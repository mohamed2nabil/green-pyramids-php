# Implementation Plan for Green Pyramids Fixes

## 1. Content Editor Fixes (admin/content_editor.php)
- Fix the corrupted Arabic text/emojis in the tabs (e.g., "About Page Content").
- Fix the HTML structure of the tabs. Currently, "Journey Steps" bleeds into the Product Page tab due to a missing closing `</div>`.
- Successfully inject the "Animated Hero Cards" section into the Product Page tab (previous attempt failed due to PowerShell parsing errors).

## 2. Product Page Cards Fixes
- Ensure `products.php` correctly loads the images for the Hero Cards.
- Fix the layout issue in `products.php` caused by `nl2br` on the hero heading which broke the line into "Fresh Produce f / or Global Export".

## 3. Global Site Settings
- Hook up Email, Location, Phone, and WhatsApp number globally from the `site_settings` database table.
- Ensure the WhatsApp widget (QUICK REPLY) uses the DB number.

## 4. Contact Page & Quality Page
- Connect `contact.php` Hero section to `admin/content_editor.php`.
- Connect `quality.php` Hero section to `admin/content_editor.php`.

