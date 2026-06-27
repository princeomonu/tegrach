# Deploying to Cloudflare Pages

The site is authored in PHP (shared `includes/`), built to static HTML in `dist/`,
and the contact form runs as a Cloudflare Pages **Function** (`functions/contact.js`)
that sends mail via Resend. Hosting is free and SSL is automatic.

## How it fits together

| Piece | File(s) | Runs |
|---|---|---|
| Pages (source of truth) | `index.php`, `about.php`, … + `includes/header.php`, `includes/footer.php` | at build time |
| Static build | `build.php` → `dist/` | build |
| Contact form backend | `functions/contact.js` | on every POST `/contact` |
| Redirects / caching | `dist/_redirects`, `dist/_headers` (written by `build.php`) | edge |

`functions/` lives at the repo root (Cloudflare picks it up automatically) and is
**not** part of `dist/`.

## One-time setup

1. Push this repo to GitHub/GitLab.
2. Cloudflare dashboard → **Workers & Pages → Create → Pages → Connect to Git** → pick the repo.
3. Build settings:
   - **Framework preset:** None
   - **Build command:** `php build.php`
   - **Build output directory:** `dist`
4. **Environment variables** (Settings → Environment variables). Set for **Production** and **Preview** separately:
   | Name | Production | Preview |
   |---|---|---|
   | `RESEND_API_KEY` | your `re_…` key | same (or a test key) |
   | `CONTACT_TO` | `contact@tegrach-nigeria.com` | `developer@tegrach-nigeria.com` |
   | `CONTACT_FROM` | `Tegrach Nigeria <website@tegrach-nigeria.com>` | same |
   | `SITE_URL` (optional) | `https://tegrach-nigeria.com` | (leave unset) |
   | `TURNSTILE_SITE_KEY` (optional) | your widget **site** key | same |
   | `TURNSTILE_SECRET_KEY` (optional) | your widget **secret** key | same |
   Mark `RESEND_API_KEY` and `TURNSTILE_SECRET_KEY` as **encrypted/secret**.

   **Turnstile:** create a widget at Cloudflare → **Turnstile → Add widget** (add the
   hostnames `tegrach-nigeria.com`, `www.tegrach-nigeria.com`, and your `*.pages.dev`).
   It gives a **site key** (public, build-time) and **secret key** (runtime). The form
   only shows/enforces the challenge when these are set, so you can deploy without it
   and add it later. `TURNSTILE_SITE_KEY` is read at **build time**, so re-deploy after
   changing it.
5. **Custom domain:** Pages → Custom domains → add `tegrach-nigeria.com` (and `www`).
   Cloudflare issues SSL automatically. If the domain's DNS isn't on Cloudflare yet,
   it will walk you through pointing it. **Keep your existing MX (`oxcs`) and the
   SPF / `resend._domainkey` DKIM / DMARC records** so email keeps working.

> If Cloudflare's build image can't run PHP, build locally instead (`./build.sh`)
> and deploy the output with `npx wrangler pages deploy dist` — the Function in
> `functions/` is included automatically.

## Local development

```bash
./build.sh                       # render dist/ (uses php or Docker)
npx wrangler pages dev dist      # serves dist/ + functions/ with the form working
```
Provide the env vars locally via `npx wrangler pages dev dist --binding RESEND_API_KEY=... CONTACT_TO=... CONTACT_FROM=...`
or a `.dev.vars` file (gitignored).

## Editing content

Edit the `.php` pages / `includes/`, then rebuild (`./build.sh`) — Cloudflare runs
the build automatically on each push. The legacy `.htaccess`, `router.php`,
`start.sh` and `includes/mailer.php` are kept for the PHP-hosting path and are not
used by Cloudflare.
