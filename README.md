# Bloom & Veg – WordPress dev setup

This repo is a **local development scaffold** for a WordPress + WooCommerce site.

## What this gives you

- WordPress running locally via Docker
- MariaDB database
- Optional tools: WP-CLI + phpMyAdmin
- A starter **child theme** you can use with the premium **Shopkeeper** theme (recommended because your live site uses it)

## Prerequisites

- Docker + Docker Compose (v2)

## Start the site

1. Copy env file:

   - `cp .env.example .env`

2. Start containers:

   - `docker compose up -d`

3. Open:

   - WordPress: http://localhost:8080
   - phpMyAdmin (optional): http://localhost:8081 (run with tools profile below)

## Tools (optional)

Start tool containers when you need them:

- `docker compose --profile tools up -d`

WP-CLI helper:

- `chmod +x bin/wp`
- `./bin/wp core version`

## One-command install (recommended)

This will install WordPress + WooCommerce and create starter pages/categories:

- `chmod +x bin/setup bin/wp`
- `./bin/setup`

## Create pages + navigation (recommended)

This creates the core pages (Home/About/Delivery/FAQs/Contact) and a starter menu with a Shop dropdown.

- `chmod +x bin/site-structure`
- `./bin/site-structure`

## Using Shopkeeper + the child theme

Your live site indicates it uses the **Shopkeeper** theme.

Because Shopkeeper is a paid theme, it is **not included** here.

To use it locally:

1. Download the Shopkeeper theme zip from the author.
2. Install it in WP Admin → Appearance → Themes → Add New → Upload Theme.
3. Then activate the child theme: **Bloom & Veg Child**.

If Shopkeeper isn’t installed, the child theme will not activate (that’s expected).

## Next steps (tell me which you want)

- Rebuild the same navigation/categories from the live site (Fruit & Veg, Dairy, Bakery, Food Cupboard, etc.)
- Set up WooCommerce shipping zones + delivery/pickup rules
- Configure payments (Stripe/PayPal)
- Migrate products/content from the existing site

## Clone the live site (recommended)

If you have WP Admin + hosting access, you can do a full clone (files + database) using Duplicator.

- Guide: [docs/migration.md](docs/migration.md)
- Start local clone restore stack: `./bin/clone-up`
