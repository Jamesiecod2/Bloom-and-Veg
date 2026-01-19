# Migrating bloomandveg.co.uk (WordPress + WooCommerce)

You said you have **WP Admin + hosting/cPanel** access, so you can do a proper full-site migration.

## Recommended method: Duplicator (full clone)

This migrates **database + wp-content + WordPress core files** in one go.

### On the LIVE site (bloomandveg.co.uk)

1. Put the site into maintenance mode (optional but recommended if you have orders coming in).
2. Install plugin: **Duplicator**
   - WP Admin → Plugins → Add New → search “Duplicator” → Install → Activate
3. Go to Duplicator → Packages → Create New
4. Build the package.
5. Download both files:
   - `installer.php`
   - the archive (`*.zip` or `*.daf` depending on version)

### Restore into your LOCAL environment (this repo)

1. Start the clone stack:

   - `chmod +x bin/clone-up bin/clone-down`
   - `./bin/clone-up`

2. Copy the two Duplicator files into:

   - `clone/wordpress/installer.php`
   - `clone/wordpress/<archive file>`

3. Run the installer:

   - Open `http://localhost:8080/installer.php`

4. DB settings in the installer (because WordPress runs inside Docker):

   - Host: `db`
   - Database: `wordpress`
   - User: `wordpress`
   - Password: `wordpress`

5. When asked for site URL, use:

   - `http://localhost:8080`

6. Finish, then log in:

   - `http://localhost:8080/wp-admin`

### Restore onto a NEW host (production)

1. Upload `installer.php` + the archive to your new hosting web root.
2. Create a new empty database + user in cPanel.
3. Browse to `https://your-new-domain.com/installer.php`
4. Enter the new DB credentials.
5. Let Duplicator run the URL replacement.
6. Re-save permalinks: WP Admin → Settings → Permalinks → Save.

## WooCommerce notes (important)

- If the store is live, take the backup at a quiet time to avoid missing orders.
- After migration, verify:
  - Payments (Stripe/PayPal keys)
  - Webhooks (Stripe especially)
  - Shipping zones/rules
  - Email sending
  - Cron (wp-cron)

## If Duplicator fails

Common reasons:
- PHP upload/timeout limits on hosting
- Very large `wp-content/uploads`

Fallback options:
- UpdraftPlus (backup/restore)
- All-in-One WP Migration (easy, but free version has upload limits)
