# Bloom-and-Veg — Session Log

Purpose: keep a running, repo-local record of what we changed and why.

Note: This file is used as the “saved chat” summary.

## Logging rule (so you don’t lose progress)
- After every meaningful request (setup steps, fixes, new features), we append a short recap here.
- We do **not** store passwords/API keys in this file.

## Security note
- This file deliberately does **not** store passwords, API keys, or other secrets.
- If you need an admin password reset, we’ll do it via WP‑CLI (see “Handy commands”).

---

## 2026-01-18

### WordPress Admin access
- Confirmed WordPress stack is running via Docker Compose.
- Verified `/wp-admin/` redirects correctly to `/wp-login.php`.
- Codespaces forwarded host detected: `cautious-space-fishstick-qxpxq75jwxqf9x44-8080.app.github.dev`.
- Admin user exists: `admin` (role: administrator).
- Reset the `admin` password via WP‑CLI (password not recorded here).

### Logo
- Theme already supports WordPress “Custom Logo”; updated header to avoid empty tagline output and added CSS so wide logos fit cleanly in the sticky header.
- Set the logo in WP Admin: Appearance → Customize → Site Identity → Logo.

### Upload limit
- Increased PHP upload limits in Docker (was 2MB). New limits: `upload_max_filesize=64M`, `post_max_size=64M`, `memory_limit=256M`.

### Navigation pages + menu
- Created/published category-named pages: Fruit & Veg, Dairy, Eggs & Chilled, Bakery, Food Cupboard, Butchery, Drinks & Juices, Snacks, Garden, BBQ, Coal + Logs, Household, Pet.
- Created menu “Category Bar” and assigned it to the theme’s Primary menu location so the top navigation shows those names in one line.

### Dropdowns
- Added dropdown (submenu) items for: Fruit & Veg, Dairy, Eggs & Chilled, Bakery, Food Cupboard.
- Each dropdown item is a child page + a child menu item under the “Category Bar” menu.

### Nav bar fit
- Removed horizontal scrollbar from the top nav by letting items wrap on narrower screens; on wider screens it stays in one row and spreads across the full width.

### Header profile icon
- Added a top-right “My account” (profile) icon button next to the cart, linking to WooCommerce My Account (or login/profile fallback if Woo isn’t available).

### Header search + hamburger icons
- Added a top-right search icon with a dropdown search box.
- Enabled a mobile hamburger icon to toggle the category nav.

### Header layout (reference-style)
- Removed the teal category nav bar and moved the Primary menu into the top header row beside the logo.
- Replaced the dropdown search with an always-visible header search bar + circular icon buttons (cart/profile/menu).

### Header layout refinements
- Kept the logo/menu/actions on a single top row and prevented the menu from wrapping; if it overflows, it scrolls without showing a scrollbar.

### Header Menu (Shop dropdown)
- Created a new menu “Header Menu” and assigned it to the Primary location.
- Top-level: Shop, Info, About Us, News, Recipes, Farm Videos.
- Nested the category pages under “Shop” as a dropdown.

### Header Menu: fix Shop link
- Fixed the top-level “Shop” menu item (custom link) to use a relative URL (`/shop/`) so it doesn’t break when running on `localhost:8080` or in Codespaces.

### WP Admin: Pages list pagination workaround
- Temporarily increased Pages per screen to 200 to avoid broken pagination, then set it back to 20 once admin links were fixed.

#### Next step (pending)
- Upload the new Bloom&Veg logo image to Media Library (or provide a direct URL to it), then we can set it as the Custom Logo via WP‑CLI using the attachment ID.

### Handy commands
- List users:
  - `./bin/wp user list --fields=ID,user_login,user_email,roles`
- Reset password for a user:
  - `./bin/wp user update admin --user_pass="NEW_PASSWORD"`
- Get current URLs:
  - `./bin/wp option get home`
  - `./bin/wp option get siteurl`

---

## 2026-01-19

### Home page hero + welcome text
- Set the Home page content to: “Welcome To Bloom And Veg Online Shop”.
- Added a front-page hero area that shows the Home page Featured Image when uploaded, otherwise a placeholder.
- Temporarily removed the Home page sidebar widgets (Search/Recent Posts/Recent Comments) and made the hero taller.
- Fixed the Home page having no author (post_author was 0 / “No author”), which could prevent normal manual editing in the block editor.

