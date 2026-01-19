# Bloom-and-Veg — Session Log

Purpose: keep a running, repo-local record of what we changed and why.

Note: This file is used as the “saved chat” summary and is updated as we make changes.

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
- The site background uses soft green/teal radial gradients (theme CSS on `body`) and the Featured card image areas use a subtle gradient placeholder.
- If you want a plainer background for handover, we can switch to a solid or much subtler gradient.

### Header overflow fix
- Prevented the header/nav/search from forcing horizontal page scrolling on narrower widths.

### My Account: Profile tab
- Added a new WooCommerce My Account endpoint: `/my-account/profile/`.
- Includes a profile form (name, email, phone, delivery address) and saves securely with a nonce.

### Footer redesign (layout only)
- Replaced the old footer menu with a two-column footer layout: “Visit Bloom & Veg” and “Delivery Zones”.
- Kept the existing site colour palette (did not copy the dark-green colour from the reference).
- Added a floating “Back to top” button.
- Added Customizer settings (Appearance → Customize → Footer) so the footer text can be edited without code.
- Tweaked styling so the footer columns feel part of the footer (not floating boxes above it).
