# Google Analytics Integration Notes

## 1. Admin Dashboard Entry Point
- File: `admin/admin_overview.php`
- UI Components:
  - Monthly Traffic Stats Card (lines 105-121)
  - Global Reach Widget (commented out in lines 239-252)

## 2. Admin Authentication
- Checked via `admin/includes/session.php`
- Checks `isset($_SESSION['admin_id'])`.

## 3. Google Service Account Credentials
- Saved at: `D:\programs\secure_credentials\green-pyramids-dd294-868e64d80653.json`
- Outside public web root: Yes (Web root is `D:\programs\xxamp\htdocs\Green Pyramids`)

## 4. Dependencies
- Composer successfully initialized and `google/analytics-data` installed under `vendor/`.
- Autoloader is available at `vendor/autoload.php`.