### Home page: make it fully editable
- Removed the theme-injected front-page hero so the homepage layout can be edited entirely with blocks.
- Added a block pattern “Home hero (editable)” and updated the Home page content to use a Cover block hero placeholder.

### Home page: Featured collections section
- Added a new editable “Featured collections” section (initially 3 cards) styled to match the reference screenshot.
- Inserted the section into the Home page so the images/labels/links can be edited in the Page editor.
- Updated the section to **5** featured cards (still fully editable in the Page editor).

### Home page: layout tweaks
- Centered the Featured Collections card layout so wrapped rows sit centered.
- Fixed footer overlap/stacking so the footer reads clearly above any card shadows.
- Updated the page layout so the footer stays at the bottom of the page (sticky-footer layout).
- Added an editable Spacer block at the end of the Home page to make the page longer before the footer.
- Moved the Welcome text into a cleaner heading block near the top of the page.

### Background
- Updated the site background to a Lincoln-green inspired green with a subtle grass-like texture (removed the earlier glowy radial gradients).
- Kept the Featured card image placeholders subtle/neutral so they don’t compete with the page background.

### Featured collections: one-row layout
- Updated the Featured Collections cards to sit in a single centered row; on smaller screens the row scrolls horizontally.
- Reduced card sizing/spacing so the row doesn’t dominate the page.

### Header overflow fix
- Prevented the header/nav/search from forcing horizontal page scrolling on narrower widths.

### My Account: Profile tab
- Added a new WooCommerce My Account endpoint: `/my-account/profile/`.
- Includes a profile form (name, email, phone, delivery address) and saves securely with a nonce.

---

## 2026-02-05

### Register page: repeated styling reverts + mobile/desktop mismatch

Problem:
- `/register/` intermittently appeared unstyled/full-width on Kinsta.
- Mobile could show unrelated cached content (e.g., “Hello world”) while desktop showed the form.

Fixes (in `bv-kinsta-tweaks`):
- Added critical register CSS injected via `wp_head` (so it doesn’t depend on enqueued CSS).
- Implemented a “delete + re-add” reset by managing `/register/` as a real WordPress Page (slug `register`) whose content is set to `[bv_register_form]`.
- Disabled the legacy rewrite rule when the real page exists.
- Added no-cache headers for register requests and a view-source stamp.

Version:
- `bv-kinsta-tweaks` → `0.4.2`

Artifacts:
- `bv-kinsta-tweaks-kinsta.zip`

Verification:
- View page source on `/register/` and search for `bv-kinsta-tweaks 0.4.2`.

### Support Chat + Support Inbox

Fixes (in `bv-support-chat`):
- Admin Support Inbox auto-opens newest thread; layout fills page and stacks on mobile.
- Hardened admin polling/send JS.
- When logged out, widget no longer auto-resumes an old chat from localStorage; guest always sees Name/Email/Order fields.

Version:
- `bv-support-chat` → `0.2.4`

Artifacts:
- `bv-support-chat-kinsta.zip`

### Footer redesign (layout only)
- Replaced the old footer menu with a two-column footer layout: “Visit Bloom & Veg” and “Delivery Zones”.
- Kept the existing site colour palette (did not copy the dark-green colour from the reference).
- Added a floating “Back to top” button.
- Added Customizer settings (Appearance → Customize → Footer) so the footer text can be edited without code.
- Tweaked styling so the footer columns feel part of the footer (not floating boxes above it).

---

## 2026-02-04

### Home hero styling polish
- Removed the white/tinted "panel" background from the editable Home hero block entirely (no more big rounded container behind the content).
- Styled the "Bloom & Veg" heading with serif display font, darker green color, subtle text shadow, and a decorative gradient underline.
- Made the hero layout clean: heading above the image with proper spacing, no excess padding.
- Hero image now has clean card styling (rounded corners + subtle shadow) against the site background.

### Kinsta setup notes
- Added a step-by-step Kinsta setup checklist (create site, migration options, DNS/SSL, WooCommerce sanity checks): `docs/kinsta-setup.md`.

