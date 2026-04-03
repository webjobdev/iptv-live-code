# Catch-Up TV Analysis and Implementation Guide

Based on the provided OTT platform document, below is a detailed analysis of the **Catch-Up TV** module and a proposed guide on how to implement it within the admin panel's `channel-services` structure.

## Overview
Catch-Up TV in IPTV is a "time-shifted" feature that allows subscribers to view TV programs for a specific window of time (typically 3 to 30 days) after they have originally aired. It functions as a bridge between live broadcasting and pure Video on Demand (VOD). This module sits under **Channel Services**.

### How It Works Technically
1. **Recording & Storage**: The server continuously records live broadcast streams as they happen. These recordings are temporarily stored as video files on the provider's server or a Cloud DVR system.
2. **Unicast Delivery**: Unlike live TV (which is broadcasted simultaneously), Catch-Up content is delivered via Unicast. A dedicated stream is created directly between the server and the subscriber's device upon pressing play.
3. **EPG Integration**: These stored files are linked to the Electronic Program Guide (EPG). When navigating backward in the guide, the app requests the corresponding stored file instead of the live stream.

### How to Implement Technical Concepts in This Project
- **Recording & Storage Mapping**: Since our middleware (`channel-services`) does not directly process the video files, the "Storage" aspect is delegated to the server specified in the **Streaming Provider** dropdown (e.g., Flussonic, Nimble, Akamai). The database merely stores the configuration (`CatchupTv` model: `days`, `schedule_base`, `url`).
- **Unicast Delivery Mapping**: To ensure a secure, dedicated stream for the subscriber, we depend on the **Playback Token Generator** fields. When the Client App requests a Catch-Up stream, our API generates a unique Unicast token appended to the Streaming URL or Custom Streaming URL to prevent unauthorized sharing.
- **EPG Integration Mapping**: The most crucial code path will be in the Client API (not the Admin API). When the Client App requests the EPG schedule (`EPG Service`), the backend must join the EPG data with the `catchup_tvs` table. If a past program falls within the allowed `days` range, the API dynamically renders a `catchup_url` in the EPG JSON payload for that specific program.

### Key Features for Users
- **VCR-Style Controls**: Viewers can pause, rewind, or fast-forward through the recorded content.
- **Restart Function**: Allows restarting a live program that has already begun ("Start-over").
- **Limited Availability**: Content usually expires and is automatically deleted after a set period (e.g., 7 days) to save storage.

### Important Business Rules
1. **Permissions**: A special option/permission rule is required to grant access to Catch-up TV settings to users.
2. **Cardinality**: Only **one** Catch-Up configuration can be created for any specific TV Channel. 
3. **Visibility**: The Catch-up must be fully created and 'Enabled' to be displayed inside the Client App. 
---

## 1. Add/Edit Catch-Up TV (Form Implementation)
**Admin Path:** `Channel Services (sidebar menu) > Catch-Up TV > Add Catch Up TV`

When implementing the create/update form, ensure the following fields and dynamic behaviors are present:

### Field Specifications:
1. **TV Channel (Required)**: Auto-complete / Search dropdown. The user types at least 1 character to search for the required channel.
2. **Description**: Text input describing the Catch-Up.
3. **Days (Required)**: Numeric input. Number of days the catch-up content remains available.
4. **Schedule Base**: Dropdown with two available values:
   - `Hourly` (catch-ups are created at each hour).
   - `EPG` (catch-ups are created according to the EPG schedule).
5. **Streaming Provider**: Select input for choosing the streaming provider.
6. **Custom Streaming URL**: A checkbox/toggle. When enabled, it allows the user to set a custom URL that is different from the main Channel's URL. It leverages its own Token Generator and DRM profiles.
7. **URL**: Target string input for the Catch-Up stream.
8. **DRM Profile**: Multi-select component. 
   - **Crucial Validation**: A user can select several DRM Profiles for one URL, but they **must** belong to the same DRM Provider (e.g., PallyCon OR EzDRM - not mixed). Automatically filter the dropdown list based on the first selected DRM provider.
