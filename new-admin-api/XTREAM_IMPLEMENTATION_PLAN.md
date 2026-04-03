# Xtream Codes (XC) API Implementation Guide

## Overview
Implementing "Xtream UI" or "Xtream Codes Support" in your middleware means building an API layer that mimics the standard Xtream Codes protocol. This allows popular IPTV players (like **IPTV Smarters, Tivimate, XCIPTV, LTQ**) to connect to your system using just a **Username, Password, and Server URL**.

## 1. Architectural Strategy

**Core Principle:**
Xtream is **NOT** something you install alongside. 
Xtream is an **Adapter Layer** you build **ON TOP** of your existing middleware.

*   **Your Middleware:** Owns the "Business Logic" (Organizations, Users, Plans, Streams).
*   **Xtream Adapter:** A stateless translation layer.
    *   Reads Xtream API requests (`player_api.php`, `get.php`)
    *   Queries your middleware via internal APIs.
    *   Returns standard Xtream JSON.

### Organization Awareness (Multiple Tenants)
Since Xtream protocol only supports `username` & `password` but your system supports multiple Organizations, we **MUST** resolve the organization via **Domain/Subdomain**.

*   `org1.yourdomain.com` -> Maps to Organization 1
*   `org2.yourdomain.com` -> Maps to Organization 2

## 2. Implementation Plan

We will create a specific module/folder for this adapter to keep it clean and separate from your admin panel logic.

### Directory Structure (Recommended)
```text
modules/
 └── XtreamAdapter/
     ├── Controllers/
     │   ├── PlayerApiController.php
     │   ├── PlaylistController.php
     │   ├── StreamController.php
     │   └── EpgController.php
     ├── Services/
     │   ├── OrganizationResolver.php  (Resolves Org from Domain)
     │   ├── MiddlewareClient.php      (Queries your own Core Middleware models)
     │   └── SessionManager.php        (Redis Connection Limiting)
     ├── routes.php
     └── XtreamServiceProvider.php
```

### Phase 1: The Core Routes (Xtream Protocol)

You need to expose exactly these public routes:

| Route | Controller Action | Purpose |
| :--- | :--- | :--- |
| `/player_api.php` | `PlayerApiController@index` | Main Entry (Login, Channel Lists, VOD Lists) |
| `/get.php` | `PlaylistController@index` | M3U Playlist Generator |
| `/xmltv.php` | `EpgController@index` | EPG XML Feed |
| `/live/{u}/{p}/{id}.ts` | `StreamController@live` | Live Stream Playback/Redirect |
| `/movie/{u}/{p}/{id}.mp4` | `StreamController@movie` | VOD Playback |
| `/series/{u}/{p}/{id}.mp4` | `StreamController@series` | Series Playback |

### Phase 2: Logic Flow

#### 1. Login (`player_api.php` with no action)
1.  **Resolve Org:** Check `request()->getHost()` to find the Organization ID.
2.  **Validate User:** Check `Subscribers` table for `username`, `password`, and `org_id`.
    *   Check Status (Active/Inactive).
    *   Check Expiry Date.
    *   Check Package (Plan) limits.
3.  **Return JSON:**
    ```json
    {
      "user_info": {
        "auth": 1,
        "username": "...",
        "status": "Active",
        "exp_date": "1735689600",
        "max_connections": "2"
      },
      "server_info": { ... }
    }
    ```

#### 2. Fetch Data (`player_api.php?action=...`)
*   `get_live_categories`: Query your `Category` model (filtered by Org & Type).
*   `get_live_streams`: Query your `Channel` model. Map fields:
    *   `id` -> `stream_id`
    *   `logo` -> `stream_icon`
    *   `epg_id` -> `epg_channel_id`
*   `get_vod_streams`: Query your `VOD` model.
*   `get_series`: Query your `TV Show` model.

#### 3. Playback (`/live/user/pass/id`)
1.  **Authenticate & Org Resolve** again.
2.  **Concurrency Check**:
    *   Use **Redis** key: `active:{org}:{username}`.
    *   If active IP count >= `max_connections`, **BLOCK** (429 Too Many Connections).
    *   Else, add User IP to Redis (with short TLL, e.g., 30s).
3.  **Resolve Stream**:
    *   Find the `Source URL` for the requested Channel ID.
4.  **Rewrite/Redirect**:
    *   Redirect user to the actual HLS/CDN URL.
    *   (Optional) Tokenize the URL for security.

## 3. Database & Modules Mapping

You already have the necessary tables in your middleware. We just need to map them.

| Xtream Concept | Your Middleware Table/Model | Notes |
| :--- | :--- | :--- |
| **User** | `Subscribers` | Filter by `org_id` |
| **Expiry** | `Subscribers->expiry_date` | |
| **Conns** | `Subscribers->devices` or Package limit | Enforce via Redis in Adapter |
| **Live Stream** | `Channels` | Map `id` to `stream_id` |
| **Category** | `Categories` | Filter by Type (Live/VOD) |
| **VOD** | `VOD` (Movies) | |
| **Series** | `TV Shows` | Handles Seasons/Episodes |
| **EPG** | `EPG Programs` | XMLTV generation needed |

## 4. Next Steps for Implementation

1.  **Create the Adapter Module:** Set up the `XtreamAdapter` directory structure.
2.  **Define Routes:** Register the standard Xtream routes in your `routes/web.php` or `api.php`.
3.  **Implement Org Resolver:** Middleware/Helper to get `org_id` from Domain.
4.  **Implement `PlayerApiController`:** Start with Auth & Live Streams.
5.  **Implement `StreamController`:** Redirect logic with Redis limiting.