### Kinsta deploy workflow
- Clarified that Kinsta WordPress Hosting doesn’t support "merging" a git branch into the server; ongoing updates are typically deployed by uploading theme/plugin changes via SFTP/SSH (or automating uploads via GitHub Actions / WP Pusher).

### Kinsta guide: quick start
- Added a “Quick start (if you’re lost)” section to the Kinsta checklist to make the next click obvious: `docs/kinsta-setup.md`.

### Kinsta setup: chosen path
- Proceeding with DIY migration: MyKinsta “Create a site” then restore using Duplicator.

### Kinsta setup: installer URL gotcha
- Hit `DNS_PROBE_FINISHED_NXDOMAIN` when visiting `https://your-temp-domain/installer.php` (placeholder / non-existent domain).
- Fix is to use the real `*.kinsta.cloud` domain from MyKinsta → Site → Info, and only visit `/installer.php` after uploading `installer.php` to `public/` via SFTP.

### Kinsta setup: next required input
- User does not yet have the Duplicator `installer.php` + archive; next step is to create a Duplicator package on the source site and download both files.

### Kinsta setup: empty environment clarification
- MyKinsta shows “WordPress is not available on this environment” under Plugins/Themes when using an Empty environment; this is normal until WordPress is installed (e.g., via Duplicator restore).

### Local WP Admin access
- Created/updated a local administrator user via WP-CLI so WP Admin login is available (password not recorded here).

### Duplicator packaging (local)
- Began installing the “Duplicator – Backups & Migration” plugin on the local site to generate `installer.php` + archive for restoring onto Kinsta.

### Duplicator package ready
- Duplicator package created and downloaded (`installer.php` + archive). Next step is SFTP upload to Kinsta `public/` then run `https://bloomveg.kinsta.cloud/installer.php`.

### Kinsta SFTP: FileZilla import gotcha
- FileZilla may fail to import Kinsta’s “FTP client config zip” (“File does not contain any importable data”). Workaround: create a Site Manager entry manually using SFTP host/port/username/password from MyKinsta.

### Kinsta restore: Duplicator 504
- Duplicator installer hit “AJAX ERROR! STATUS: 504”. Next step is to review `installer-log.txt` from the Kinsta `public/` folder and adjust package size/archive type if needed.

### Kinsta restore: DB connection timeout
- `installer-log.txt` shows “DATABASE CONNECTION EXCEPTION ERROR: Connection timed out” during `sparam_s1`. Next step is to re-check DB Host/Name/User from MyKinsta → Databases → Database access (the earlier DB name appeared to be a site title, not the DB name).

### Kinsta restore: DB access denied
- Duplicator DB validation now reaches host but fails with “Access denied for user 'bloomveg'@'localhost' (using password: YES)”. Next step is to reset/regenerate the Kinsta DB user password in MyKinsta and re-enter it in Duplicator (ensure it’s the DB password, not SFTP).

### Kinsta restore: DB connection fixed
- Reset Kinsta DB password and revalidated in Duplicator; DB connection issue resolved (no secrets recorded).

### Kinsta restore: Duplicator complete
- Reached Duplicator “Step 2 of 2: Test Site” with Files/Database/Search&Replace/Plugins status showing good.
- Noted “An index.html was found” in `public/` from the empty environment; plan is to delete `index.html` after confirming the WordPress front-end loads.

### Kinsta restore: migration finalized
- Duplicator reports “This site has been successfully migrated” and cleanup ran, removing installer + archive from `public/`.
- Remaining tasks: confirm front-end loads, re-save permalinks, verify WooCommerce checkout, and remove any placeholder `public/index.html` if still present.

### WP Admin: Permalinks menu missing
- User couldn’t see Settings → Permalinks in WP Admin; workaround is to visit `/wp-admin/options-permalink.php` directly and save.

### Permalinks saved
- Permalinks page accessed and saved; site routes confirmed working.

### WP Admin editing issue
- When editing a page, WordPress shows a “Try Again” prompt (likely block editor save/autosave failing). Next step is to capture the exact error text (often “response is not a valid JSON response”) and verify the REST API endpoint (`/wp-json/`) works on the Kinsta domain.

### Block editor: invalid block content
- Editor shows “Block contains unexpected or invalid content” with an “Attempt recovery” button on some pages. Next step is to use block recovery or switch that block to HTML/classic content so it’s editable.

