# Tegrach Nigeria Limited — website

Marketing site for Tegrach Nigeria Limited. **Static-first and portable:** one
content source builds a self-contained `dist/` that deploys to Vercel (primary),
Cloudflare Pages, or any PHP/Apache webhost. The only per-host piece is the
contact-form backend, which exists once per platform behind the same
`/api/contact` endpoint.

## Layout

```
build.mjs            Build: wraps src/pages/*.html in shared chrome -> dist/
vercel.json          Vercel routing/caching + build config
package.json         npm scripts (build, dev, preview)

src/                 ── single source of truth ──
  pages/             Page bodies (index, about, services, projects, contact)
  style.css          Site styles
  app.js             Site scripts (incl. contact-form submit)
  assets/            Images, fonts, favicons, OG image

api/
  contact.js         Vercel serverless function  (POST /api/contact)

platform/            Per-host adapters for the portable fallbacks
  cloudflare/
    functions/api/contact.js   Cloudflare Pages Function (POST /api/contact)
    .dev.vars.example
  php/
    contact.php      PHP handler (POST /api/contact), returns JSON
    mailer.php       Resend email sender
    config.example.php

docs/                deploy-vercel.md · deploy-cloudflare.md · deploy-php.md
design/              Brand PDFs, mockups, reference docs (gitignored, local only)
dist/                Build output (generated, gitignored)
```

## Build & preview

```bash
npm run build        # node build.mjs  -> dist/
npm run preview      # serve the built dist/ locally
npm run dev          # build, then serve dist/
```

`build.mjs` also writes the per-host sidecar files into `dist/`
(`_redirects` + `_headers` for Cloudflare, `.htaccess` for Apache/PHP). Vercel
ignores them; the other hosts use them. So the same `dist/` works everywhere.

## Editing content

- Page body: edit `src/pages/<page>.html`.
- Shared header/footer/meta: edit `build.mjs`.
- Styles/scripts: edit `src/style.css` / `src/app.js`.

Then rebuild. The public Turnstile **site key** is hard-coded in
`src/pages/contact.html` (it is meant to be public).

## Contact form

The form posts JSON to **`/api/contact`** and expects `{"ok":true}` or
`{"ok":false,"error":"…"}`. Each host implements that endpoint:

| Host | Handler | Secrets |
|---|---|---|
| Vercel | `api/contact.js` | dashboard env vars (`.env.example`) |
| Cloudflare Pages | `platform/cloudflare/functions/api/contact.js` | dashboard / `.dev.vars` |
| PHP/Apache | `platform/php/contact.php` | `platform/php/config.php` |

## Deploying

See **`docs/`** — one guide per host. Vercel is the primary target.
