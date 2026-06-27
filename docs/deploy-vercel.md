# Deploying to Vercel (primary target)

Static site built by Node (no PHP in CI) plus a Node serverless function for the
contact form. Free SSL; the apex domain works via a DNS A record (no nameserver
change required).

## How it fits together

| Piece | File(s) | Runs |
|---|---|---|
| Page chrome (header/footer/meta) | `build.mjs` | build time |
| Page bodies | `src/pages/*.html` | build time |
| Styles / scripts / images | `src/style.css`, `src/app.js`, `src/assets/` | copied to `dist/` |
| Static output | `dist/` (generated, gitignored) | served at the edge |
| Contact form backend | `api/contact.js` | serverless, on POST `/api/contact` |
| Routing / caching | `vercel.json` (cleanUrls, redirects, headers) | edge |

**Editing content:** change the body in `src/pages/<page>.html`, or the shared
chrome / page metadata in `build.mjs`, then rebuild (`node build.mjs`). The public
Turnstile **site key** is hard-coded in `src/pages/contact.html`.

## One-time setup

1. Push to GitHub (`princeomonu/tegrach`).
2. **vercel.com → Add New → Project → Import** the repo.
3. Vercel reads `vercel.json`, so settings are automatic:
   - Build command: `node build.mjs`
   - Output directory: `dist`
   - Functions: `api/` (auto-detected)
4. **Environment Variables** (Settings → Environment Variables) for **Production** and **Preview**:
   | Name | Value |
   |---|---|
   | `RESEND_API_KEY` | your `re_…` key |
   | `CONTACT_TO` | `contact@tegrach-nigeria.com` (or `developer@…` for Preview) |
   | `CONTACT_FROM` | `Tegrach Nigeria <website@tegrach-nigeria.com>` |
   | `TURNSTILE_SECRET_KEY` | Turnstile secret (omit to disable verification) |
   | `SITE_URL` (optional) | `https://tegrach-nigeria.com` |
   Read at **runtime** by the function. Redeploy after first adding them.
5. **Custom domain:** Project → Settings → Domains → add `tegrach-nigeria.com` and `www`.
   - apex `tegrach-nigeria.com` → **A** record `76.76.21.21`
   - `www` → **CNAME** `cname.vercel-dns.com`
   SSL is automatic. Leave **MX / SPF / DKIM / DMARC** untouched.

## Local development

```bash
cp .env.example .env.local      # fill in real values (gitignored)
npx vercel dev                  # builds + runs the function locally
```

To preview the static pages without the function:
```bash
npm run dev                     # node build.mjs && serve dist
```

## Notes
- The per-host sidecar files `build.mjs` also writes into `dist/`
  (`_redirects`, `_headers`, `.htaccess`) are ignored by Vercel — they only
  matter for the Cloudflare and PHP fallbacks. See the other docs in this folder.
- Make sure your Turnstile widget lists `tegrach-nigeria.com`,
  `www.tegrach-nigeria.com`, your `*.vercel.app` preview domain, and `localhost`.