### Home page editor issue
- Editing the Home page (post ID 10) shows “Block contains unexpected or invalid content”, indicating one or more blocks don’t match their saved markup (often due to missing custom blocks/plugins or HTML changes during migration).

### Kinsta: Home page broken image
- In the block editor, the Home page hero image shows as broken (image icon) and displays alt-style text (“This image has an empty alt attribute; its file name is …”), suggesting the image URL/file isn’t present on Kinsta or still references the old domain.

### Kinsta: visual parity
- Goal shifted to getting Kinsta to match the real site visually (images/sections/styles).
- Home page “Featured collections” shows black placeholder boxes, indicating the image blocks have no valid images set (or uploads/URLs didn’t migrate cleanly).

### Kinsta: MyKinsta plugin list empty
- MyKinsta → Plugins and themes shows “No plugins found…”. This is likely a Kinsta detection/MU-plugin issue rather than WordPress having zero plugins; verify via `/wp-admin/plugins.php`.

### Pivot: work on pages first
- Paused global Codespaces URL search/replace for now; focus is on editing pages and replacing key images/sections directly in the block editor.

### Home page: Delivery checker added (shortcode)
- Implemented shortcode `[bv_delivery_checker]` in the Bloom & Veg theme.

---

## 2026-02-06

### Shop Category Bar: visibility + caching/placement issues

Problem:
- Shop Category Bar reported as “not showing” under the sticky header on Kinsta.

Confirmed (live):
- Plugin runs and assets load; bar HTML is present in page source.
- Bar is initially rendered after the footer and relies on JS to move under `.site-header`.

Work done:
- Fixed/verified ZIP structure.
- Tried a server-side injection via output buffering (rolled back after causing a “plain/blank screen” symptom).
- Removed plugin behavior that hid/removed the Shop menu/catalog.
- Added repeated JS placement retries to improve reliability.

Artifacts:
- `bv-shop-category-bar-kinsta.zip`

Saved chat:
- `docs/chat-2026-02-06.md`
- Added Settings → Delivery Checker screen to paste allowed postcodes (one per line) and phone number.
- Shortcode renders a “Delivery checker” section with a postcode input + “CHECK MY POSTCODE” button and shows a simple yes/no message.

### Delivery checker: delivery prices
- Added settings for a default delivery fee and per-postcode/outward-code fees (e.g. `RM14=4.99`).
- When a postcode matches, the checker message shows “Delivery fee: £X.XX”.

### Kinsta: WP Admin login recovery
- User doesn’t know the WordPress admin login on Kinsta after restore.
- Next actions: try WP “Lost your password?” on `/wp-login.php`; if email isn’t working, reset password or create a new admin via MyKinsta phpMyAdmin / SSH WP-CLI (no passwords stored here).

### Kinsta: Duplicator installer reopened
- Visiting `/installer.php` shows Duplicator “Installer Security” asking for Archive File Name, which typically means `installer.php` exists but the archive is missing/not detected in `public/`.

### Kinsta: WP admin login not accepting password
- Attempting to log in with user `jamescody` fails (“password is incorrect”) after manual password change.
- Next step is to force-set the password hash in the correct `*_users` table via phpMyAdmin using MD5, then retry login on the Kinsta domain.

---

## 2026-02-05

### WooCommerce: My Account UI (Kinsta)
- Iterated on My Account layout/styling based on screenshots (navigation + content cards + addresses cards).
- Fixed layout issues caused by WooCommerce default floats/clearing conflicting with custom layout.
- Adjusted widths/spacing so the left navigation and content area match the desired look (less cramped; active item no longer “sticks out” past the card).

### WooCommerce: remove “info” widgets on Woo pages
- Removed/hid the default sidebar widgets (Search/Recent Posts/Recent Comments/Archives) from WooCommerce pages (Account/Cart/Checkout) so they don’t show as an “info” column.

### Kinsta deployment: plugin ZIPs
- Built Kinsta-uploadable plugins so changes can be installed via WP Admin → Plugins → Upload.
- Delivery checker plugin ZIP:
  - `bv-delivery-checker-kinsta.zip` (Delivery Checker v0.1.3)
  - Includes robust fee parsing so inputs like `RM14=£5.00` persist (stored normalized as `RM14=5.00`).
