# Deploying to Vercel

Static site built by Node (no PHP in CI) + a Node serverless function for the
contact form. Free SSL, and the apex domain works via a DNS A record — no
nameserver change required.

## How it fits together

| Piece | File(s) | Runs |
|---|---|---|
| Page chrome (header/footer/meta) | `build.mjs` | build time |
| Page bodies | `site/pages/*.html` | build time |
| Static output | `public/` (generated) | served at the edge |
| Contact form backend | `api/contact.js` | serverless, on POST `/api/contact` |
| Routing / caching | `vercel.json` (cleanUrls, redirects, headers) | edge |

**Editing content:** change the body in `site/pages/<page>.html`, or the
shared chrome / page metadata in `build.mjs`, then rebuild (`node build.mjs`).
The public Turnstile **site key** is hard-coded in `site/pages/contact.html`.

## One-time setup

1. Push to GitHub (already done: `princeomonu/tegrach`).
2. **vercel.com → Add New → Project → Import** the repo.
3. Vercel reads `vercel.json`, so settings are automatic:
   - Build command: `node build.mjs`
   - Output directory: `public`
   - Functions: `api/` (auto-detected)
4. **Environment Variables** (Settings → Environment Variables) for **Production** and **Preview**:
   | Name | Value |
   |---|---|
   | `RESEND_API_KEY` | your `re_…` key |
   | `CONTACT_TO` | `contact@tegrach-nigeria.com` (or `developer@…` for Preview) |
   | `CONTACT_FROM` | `Tegrach Nigeria <website@tegrach-nigeria.com>` |
   | `TURNSTILE_SECRET_KEY` | Turnstile secret (omit to disable verification) |
   | `SITE_URL` (optional) | `https://tegrach-nigeria.com` |
   These are read at **runtime** by the function — no rebuild gymnastics, and
   secrets stay secret. Redeploy after first adding them.
5. **Custom domain:** Project → Settings → Domains → add `tegrach-nigeria.com` and `www`.
   Vercel gives the DNS records to add at **Network Solutions** (no nameserver change):
   - apex `tegrach-nigeria.com` → **A** record `76.76.21.21`
   - `www` → **CNAME** `cname.vercel-dns.com`
   SSL is issued automatically. Leave your **MX / SPF / DKIM / DMARC** records untouched.

## Local development

```bash
cp .env.example .env.local      # fill in real values (gitignored)
npx vercel dev                  # builds + runs the function locally
```
`vercel dev` runs `node build.mjs` and serves `public/` plus `api/contact.js`,
so the whole flow (form → Resend → Turnstile) works locally.

To just preview the static pages without the function:
```bash
node build.mjs && npx serve public
```

## Notes
- The Cloudflare files (`functions/`, `build.php`, `build.sh`, `deploy.sh`,
  `_redirects`/`_headers`) and the PHP page files are **not used by Vercel** —
  kept only as a fallback. Vercel builds from `site/pages/` + `build.mjs`.
- Make sure your Turnstile widget lists the hostnames `tegrach-nigeria.com`,
  `www.tegrach-nigeria.com`, your `*.vercel.app` preview domain, and `localhost`.
