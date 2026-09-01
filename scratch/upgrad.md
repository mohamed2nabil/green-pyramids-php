MASTER IMPLEMENTATION PLAN — GREEN PYRAMIDS PHP
ROLE AND EXECUTION RULES

You are working on an existing PHP website repository.

Your task is NOT to redesign or rewrite the project from scratch.

Your task is to systematically audit, fix, improve, and extend the existing implementation while preserving everything that already works.

Mandatory workflow

For every phase:

Inspect the relevant existing files first.
Identify the actual root cause.
Make the smallest safe change possible.
Test for regressions.
Do not modify unrelated files.
Report exactly what changed.
Stop after completing the current phase unless explicitly instructed to continue.
Critical rules
Do NOT replace working sections with simplified versions.
Do NOT rewrite the entire index.php.
Do NOT redesign the visual identity.
Do NOT remove existing functionality unless explicitly requested.
Do NOT use overflow-x: hidden as a lazy fix for layout overflow without identifying the source.
Do NOT add unnecessary libraries.
Do NOT duplicate JavaScript logic.
Do NOT hardcode data that should come from the database.
Do NOT break the existing admin functionality.
Preserve the existing Green Pyramids design system and brand identity.
Before deleting any code, confirm it is unused.
Before changing shared CSS or JavaScript, check which pages depend on it.
PHASE 0 — FULL REPOSITORY AUDIT
Goal

Before fixing anything, perform a structured audit of the repository.

Inspect:
All public PHP pages.
index.php
about.php
products.php
productdetail.php
process.php
quality.php
contact.php
shared includes.
navigation.
footer.
CSS architecture.
JavaScript architecture.
database connection.
admin dashboard.
API endpoints.
product database flow.
review/testimonial system.
image loading.
SEO structure.
Create an audit report grouped by:
A. Critical Bugs

Examples:

broken navigation.
incorrect active navigation state.
mobile layout problems.
horizontal overflow.
broken links.
encoding problems.
database connection issues.
JavaScript errors.
B. Responsive Problems

Check:

Desktop.
Laptop.
Tablet.
Mobile.

Test at least:

1440px
1280px
1024px
768px
480px
390px
360px
C. Performance Problems

Check:

unnecessary JavaScript.
unnecessary animations.
large images.
missing lazy loading.
render-blocking assets.
duplicate CSS.
duplicate JS.
unnecessary global dependencies.
D. Database Integration Problems

Check whether these currently work correctly:

Products
Categories
Product images
Product visibility
Inquiries
Reviews / Testimonials
Site settings
E. SEO Problems

Check:

duplicate titles.
missing meta descriptions.
heading hierarchy.
image alt attributes.
canonical URLs.
structured data.
semantic HTML.
agricultural keywords.
Output

Do NOT start massive changes immediately.

First provide:

AUDIT REPORT

1. Critical issues
2. High-priority issues
3. Medium-priority issues
4. Low-priority improvements

For each issue:
- File(s)
- Root cause
- Recommended fix
- Regression risk

Then proceed phase by phase.

PHASE 1 — FIX GLOBAL NAVIGATION
Goal

The navigation must work correctly on every page and every screen size.

Problems to solve
1. Desktop navigation visibility

Currently, when opening the website, the navigation tabs may not be immediately visible correctly.

Investigate the actual cause.

Possible areas to inspect:

header positioning
z-index
hero overlap
header height
initial scroll state
is-scrolled classes
opacity
transform
CSS animations

Do NOT fix this by forcing random margins.

Find the real cause.

2. Active navigation state

Fix the navigation so the active page is always correct.

Required behavior:

Home          → index.php
About Us      → about.php
Products      → products.php
Our Process   → process.php
Quality       → quality.php
Contact       → contact.php

For product details:

productdetail.php

the active item should logically be:

Products
3. Mobile navigation

The mobile navigation must be redesigned carefully.

Current requirement:

The sidebar/drawer must NOT occupy the entire screen unnecessarily.

Preferred behavior:

Desktop:
Full horizontal navigation.

Tablet/Mobile:
Compact hamburger menu.
Mobile drawer requirements
Drawer width

Use a responsive width such as:

width: min(420px, 88vw);

Do NOT use:

width: 100vw;

unless the screen is extremely small and necessary.

Overlay

When the mobile menu opens:

show a dark semi-transparent overlay.
the page behind the drawer should remain visually visible.
clicking outside the drawer closes it.
clicking inside the drawer must NOT close it.
Closing behavior

The menu must close when:

Clicking the overlay.
Clicking a navigation link.
Pressing Escape.
Clicking the close/hamburger button again.
Accessibility