- Kinsta tweaks plugin ZIP:
  - `bv-kinsta-tweaks-kinsta.zip` (Kinsta Tweaks v0.1.7)
  - Includes My Account layout override + styling, hides sidebar widgets on Woo pages, and hides the WP admin bar on Woo pages.

### Codespaces: permissions fix
- Resolved VS Code save error (`EACCES: permission denied`) caused by `wp-content/plugins` being owned by `www-data`.
- Created/adjusted `wp-content/plugins/bv-delivery-checker/` so it’s writable by the Codespace user.

### Saved chat summary
- Saved a standalone recap: `docs/chat-2026-02-05.md`.

### Kinsta: My Account nav active item overflow fix
- Fixed the WooCommerce My Account left-nav active item (“Profile”) sticking out of the nav card by adjusting the active-state CSS to use an inset accent (no layout-shifting border) and clamping any offsets.
- Hardened the CSS overrides (higher specificity + `!important` clamps) to ensure theme/Woo styles can’t offset the active item outside the nav card.
- Aligned the Kinsta Tweaks My Account CSS to match the local styling and ensured the stylesheet loads after the theme (enqueue priority 20).
- Bumped the Kinsta Tweaks plugin version to `0.2.0` and rebuilt the uploadable ZIP: `bv-kinsta-tweaks-kinsta.zip`.

### Codespaces: plugin permissions
- Fixed VS Code save error (`EACCES: permission denied`) on `wp-content/plugins/bv-kinsta-tweaks/` by changing ownership from `www-data` to the Codespaces user.

### WooCommerce: My Account login styling
- Styled the not-logged-in My Account login form to look like a clean centered card (consistent border/radius/shadow), with improved label/input spacing.
- Updated the login screen so when WooCommerce registration is enabled, Login + Register render side-by-side on desktop (and stack on mobile).
- Enabled WooCommerce “My Account registration” automatically on plugin activation.
- Added a “Register” button next to the “Log in” button; the page defaults to a simple Login-only view and reveals the Register form when the button is clicked.
- Adjusted the Register button behavior so it reveals the Register form without scrolling/jumping down the page.
- Changed the Register control to a real button (no hash navigation) and removed auto-focus so clicking Register can’t jump the page.
- Updated the Register reveal so on desktop it displays the Register form beside Login (instead of pushing it below the fold).
- Bumped Kinsta Tweaks to `0.2.7` and rebuilt `bv-kinsta-tweaks-kinsta.zip` for upload.

### Registration: separate Register page + custom fields
- Added a dedicated `/register/` page (plugin template) and changed the Login screen “Register” button to navigate there.
- Register form fields: First name, Last name, Phone number, Email, Date of birth, Location.
- On successful registration, creates the user, stores the extra fields in user meta, logs the user in, and redirects to My Account.
- Bumped Kinsta Tweaks to `0.3.0` and rebuilt `bv-kinsta-tweaks-kinsta.zip` for upload.

### Kinsta: mobile/desktop cache mismatch fixes (register/support/checker)
Problem:
- Mobile sometimes showed unrelated cached content (e.g. “Hello world”) on `/register/`.
- Support widget behaved differently per device/session; after signing out, desktop could auto-resume an old chat (no Name/Email/Order fields).
- Delivery checker could appear unstyled/left-aligned when signed out.

Fixes:
- Hardened `/register/` no-cache handling in `bv-kinsta-tweaks` by setting `DONOTCACHE*` as early as possible (based on `REQUEST_URI`) and sending stronger no-cache headers.
- Support Chat now uses the presence of the `wordpress_logged_in_` cookie as the source of truth (so stale cached “isLoggedIn” state can’t hide the setup fields).
- Added a tiny inline “layout guard” style to the delivery checker output to keep it centered even if guest caches serve markup without the latest CSS.

Versions / artifacts:
- `bv-kinsta-tweaks` → `0.4.3` → `bv-kinsta-tweaks-kinsta.zip`
- `bv-support-chat` → `0.2.5` → `bv-support-chat-kinsta.zip`
- `bv-delivery-checker` → `0.1.7` → `bv-delivery-checker-kinsta.zip`

