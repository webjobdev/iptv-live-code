# Subscriber Module Documentation

This document provides a comprehensive overview of the Subscriber Module requirements based on the system design specifications. It covers profile management, device handling, subscription logic, and payment workflows.

---

## 1. Module Overview
The Subscriber module is designed for creating, configuring, and maintaining individual subscribers within the IPTV middleware. It manages:
1.  **Personal Information**
2.  **Hardware (Devices)**
3.  **Subscriptions & Billing**
4.  **Content Customization (Custom Streams)**
5.  **Notes & Support History**

---

## 2. Core Sub-Modules

### 2.1 Subscriber Profile (General Information)
*   **Path**: `Subscribers > Add/Edit Subscriber`
*   **Key Fields**:
    *   **Organization**: Association with a parent entity.
    *   **Account Number**: Auto-generated or manual ID.
    *   **Credentials**: Username, Password, and PIN (for parental control/locked content).
    *   **Contact**: Email, Phone, Address, City, Country, Zip.
    *   **Localization**: Language and Timezone.

### 2.2 Device Management
*   **Path**: `Subscribers > Edit Subscriber > Devices`
*   **Features**:
    *   **Inventory**: MAC Address, Serial Number, Identifier, IP Address.
    *   **Status Control**: Enabled/Disabled and Active/Inactive toggles.
    *   **Linking**: Assigning unassigned devices to a subscriber.
    *   **Actions**: Edit hardware details or unassign devices.

### 2.3 Subscription & Payments
*   **Path**: `Subscribers > Edit Subscriber > Activations > Subscription Payments`
*   **Types of Transactions**:
    1.  **Custom Subscription**: Flexible duration (Days/Months) with Override or Top-up logic.
    2.  **Subscription Sets**: Predefined packages from the Organization.
    3.  **Free Subscription**: Zero-cost plans for testing or promotions.
    4.  **Add Devices/Slots**: Expanding the subscriber's device limit.
    5.  **Accessories**: One-time hardware purchases.
    6.  **Custom Charge**: Manual adjustments or miscellaneous fees.
    7.  **Bundles**: Specific content groups (e.g., Sports, Movies).

### 2.4 Custom Streams
*   **Path**: `Subscribers > Custom Streams`
*   **Functionality**: Allows admins to assign specific TV Channels or VODs directly to a single subscriber, bypassing standard plan restrictions.
*   **UI Pattern**: Dual-list drag-and-drop (Available vs. Assigned).

### 2.5 Billing & Credit Cards
*   **Gateways**: Supports Authorize.net (AIM) or Local System storage.
*   **Features**: Multiple card profiles, billing address overrides, and transaction history.

---

## 3. Implementation Guidelines

### 3.1 Frontend (AngularJS + Blade)
*   **Grid Lists**: Always use the `data-grid-view` directive. It ensures consistent pagination, search, and sort functionality across all tables.
*   **Form Handling**: Apply `data-base-validator` for automated server-side error mapping to the UI.
*   **Date Calculations**: Use normalized date objects (set to `00:00:00`) for all duration calculations to avoid timezone shifts.
*   **Standard Sockets**: Ensure `durationText` is updated in real-time when inputs change using `$watch` or `ng-change`.

### 3.2 Backend (Laravel)
*   **Override Logic**: When an "Override" activation is selected, existing active subscriptions must be marked as `overridden` in the database.
*   **Top-up Logic**: The `start_date` for a top-up should automatically equal the current `end_date` of the last active subscription.
*   **Prorating**: Calculate prices based on remaining days when switching plans mid-cycle.

---

## 4. Accelerated Development Tips
1.  **Directives**: Create a shared directive for the **Device Toggle** and **Bundle Selection** as they are used across multiple payment screens.
2.  **Request Factory**: Utilize the existing `requestFactory` in `activation.js` for all async operations to handle loading states automatically.
3.  **Blade Partials**: Separate the "Order Summary" component into a Blade partial so it can be shared between Subscription Sets and Custom Subscriptions.

---
*Generated based on Subscriber Modules Design Document - April 2026*
