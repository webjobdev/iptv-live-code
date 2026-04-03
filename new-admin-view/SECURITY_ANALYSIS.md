# Security Breach Analysis Report

**Projects Analyzed:**
- `new-admin-view` (Frontend Management)
- `new-admin-api` (Backend Services)

**Date:** 2026-03-27
**Severity Assessment:** High Risks Identified

---

## 1. Critical Vulnerabilities (Immediate Action Required)

### ⚠️ Hardcoded Secrets in Version Control
Both projects contain sensitive credentials in `.env.example` and potentially other files.
- **Location:** `new-admin-view/.env.example` and `new-admin-api/.env.example`
- **Exposed Keys:**
  - **AWS Keys:** `AWS_SECRET` and `AWS_KEY` are hardcoded.
  - **MongoDB:** Real connection strings including `MONGO_DB_URI` with username/password (`dev:dev`).
  - **Razorpay:** `RAZORPAY_API_KEY` and `RAZORPAY_API_SECRET`.
  - **Wowza:** `WOWZA_CLOUD_API_KEY` and `WOWZA_CLOUD_ACCESS_KEY`.
  - **JWT:** `JWT_SECRET` is common across examples.
- **Risk:** Anyone with access to the source code (current/former employees, attackers who gain read access) can control your infrastructure, dump your database, or perform financial transactions.
- **Recommendation:** **ROTATE ALL KEYS IMMEDIATELY**. Remove all secrets from `.env.example` and never commit real keys to version control. Use a secret manager or encrypted environment variables.

### ⚠️ Unprotected API Endpoint (Candidate for RCE/Arbitrary Upload)
An API route for video uploads exists outside of authentication middleware.
- **Location:** `new-admin-api/packages/contus/video/src/routes/api.php`
- **Route:** `Route::post('media/upload-video', [VideoController::class, 'store']);` (Line 381)
- **Risk:** It is placed outside the `jwt-auth` middleware group. Although the `store` method appears missing in the current controller, if a method with that name exists or is added, an attacker can upload arbitrary files without any credentials.
- **Recommendation:** Move all administrative and media-handling routes inside the `jwt-auth` middleware group.

---

## 2. High Risk Vulnerabilities

### ⚠️ Insecure CORS Policy
The API allows requests from any origin.
- **Location:** `new-admin-api/config/cors.php`
- **Code:** `'allowed_origins' => ['*']`
- **Risk:** Allows malicious websites to perform cross-origin requests to your API. While JWT provides some protection, this simplifies CSRF-style attacks and data exfiltration.
- **Recommendation:** Explicitly list your frontend domains in `allowed_origins` (e.g., `https://admin.yourdomain.com`).

### ⚠️ End-of-Life (EOL) Environment
The projects use outdated technologies.
- **PHP Version:** `^7.3` (EOL since Dec 2021)
- **Laravel Version:** `^8.0` (Security fixes stopped in Feb 2025)
- **Risk:** Known vulnerabilities in PHP 7.3 and Laravel 8 will never be patched in your system. This includes potential memory corruption or remote execution bugs in the engine itself.
- **Recommendation:** Upgrade to PHP 8.2+ and Laravel 10/11.

---

## 3. Medium & Low Risk Issues

### ⚠️ SQL Injection Pattern Risk
While Laravel's Eloquent is generally safe, the code uses `whereRaw` and `selectRaw` with string concatenation.
- **Location:** `new-admin-api/packages/contus/video/src/Traits/CollectionTrait.php` (Lines 350, 353, 376)
- **Example:** `->whereRaw ( 'scheduledStartTime < "' . Carbon::now ()->now () . '" ' )`
- **Risk:** While use of `Carbon` is safe, the *pattern* of concatenating variables into raw queries is dangerous. If any user-supplied variable is used this way, it's a direct SQL Injection.
- **Recommendation:** Always use parameter binding: `->whereRaw('scheduledStartTime < ?', [Carbon::now()])`.

### ⚠️ Cross-Site Scripting (XSS) in Blade
Extensive use of raw output tags in Blade templates.
- **Location:** Multiple files in `new-admin-view/packages/contus/*/src/resources/views/`
- **Code:** `{!! $variable !!}`
- **Risk:** This skips HTML escaping. If `$variable` contains user-submitted data (like a video title edited by a low-privilege admin), a malicious script can be injected.
- **Recommendation:** Only use `{!! !!}` for trusted HTML content. Default to `{{ $variable }}` which automatically escapes output.

### ⚠️ Insecure Logout
The JWT logout doesn't activeley invalidate the token.
- **Location:** `AuthContoller.php` (Line 195)
- **Risk:** `Auth::logout()` in a stateless JWT environment doesn't stop the client from using the same token until it expires.
- **Recommendation:** Implement a JWT Blacklist to invalidate tokens on the server side upon logout.

---

## Summary Table

| vulnerability | Severity | Status |
| :--- | :--- | :--- |
| Hardcoded Credentials | **Critical** | Fixed? No |
| Unprotected Uploads | **Critical** | Fixed? No |
| Open CORS Policy | **High** | Fixed? No |
| Outdated PHP/Laravel | **High** | Fixed? No |
| SQL Injection Risks | **Medium** | Fixed? No |
| XSS in Blade | **Medium** | Fixed? No |

**Next Steps:**
1. **Rotate AWS and Database passwords immediately.**
2. **Move upload routes behind JWT middleware.**
3. **Restrict CORS origins to specific domains.**
4. **Plan an upgrade path for PHP and Laravel versions.**
