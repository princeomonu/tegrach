# Deploying to Cloudflare Pages (fallback)

Same static `dist/` as every other host (built by `node build.mjs`), plus the
contact form as a Cloudflare Pages **Function**. Hosting is free, SSL automatic.

`build.mjs` writes `dist/_redirects` and `dist/_headers` automatically, so the
redirects and long-cache headers are already in place.

## How it fits together

| Piece | File(s) | Runs |
|---|---|---|
| Pages + assets | `src/` → `build.mjs` → `dist/` | build time |
| Contact form backend | `platform/cloudflare/functions/api/contact.js` | on POST `/api/contact` |
| Redirects / caching | `dist/_redirects`, `dist/_headers` (written by `build.mjs`) | edge |

Cloudflare Pages serves Functions from a `functions/` directory at the root of
the uploaded output, so the build step copies the function into `dist/functions/`.
The endpoint path is `/api/contact` — identical to Vercel — because the function
lives at `functions/api/contact.js`.

## One-time setup (Git integration)

1. Push this repo to GitHub.
2. Cloudflare dashboard → **Workers & Pages → Create → Pages → Connect to Git** → pick the repo.
3. Build settings:
   - **Framework preset:** None
   - **Build command:** `node build.mjs && cp -r platform/cloudflare/functions dist/functions`
   - **Build output directory:** `dist`
4. **Environment variables** (Settings → Environment variables), per **Production** / **Preview**:
   | Name | Production | Preview |
   |---|---|---|
   | `RESEND_API_KEY` | your `re_…` key | same (or a test key) |
   | `CONTACT_TO` | `contact@tegrach-nigeria.com` | `developer@tegrach-nigeria.com` |
   | `CONTACT_FROM` | `Tegrach Nigeria <website@tegrach-nigeria.com>` | same |
   | `SITE_URL` (optional) | `https://tegrach-nigeria.com` | (unset) |
   | `TURNSTILE_SECRET_KEY` (optional) | widget **secret** key | same |
   Mark `RESEND_API_KEY` and `TURNSTILE_SECRET_KEY` as **encrypted/secret**.
5. **Custom domain:** Pages → Custom domains → add `tegrach-nigeria.com` (and `www`).
   Keep your existing **MX / SPF / DKIM / DMARC** records so email keeps working.

## Deploy from the CLI instead

```bash
node build.mjs
cp -r platform/cloudflare/functions dist/functions
npx wrangler pages deploy dist --project-name tegrach
```

## Local development

```bash
node build.mjs
cp -r platform/cloudflare/functions dist/functions
cp platform/cloudflare/.dev.vars.example dist/.dev.vars   # then fill in real values (gitignored)
npx wrangler pages dev dist                               # serves dist/ + the function
```