### News page: Facebook updates embed
- Added an auto-rendered Facebook Page timeline embed for `https://www.facebook.com/bloomandveg1925`.
- Shows on the posts index (blog/News listing) and also on a static page with slug `news` if used.
- Goal: when you post on Facebook, the website News page reflects it automatically.

### Kinsta deploy: Facebook News Embed plugin ZIP
- Created a plugin so the Facebook feed can be deployed via WP Admin upload (no theme/SFTP needed).
- Plugin ZIP: `bv-facebook-news-embed-kinsta.zip` (Facebook News Embed v0.4.1)
- v0.4.1: centers the News title and adds a configurable “Highlights / offers” card to fill the page better.

### Shop pages: category bar
- Added a new plugin to show a horizontal category bar on WooCommerce shop/category/product pages.
- Categories: Veg, Fruit, Meat, Drinks, Compost & Topsoil, Cakes, Sweets & Biscuits.
- Updated output to render more reliably (multiple Woo hooks + ensures categories exist before rendering).
- Added a fallback so it also renders if your “Shop” is a normal WP page with slug `shop`.
- Also prepends the bar to the content on the `shop` page so it works even if WooCommerce hooks aren’t fired.
- Added robust “active” markers (meta tag + HTTP header) in case HTML comments are stripped.
- Updated to show site-wide (all front-end pages) and auto-places under the header.
- Improved styling to better match the site (padding/contrast/shadows using existing theme tokens).
- Added dropdown menus with a toggle button (works on mobile + desktop).
- Moved styling/behavior into real asset files (CSS/JS) with cache-busting versions so updates apply reliably.
- Polished UI: cleaner pills + better spacing + clearer active state.
- Plugin ZIP: `bv-shop-category-bar-kinsta.zip` (Shop Category Bar v0.3.3)

### WooCommerce: product cards + Reviews centering (Kinsta)

Goal:
- Make WooCommerce product tiles look like consistent “cards” (same sizing) and make the single-product Reviews area centered and styled like the rest of the layout.

Work done (in `bv-woocommerce-cards`):
- Built/iterated a dedicated “WooCommerce Product Cards” plugin so changes can be deployed via WP Admin upload (no theme edits required).
- Product grid/cards:
  - Equal-height card layout.
  - Consistent image crop using `aspect-ratio: 4/3` + `object-fit: cover`.
  - Title clamped to 2 lines to prevent cards being different heights.
  - Full-width “Add to cart” button styled with the site’s green/teal gradient (uses existing `--bv-*` theme tokens).
- Single product Reviews/tabs:
  - Centered the tab wrapper and styled the review panel as a padded “card” (surface/border/radius/shadow).
  - Styled the Reviews submit button to match the primary button style.
  - Fix: broadened the centering CSS selectors so it still centers if the `wc-tabs-wrapper` class is missing/overridden.

Debugging / cache reality on Kinsta:
- Added a view-source marker to confirm the plugin is actually loading:
  - `<!-- bv-woocommerce-cards loaded vX.Y.Z -->`
  - `<meta name="bv-woocommerce-cards" content="X.Y.Z">`
- Confirmed Kinsta can serve different cached HTML per URL (some pages showed older plugin versions until cache was purged).
- Verification step: View page source and search for `bv-woocommerce-cards loaded` to confirm the live version.

Version:
- `bv-woocommerce-cards` → `0.2.1`

Artifacts:
- `bv-woocommerce-cards-kinsta.zip`

---

## 2026-02-07

### WooCommerce: neon “cards” + Purchase Card on single product

Goal:
- Create a premium neon/modern “card” UI for WooCommerce single products (variable + simple) and product archives, shipped as uploadable plugin ZIPs.

Work done (in `bv-woocommerce-cards`):
- Single product image: added a neon/rainbow frame around the product image.
- Variations UI: restyled the variations/qty/button area into a modern “Purchase Card”.
- Dropdown “keeps going black”: switched to WooCommerce SelectWoo/Select2 initialization (instead of trying to force-style the native `<select>`), then themed the dropdown to match.
- Purchase Card content: injected/moved Title + Price into the card; added SKU + Category; moved Rating into the card and hid the duplicated original elements.
- Simple products: added a matching Purchase Card for simple products (not just variable).

