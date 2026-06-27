# Deploying to a PHP / Apache webhost (fallback, e.g. cPanel)

Static-first: every page is plain pre-built HTML. The **only** dynamic piece is
the contact form handler, a single `contact.php`. This means a cheap shared host
with PHP works the same as Vercel — upload the built files plus three PHP files.

## How it fits together

| Piece | File(s) | Runs |
|---|---|---|
| Pages + assets | `src/` → `build.mjs` → `dist/` | build time |
| Clean URLs / caching | `dist/.htaccess` (written by `build.mjs`) | Apache |
| Contact form backend | `platform/php/contact.php` (+ `mailer.php`, `config.php`) | on POST `/api/contact` |

The generated `dist/.htaccess` rewrites `/api/contact` → `contact.php`, so the
endpoint path matches Vercel and Cloudflare and the shared `src/app.js` needs no
changes.

## Steps

1. Build locally:
   ```bash
   node build.mjs
   ```
2. Configure the mailer (one time):
   ```bash
   cp platform/php/config.example.php platform/php/config.php
   # edit config.php — set APP_ENV + RESEND_API_KEY (gitignored, never commit it)
   ```
3. Upload to the web root (e.g. `public_html/`):
   - **everything inside `dist/`** (including the hidden `.htaccess`)
   - `platform/php/contact.php`
   - `platform/php/mailer.php`
   - `platform/php/config.php`
   So the web root ends up with `index.html`, `contact.html`, …, `assets/`,
   `.htaccess`, `contact.php`, `mailer.php`, `config.php`.
4. Test the form. A successful POST to `/api/contact` returns `{"ok":true}`;
   errors return `{"ok":false,"error":"…"}` — same contract as the other hosts.

## Notes
- Requires PHP with cURL (standard on cPanel) for the Resend API call.
- Email deliverability relies on your domain's **SPF / DKIM / DMARC** being set
  up in Resend — the same as the other deployment targets.
- Turnstile verification is not wired into the PHP handler; the honeypot + server
  validation still apply. Add it to `contact.php` if you need it on this host.