Add:

aria-expanded
aria-controls
aria-hidden where appropriate

Ensure keyboard navigation works reasonably.

Mobile menu animation

Use a clean animation:

Overlay:
opacity fade.

Drawer:
translateX or translateY depending on design direction.

Avoid excessive animation.

Testing

Test:

Home
About
Products
Process
Quality
Contact

On:

Desktop
Tablet
Mobile
PHASE 2 — FIX RESPONSIVE LAYOUT
Goal

Make the entire public website properly responsive.

Important rule

Do NOT apply a global:

overflow-x: hidden;

until the actual horizontal overflow source is identified.

Audit every major section
Homepage

Check:

Hero
Product showcase
Product cards
Origin section
Supply chain
Markets
Partner CTA
Testimonials
Footer
Product cards

Current problem:

The product cards can feel too static and do not adapt elegantly.

Requirements:

Desktop

Cards should:

have consistent height.
maintain proper image aspect ratio.
not stretch randomly.
have predictable spacing.
Tablet

Cards should:

reduce visible count naturally.
maintain readable content.
avoid clipped text.
Mobile

Cards should:

be easy to swipe.
not overflow the viewport.
have large enough touch targets.
preserve image quality.
Images

Do NOT force every image into the same arbitrary dimensions.

Use appropriate containers such as:

aspect-ratio
object-fit: cover
object-position

depending on the section.

PHASE 3 — HOMEPAGE HERO ANIMATION
Goal

Implement the requested professional text typing/reveal effect.

The hero must NOT look like a cheap terminal typing animation.

Required effect

The hero headline should appear as if it is being written/revealed naturally in front of the client.

Example concept:

From Egyptian Soil
To Global Markets.
Preferred animation

Use a refined text reveal / typing hybrid.

Sequence
Small eyebrow text appears.
Main headline begins revealing progressively.
The reveal should feel intentional and premium.
Avoid showing a visible blinking terminal cursor unless it fits the design.
Subtext appears afterward.
CTA appears last.
Performance

Use:

CSS
or
GSAP

only where justified.

Respect:

prefers-reduced-motion
Important

The animation must run correctly on:

Desktop
Mobile
Reload
Navigation back to home

Do not cause layout shifts while text animates.

PHASE 4 — PRODUCT CARD ENTRANCE ANIMATION
Goal

Product cards must not appear suddenly as static blocks.

Required behavior

When the user reaches the product section:

Cards should animate into view.

Preferred animation

Use subtle stagger animation.

Example:

opacity: 0 → 1
translateY: 30px → 0

Each card enters slightly after the previous one.

Do NOT use
aggressive bouncing
large rotations
excessive scaling
slow animations
Scroll behavior

The animation should trigger once when appropriate.

Do not repeatedly replay every time the user scrolls a few pixels.

Important

If GSAP is used:

load it only where needed.
do not make it a heavy dependency on every page unless required.
scope homepage animations properly.
PHASE 5 — LAZY LOADING AND IMAGE PERFORMANCE
Goal

Improve loading speed without hurting the visual experience.

Image strategy
Above the fold

Hero image / critical image:

Do NOT blindly lazy load.

Prioritize the actual LCP element.

Below the fold

Use:

loading="lazy"
decoding="async"

where appropriate.

Add dimensions

Images should have:

width
height

or stable aspect ratio containers to reduce:

CLS — Cumulative Layout Shift
Audit all images

Check:

image size
file format
duplicate files
unused images
oversized images
Preferred modern formats

Where practical:

WebP
AVIF

But do NOT convert everything blindly.

Responsive images

Where justified, implement:

srcset
sizes

especially for large homepage images.

PHASE 6 — PERFORMANCE OPTIMIZATION
Goal

Improve performance without breaking functionality.

Audit JavaScript

Inspect:

main.js
animations.js
hero.js

Determine whether logic is duplicated.

Important architectural rule

Do NOT turn one shared JavaScript file into a giant dumping ground.

Separate logic conceptually:

global functionality
navigation functionality
homepage functionality
page-specific animations
Load scripts intelligently

Example:

navigation.js → shared
main.js → shared only if needed
homepage.js → index.php only
process.js → process.php only if needed

Do not create unnecessary files merely for the sake of it.

CSS

Audit:

duplicate selectors.
conflicting media queries.
unused code where safely identifiable.
overly broad selectors.
Performance targets

Aim to improve:

LCP
CLS
INP

without fake optimization tricks.

PHASE 7 — PRODUCT DATABASE FLOW
Goal

Make the public product system truly database-driven.

Public products page

Products displayed should come from the database.