9. **Playback Token Generator**: Checkbox/toggle to activate the token generator.
10. **Token Generator**: Select input (visible only if Playback Token Generator is enabled).
11. **Enable**: Toggle switch or checkbox to make the newly created Catch-Up available to use immediately.

### Action Buttons:
- **Create**: Add a new record.
- **Update**: Save changes (visible only in Edit mode).
- **Cancel**: Abort and return to the list.

---

## 2. List of Catch-Ups (Grid/Table Implementation)
**Admin Path:** `Channel Services (sidebar menu) > Catch-Up TV`

The listing page should be a robust data table displaying all configured catch-ups.

### Table Columns & Features:
1. **Add Catch Up Button**: Always accessible at the top of the grid to trigger the creation form.
2. **Channel Logo**: Image preview. Sortable default. Disables gracefully if logo is missing from the parent TV Channel settings.
3. **Channel Name**: Label. Clickable link that redirects to the TV Channel's edit mode. 
   - *Filter*: Single selection search.
   - *Sort*: Ascending (default).
4. **Channel Status**: Non-editable display of the Parent TV Channel's status (Enabled/Disabled).
5. **Description**: Fallbacks to 'Channel Name' if unassigned.
   - *Filter*: Supported.
   - *Sort*: Supported.
6. **Days**: 
   - *Filter*: Ranged filter (Min and Max values).
   - *Sort*: Supported.
7. **Schedule**:
   - *Filter*: Dropdown selection (Hourly / EPG).
   - *Sort*: Supported.
8. **Enable (Status)**: Quick toggle to change Catch-Up status (Enable/Disable). Filterable via single selection.
9. **Action Column**: Dropdown or icon buttons for:
   - Change Status.
   - Edit Catch-Up.
   - Delete Catch-Up.

---

## 3. Recommended Folder & Backend Architecture (`channel-services`)

To store and structure this in your `packages/contus/channel-services` package:

### Backend Architecture (Laravel/PHP Modules)
- **Migrations**: Create a migration for `catchup_tvs`. It should contain columns like `channel_id`, `description`, `days`, `schedule_base`, `streaming_provider`, `is_custom_url`, `url`, `is_token_generator`, `token_generator_id`, `is_active`.
- **Models**: Create `CatchupTv.php`. Establish a `belongsTo` relationship with the `Channel` model. (And a `hasOne` Catchup relation on the existing `Channel` model).
- **Controllers**: `CatchupTvController.php` with `index`, `store`, `update`, `destroy`, `changeStatus` endpoints under `api/admin`.
- **Form Requests**: `StoreCatchupTvRequest.php` – Must validate the 1:1 mapping (ensure `channel_id` is unique in the catchups table) and DRM Provider homogeneity.
- **Repository**: Define `CatchupTvRepository.php` to handle grid search algorithms, particularly the min/max filters for the "Days" column.

### Frontend Architecture (Admin SPA - Angular/React/Vue depending on your setup)
- **Sidebar Menu**: Add the routing paths properly to your Sidebar constants file.
- **Views/Pages**:
  - `channel-services/views/catchup/list.html` (or `.blade.php` / JSX depending on UI layer)
  - `channel-services/views/catchup/form.html`
- **Controllers/Components**: Add logic to handle the DRM cascade rules and ensure Token Configuration expands when toggled.

### Final Checklist for Developers
- [ ] Has the Permission Rule for `Catch-Up TV` been added to the Super Admin seeding?
- [ ] Is the DRM validation rigidly checking against cross-provider profiles?
- [ ] Does deleting a Catch-Up successfully clear it out without affecting the Parent Channel?
- [ ] Does the `Channel Name` link correctly reroute to the Channel Edit Page?