Version / artifacts:
- `bv-woocommerce-cards` built up to `0.5.18` in this repo.
- ZIP: `plugin-build/bv-woocommerce-cards-kinsta.zip`

### WooCommerce archives: grid alignment + “5 per row”

User goal:
- “Move them over so there’s 5 in a row” and align the grid under “Showing all X results”, plus fix mobile layout.

Work done (in `bv-woocommerce-cards` CSS):
- Forced `ul.products` to use CSS Grid with responsive breakpoints aimed at 5 columns on desktop.
- Added stronger theme-wrapper targeting for archives (the theme container on category templates didn’t always match `.woocommerce` expectations).
- Added defensive overrides to neutralize WooCommerce legacy float/clear rules (`float:none`, `width:auto`, ignore `.first/.last`) that can break grid alignment.
- Added mobile stacking rules so the result count + sorting + grid behave cleanly on small screens.

Status:
- Fixes are implemented and packaged in the repo, but live screenshots still showed old behavior because the live site intermittently served older plugin builds.

### Shop Category Bar: mobile label overlap fix

Problem:
- On mobile, category labels overlapped / got squashed.

Fix (in `bv-shop-category-bar`):
- Prevented bar items/links from shrinking so labels don’t overlap; bar becomes scroll-friendly on small screens.

Version / artifacts:
- `bv-shop-category-bar` → `0.7.1`
- ZIP: `plugin-build/bv-shop-category-bar-kinsta.zip`

### Kinsta deployment reality: version/caching mismatch

Observed issue:
- Live pages sometimes served different cached HTML/CSS versions (e.g. page source markers and stylesheet `?ver=` showing older versions even after uploading a new ZIP), masking the latest archive-grid fixes.

Verification + deployment reminders:
- View page source and search for `bv-woocommerce-cards loaded` and/or `<meta name="bv-woocommerce-cards" ...>`.
- Ensure the CSS URL query string `?ver=...` matches the intended plugin version.
- If versions don’t match: deactivate + delete the plugin folder in WP Admin, then upload the new ZIP again and purge Kinsta/cache/CDN.

### WooCommerce archives: enforce 5-per-row packing

Fix (in `bv-woocommerce-cards`):
- Reduced the grid tile minimum width so 5 columns can fit in common theme container widths.
- Added archive-specific selectors so the grid rules apply even when templates omit a `.woocommerce` wrapper.
- Broadened the archive container width override to cover nested `.site-main .container` structures.

Version / artifacts:
- `bv-woocommerce-cards` → `0.5.22`
- ZIP: `plugin-build/bv-woocommerce-cards-kinsta.zip`

Follow-up fix:
- Resolved an overlapping media-query bug where the 4-column rule could override the 5-column rule at common desktop widths (e.g. ~1200px), preventing 5-per-row from ever applying.

Follow-up fix (grid packing):
- Disabled WooCommerce’s legacy `ul.products::before/::after` float-clearing pseudo-elements, which were becoming grid items and creating an “empty slot” that prevented true 5-across packing.

### WooCommerce archives: add product search bar

Work done (in `bv-woocommerce-cards`):
- Added a simple product search bar to Shop / Category / Tag pages (renders above the product grid using WooCommerce’s native product search form).

Version / artifacts:
- `bv-woocommerce-cards` → `0.5.24`
- ZIP: `plugin-build/bv-woocommerce-cards-kinsta.zip`

UI polish:
- Wrapped result count + search + sorting into a single toolbar row so it aligns cleanly on desktop and stacks well on mobile.

Further polish:
- Centered the archive title, nudged it upward slightly, and added an underline accent.
- Centered the product search bar and moved the sorting dropdown to the opposite side of the toolbar.

### Later updates (themes + Christmas)
- Added a theme system to the `bv-woocommerce-cards` plugin:
  - Admin setting for theme: Normal/Christmas/Halloween/Easter.
  - Front-end admin-bar control for quick switching.
  - On devices where the admin-bar dropdown is hard to open, the label "Card theme: …" now cycles to the next theme on click.
- Christmas theme enhancements:
  - Default red palette for frames + neon.
  - Falling snow overlay animation.
  - More Christmassy background layers (tree silhouettes) and a light-red header background with a subtle presents pattern.
  - Readability fixes so the homepage hero title ("Bloom & Veg") stays visible on the red background.