Required fields may include:

id
name
slug
category
description
image
availability
season
visibility

Use the existing database schema where possible.

Do NOT create a duplicate product system if one already exists.

Product detail page

The page should load the correct product using a safe identifier.

Preferred architecture:

slug

or existing secure ID flow.

Validate all input.

Visibility

If a product is hidden in admin:

It should not appear publicly.

Empty state

If no products exist:

Display a proper graceful message.

Do NOT show broken cards.

PHASE 8 — REVIEWS / TESTIMONIALS DATABASE SYSTEM
Goal

Reviews must come from the database and be manageable through the Admin Dashboard.

PUBLIC FLOW
Database
   ↓
Approved Reviews
   ↓
Public Homepage

Only approved/visible reviews should appear publicly.

ADMIN FLOW

Create a dedicated admin section/tab:

Reviews

or:

Testimonials
Required admin functionality
List reviews

Display:

Client Name
Company
Country
Review
Rating
Status
Created Date
Actions

Admin should be able to:

Add
Edit
Delete
Approve
Hide
Suggested statuses
pending
approved
hidden
Public display

Only:

approved

reviews should appear on the website.

Database

First inspect whether a reviews/testimonials table already exists.

If it exists

Extend it safely.

If it does not exist

Create a clean migration or SQL setup consistent with the existing project structure.

Do NOT silently modify production data.

PHASE 9 — ADMIN DASHBOARD INTEGRATION
Goal

Continue connecting the public website with the admin dashboard.

First inspect current admin architecture

Identify:

Authentication
Dashboard layout
Navigation
API structure
Database access
Existing CRUD patterns
Do NOT rebuild admin from scratch

Extend the existing architecture.

Integration roadmap
Step 1

Products

Ensure:

Admin Product CRUD
        ↓
Database
        ↓
Public Website

works completely.

Step 2

Reviews

Admin Reviews
        ↓
Database
        ↓
Public Testimonials
Step 3

Contact inquiries

Public Contact Form
        ↓
Database
        ↓
Admin Dashboard

Admin should be able to see:

Name
Email
Phone
Company
Message
Date
Status
Step 4

Site settings

Eventually connect manageable settings such as:

Company email
Phone
WhatsApp
Social links
Address

Do NOT hardcode information that the admin should manage.

PHASE 10 — CONTACT FORM AND INQUIRIES
Goal

Make the contact flow production-ready.

Required validation

Client side validation:

For better UX.

Server side validation:

Mandatory.

Security

Check:

SQL injection
XSS
CSRF
input validation
output escaping
Inquiry flow
User submits form
        ↓
Validation
        ↓
Database
        ↓
Admin Dashboard
        ↓
Status management

Suggested statuses:

new
contacted
in_progress
closed
PHASE 11 — SEO STRUCTURE
Goal

Optimize the website for Egyptian agricultural exports and international agricultural markets.

Important

Do NOT keyword stuff.

SEO must sound natural and professional.

CORE SEO TOPICS

The website should be relevant to terms around:

Egyptian agricultural exports
Egyptian fresh fruits exporter
Egyptian fresh vegetables exporter
Fresh produce exporter Egypt
Citrus exporter Egypt
Egyptian oranges export
Navel oranges Egypt
Valencia oranges Egypt
Grapefruit exporter Egypt
Pomegranate exporter Egypt
Fresh vegetables supplier Egypt
Agricultural products Egypt
Global fresh produce supplier
Farm to export Egypt
Egypt agricultural export company
Premium Egyptian produce
PAGE-SPECIFIC SEO
Home

Focus on:

Egyptian agricultural exports
Fresh fruits
Fresh vegetables
Global markets
Agricultural supply
About

Focus on:

Egyptian agriculture
Agricultural production
Farming conditions
Nile Delta
Upper Egypt
Products

Focus on:

Fresh produce exporter
Egyptian fruits
Egyptian vegetables
Citrus export
Seasonal crops
Product details

Generate unique metadata based on the actual product.

Example structure:

{Product Name} Exporter from Egypt | Green Pyramids
Process

Focus on:

Harvesting
Selection
Cooling
Packing
Inspection
Export
Cold chain
Quality

Focus on:

Food safety
Quality control
Traceability
Packing standards
Export quality
TECHNICAL SEO

Every page should have:

Unique title
<title>
Unique meta description
<meta name="description">
Canonical URL

Where appropriate.

Heading hierarchy

Use:

One H1
Logical H2 sections
H3 only when appropriate

Do NOT use headings only for visual styling.

Images

Every meaningful image should have meaningful:

alt=""

Avoid useless alt text like:

