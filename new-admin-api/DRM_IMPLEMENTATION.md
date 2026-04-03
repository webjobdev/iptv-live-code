# DRM Implementation Guide

This document outlines how to implement and configure Digital Rights Management (DRM) in the project using **EZDRM** and **PallyCon**.

## 1. Getting Credentials

Before configuring DRM in the admin panel, you need to obtain the necessary credentials from the respective DRM providers.

### EZDRM
To use EZDRM, you need a **PX Value** (User ID).

1.  **Sign Up/Login**: Go to the [EZDRM Website](https://www.ezdrm.com/) and log in to your account.
2.  **Locate Credential**:
    *   Navigate to your dashboard or profile settings.
    *   Find your **PX Number** (often referred to as the User ID or Client ID).
    *   *Note: This is usually a numeric value.*

### PallyCon
To use PallyCon, you need an **Account ID**, **Site Key**, and **Access Key**.

1.  **Sign Up/Login**: Go to the [PallyCon Console](https://console.pallycon.com/) and log in.
2.  **Locate Credentials**:
    *   Navigate to **Settings** > **Site Settings** (or API Settings).
    *   Copy the **Account ID**.
    *   Copy the **Site Key**.
    *   Copy the **Access Key**.

---

## 2. Configuration in Admin Panel

Once you have your credentials, you need to configure them in the application's DRM module.

### Step 1: Add DRM Account
1.  Log in to the **Admin Panel**.
2.  Navigate to the **DRM** module (usually found in the sidebar under Settings or a dedicated DRM section).
3.  Click on **"Add DRM Account"**.
4.  Fill in the form based on your provider:

    **For EZDRM:**
    *   **Name**: Enter a descriptive name (e.g., "EZDRM Production").
    *   **DRM Provider**: Select **EZDRM**.
    *   **PX Value**: Enter the PX Number you obtained from EZDRM.
    *   **Publish Now**: Toggle to Enable.

    **For PallyCon:**
    *   **Name**: Enter a descriptive name (e.g., "PallyCon Production").
    *   **DRM Provider**: Select **Pallycon**.
    *   **Account ID**: Enter your PallyCon Account ID.
    *   **Site Key**: Enter your PallyCon Site Key.
    *   **Access Key**: Enter your PallyCon Access Key.
    *   **Publish Now**: Toggle to Enable.

5.  Click **Update/Save**.

### Step 2: Add DRM Profile
After creating an account, you likely need to create a profile to define which DRM technologies to use (Widevine, FairPlay, PlayReady).

1.  In the DRM module, look for a **"DRM Profiles"** or **"Add Profile"** tab/button.
2.  Select the **DRM Account** you just created.
3.  Configure the specific settings for **Widevine**, **FairPlay**, and **PlayReady** as required by your project needs.
    *   *Note for EZDRM*: You may see specific fields for Authorization URLs depending on the setup.

---

## 3. Usage in Content

Once configured, you can apply DRM to your content (Videos, Live Events, Channels, etc.).

### Applying DRM to Video/VOD
1.  Navigate to **Manage Videos** or **VOD**.
2.  Add a new video or edit an existing one.
3.  Look for the **DRM Type** or **DRM Settings** section in the form.
4.  Select your configured DRM provider (e.g., **EZDRM** or **Pallycon**).
5.  Save the video. The system will use the configured credentials to package/protect the content.

### Applying DRM to Live Events / Channels
1.  Navigate to **Live Events** or **Manage Channels**.
2.  In the configuration form, find the **DRM Type** dropdown.
3.  Select **Pallycon** or **EZDRM**.
4.  Ensure the stream details (HLS/Dash) are compatible with the selected DRM configuration.

---

## Summary of Keys

| Provider | Required Key | Description |
| :--- | :--- | :--- |
| **EZDRM** | **PX Value** | Your unique user identifier from EZDRM. |
| **PallyCon** | **Account ID** | Your PallyCon account identifier. |
| | **Site Key** | Used for site identification. |
| | **Access Key** | Used for API authentication. |