Version / artifacts:
- `bv-woocommerce-cards` → `0.5.36`
- ZIP: `plugin-build/bv-woocommerce-cards-kinsta.zip`

### Later updates (Christmas polish)
- Christmas: shop category bar themed to match Christmas mode (red + presents) via the cards plugin CSS.
- Christmas: archive/category title readability improved with a stronger dark backdrop behind titles.

Version / artifacts:
- `bv-woocommerce-cards` → `0.5.41`
- ZIP: `plugin-build/bv-woocommerce-cards-kinsta.zip`

### Maintenance mode
- Added a Maintenance mode toggle to `bv-kinsta-tweaks`.
  - Admin-only admin-bar button: `Maintenance: OFF` / `Maintenance: ON`.
  - When ON, visitors see a full-page green maintenance screen with logo and message.
  - Admins can still browse normally.
  - Sends HTTP `503` + no-cache headers.

Version / artifacts:
- `bv-kinsta-tweaks` → `0.4.6`
- ZIP: `plugin-build/bv-kinsta-tweaks-kinsta.zip`

### Halloween theme (initial pass)
- Added a Halloween background + animations (ghost drift overlay and fire/pumpkin flicker overlay) and a small friendly greeting box.

Version / artifacts:
- `bv-woocommerce-cards` → `0.5.41`
- ZIP: `plugin-build/bv-woocommerce-cards-kinsta.zip`

### Pending / next changes requested
- Halloween: change to *one small ghost* moving around the page (instead of multiple repeating ghosts).
- Halloween: make “fire pumpkins” part of the page background (not a bottom overlay).

### Shop Category Bar: make it fully editable in WP Admin

Goal:
- Make the category bar easy to edit without changing code.

Work done (in `bv-shop-category-bar`):
- Switched the bar to auto-build from WooCommerce **Product categories** (top-level categories become bar items; child categories become dropdown items).
- Updated the front-end “Edit bar” button to link directly to **Products → Categories**.
- Removed the manual “bar items” textarea/settings and the legacy behavior that auto-created categories.

Version / artifacts:
- `bv-shop-category-bar` → `0.8.1`
- ZIP: `plugin-build/bv-shop-category-bar-kinsta.zip`

Follow-up:
- Fixed the “Sorry, you are not allowed to access this page.” issue by showing the “Edit bar” link only for users who can manage WooCommerce product categories (`manage_product_terms` / `manage_woocommerce`).

Update:
- Added a **Manual mode** so the bar can be edited even if the user can’t edit Product categories.
  - New settings page: WooCommerce → Shop Category Bar (falls back to Settings → Shop Category Bar if WooCommerce menu isn’t available).
  - Mode toggle: Auto (from categories) vs Manual (paste/edit items in a textarea).
  - Front-end “Edit bar” button now opens the settings page when Manual mode is selected.

Version / artifacts:
- `bv-shop-category-bar` → `0.8.2`
- ZIP: `plugin-build/bv-shop-category-bar-kinsta.zip`

Revert:
- Switched the Shop Category Bar back to the older **manual list** behavior (no auto-build from categories; no mode toggle).
- The bar items are edited via WooCommerce → Shop Category Bar (textarea).

Version / artifacts:
- `bv-shop-category-bar` → `0.8.3`
- ZIP: `plugin-build/bv-shop-category-bar-kinsta.zip`

Revert (one more):
- Reverted the deploy artifact to an older known-good Shop Category Bar ZIP.

Version / artifacts:
- ZIP: `plugin-build/bv-shop-category-bar-kinsta.zip` (Shop Category Bar v0.5.4)

Revert to requested version:
- Rebuilt the deploy ZIP so the plugin reports version `0.7.3`.
- Kept a copy of the v0.5.4 artifact as: `plugin-build/bv-shop-category-bar-kinsta-0.5.4.zip`.

Version / artifacts:
- ZIP: `plugin-build/bv-shop-category-bar-kinsta.zip` (Shop Category Bar v0.7.3)
- Investigate: “Facebook isn’t working properly” (likely `bv-facebook-news-embed`) — needs reproduction details/screenshot.