image1
photo
picture
Structured data

Where appropriate, add:

Organization
WebSite
BreadcrumbList
Product

Use valid JSON-LD.

Do NOT generate fake reviews or fake ratings in structured data.

PHASE 12 — ACCESSIBILITY
Goal

Improve usability while fixing the UI.

Check:

Keyboard navigation
Focus states
Color contrast
Mobile touch targets
Alt text
Button labels
Form labels
ARIA attributes
Reduced motion
PHASE 13 — SECURITY AUDIT
Audit all write endpoints

Especially:

save_product.php
delete_product.php
toggle_visibility.php
submit_inquiry.php
save_settings.php
save_content.php
Check
Authentication
Authorization
CSRF protection
Prepared statements
Input validation
Output escaping
File upload validation
Error leakage
Error logs

Do not expose error logs publicly.

Check whether:

error_log

files are tracked in the repository.

If they contain sensitive runtime information:

remove them from version control appropriately.
add correct ignore rules.
do not delete useful production configuration blindly.
PHASE 14 — FINAL RESPONSIVE QA

After all changes:

Test every public page.

Required pages
Home
About
Products
Product Details
Our Process
Quality
Contact
Required breakpoints
1440px
1280px
1024px
768px
480px
390px
360px
Check
No horizontal scrolling
No clipped text
No overlapping navigation
No broken images
No broken links
No layout jumping
No hidden important content
No oversized mobile menu
No animation breaking layout
No console errors
PHASE 15 — FINAL PERFORMANCE QA

Check:

Large images
Lazy loading
Critical images
Script loading
Unused dependencies
Layout shifts
Animation performance
PHASE 16 — FINAL SEO QA

Verify:

Unique title per page
Unique meta description
One H1
Correct H2 hierarchy
Image alt text
Canonical structure
Structured data validity
Internal links
Agricultural export keywords naturally integrated
REQUIRED GIT WORKFLOW

After each major completed phase:

git status

Review changed files.

Do NOT commit unrelated changes.

Use focused commits.

Example:

fix: repair responsive navigation behavior
fix: resolve homepage horizontal overflow
feat: add database-driven testimonials
perf: optimize image loading and animations
REQUIRED REPORT AFTER EACH PHASE

After completing each phase, provide:

1. What was inspected
Files inspected:
- ...
2. Root cause

Explain the actual problem.

3. Changes made
File:
Change:
Reason:
4. Regression check

List:

Pages tested
Responsive sizes tested
Functionality checked
5. Remaining issues

Clearly state anything not yet fixed.

EXECUTION ORDER — STRICT

Execute in this exact order:

PHASE 0
Repository Audit
↓
PHASE 1
Navigation Fix
↓
PHASE 2
Responsive Layout Fix
↓
PHASE 3
Hero Text Animation
↓
PHASE 4
Product Card Animation
↓
PHASE 5
Lazy Loading & Images
↓
PHASE 6
Performance Optimization
↓
PHASE 7
Product Database Integration
↓
PHASE 8
Reviews Database + Admin Tab
↓
PHASE 9
Admin Dashboard Integration
↓
PHASE 10
Contact/Inquiries Integration
↓
PHASE 11
SEO
↓
PHASE 12
Accessibility
↓
PHASE 13
Security Audit
↓
PHASE 14
Responsive QA
↓
PHASE 15
Performance QA
↓
PHASE 16
SEO Final QA
VERY IMPORTANT — DO NOT SKIP THE EXISTING BUGS

The following issues are known and must be explicitly checked:

1. Navbar tabs are not reliably visible immediately on first page load.

2. Home/About navigation state has previously behaved incorrectly and must be verified.

3. Mobile menu must NOT unnecessarily cover the entire screen.

4. Mobile menu must have an overlay.

5. Clicking outside the drawer must close it.

6. Clicking inside the drawer must not close it.

7. Product images and homepage sections need better responsive behavior.

8. Remove any unwanted green-light/focus-style visual effect from active elements unless intentionally part of the design system.

9. Remove corrupted text such as:
   ΓåÆ
   and any mojibake/encoding artifacts.

10. Fix the requested premium typing/reveal animation for the homepage hero headline.

11. Add subtle stagger entrance animation for product cards.

12. Reviews/testimonials must come from the database.

13. Reviews must have a dedicated Admin Dashboard management tab.

14. Implement lazy loading correctly.

15. Improve website performance.

16. Improve SEO specifically for Egyptian agricultural exports and global fresh produce markets.

17. Continue the connection between the public website and Admin Dashboard instead of treating them as separate systems.

18. Do not break existing working pages while fixing the homepage.