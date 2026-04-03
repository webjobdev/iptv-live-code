# Xtream Codes Implementation Guide (Organization-Wise)

This document outlines the implementation of the Xtream Codes API protocol within the backend panel. The goal is to allow standard IPTV players (like Smarters, Tivimate, etc.) to connect to this system using a **Username**, **Password**, and **Server URL**.

## 1. Concept Overview

Xtream Codes is a standard API protocol legacy used by almost all IPTV players. Instead of building a custom app for every client, we create an **Adapter Layer** that speaks the "Xtream" language.

### Organization-Wise Logic (Multi-Tenancy)
**Why is this needed?**
The system is multi-tenant (SaaS), meaning one backend serves multiple different companies. We cannot simply return "all channels" to everyone.
- **The Challenge**: Standard XTREAM players usually have a single server URL. Unique users from different companies will hit the *same* URL.
- **The Solution**: We use the User's Login to determine their specific context.
    1.  **Input**: User sends `username` (email) and `password`.
    2.  **Lookup**: We find the user in the `org_subscribers` table.
    3.  **Context Switch**: We retrieve the `organization_id` from that specific user's profile.
    4.  **Data Isolation**: Every subsequent query for Channels, Movies, or Series automatically adds a `WHERE organization_id = X` clause.

**Result**: User A (Company 1) and User B (Company 2) use the same app and URL but see completely different content libraries tailored to their subscription.

---

## 2. Architecture & File Structure

We are adding a new dedicated controller and namespace for this feature to keep it clean.

### **A. Routes**
**File**: `packages/contus/app-apis/src/routes/xtream_api.php`
Xtream players are "dumb" clients; they expect files to exist at specific paths. We use Laravel routes to mimic these paths.
- `/player_api.php`: **The Core Hub**. 99% of requests go here. It handles Login, fetching Category lists, EPG, and Playlist info.
- `/get.php`: **Stream Handler**. Used when the player wants to actually play a video. e.g., `/get.php?username=..&password=..&stream_id=55`.
- `/xmltv.php`: **EPG Handler**. (Optional) returns the Electronic Program Guide in XML format for players that support it.

### **B. Controller**
**File**: `packages/contus/app-apis/src/Api/Controllers/Xtream/PlayerApiController.php`
This controller uses a **Dispatcher Pattern**. Instead of having different functions for different routes, it has one main `handle()` function.
- It checks the `$_GET['action']` parameter.
- **Switch Case**: Based on the action (e.g., `get_live_streams`), it calls the appropriate internal method to fetch data.

---

## 3. Implementation Detail: Authentication Flow

1.  **Request**: `GET /api/xtream/player_api.php?username=anu@gmail.com&password=123456`
2.  **Find User**:
    We search `OrgSubscribers` for the matching email.
    ```php
    $user = OrgSubscribers::where('email', $username)->first();
    ```
3.  **Verify Organization**:
    We ensure the organization they belong to is actually active.
    ```php
    $org = OrganizationDetail::find($user->organization_id);
    ```
4.  **Response Construction**:
    We return a fake "Server Info" block. This tells the player that everything is OK, and provides configuration like "server_url" and "timestamp".

---

## 4. Implementation Detail: Content Fetching

This is where we connect your specific backend logic to the generic Xtream output.

### The `getAssignedSets` Logic
In your system, content isn't just "in the database"; it is "assigned" to an organization.
1.  **Get Assigned IDs**:
    We call `AppApiController::getAssignedSets($orgId, 'channel')`. This function looks at the `ChannelContet` table to find which exact Channel IDs are linked to this specific Organization.
2.  **Fetch Data**:
    We then query the main `Channel` table using those IDs.
    ```php
    $channels = Channel::whereIn('id', $assignedIds)->get();
    ```
3.  **Format Mapping**:
    We map your database columns to the specific names Xtream expects.
    - Your `id` becomes `stream_id`.
    - Your `logo` becomes `stream_icon`.
    *If we don't do this mapping, the player will show blank rows.*

---

## 5. How to Test

### A. Postman (API Testing)
1.  **Method**: GET
2.  **URL**: `http://your-domain.com/api/xtream/player_api.php`
3.  **Params**:
    - `username`: (Valid Subscriber Email)
    - `password`: (Valid Password)
4.  **Expected Result**: A JSON object containing `user_info` (User details) and `server_info`.

### B. Live Player (Real World Test)
1.  **App**: Download "IPTV Smarters" or "Tivimate" on your phone.
2.  **Login Type**: Select "Login with Xtream Codes API".
3.  **Input**:
    - **Name**: Any Name
    - **Username**: Subscriber Email
    - **Password**: Subscriber Password
    - **URL**: `http://your-domain.com/api/xtream`
4.  **Verify**: You should log in successfully and see the categories/channels assigned to that user's organization.

---

## 6. API Reference (List of Actions)

These are the specific commands the player sends to `player_api.php` via the `action` parameter.

### Authentication
| Action | Description |
| :--- | :--- |
| `login` | **Default**. Authenticates the user. Returns account limits and server status. |

### Live TV
| Action | Description |
| :--- | :--- |
| `get_live_categories` | Returns a list of Categories (e.g., Sports, News). |
| `get_live_streams` | Returns the actual Channels. **Crucial**: Takes an optional `category_id` to filter results. |

### Movies (VOD)
| Action | Description |
| :--- | :--- |
| `get_vod_categories` | Returns Movie Categories (e.g., Action, Comedy). |
| `get_vod_streams` | Returns the Movie library. Includes metadata like Rating, Plot, and Cover Image. |

### TV Series
| Action | Description |
| :--- | :--- |
| `get_series_categories`| Returns Series Categories. |
| `get_series` | Returns TV Shows. Note: Series usually require a second call (`get_series_info`) to get Episodes, which we handle or bundle here depending on player preference. |
