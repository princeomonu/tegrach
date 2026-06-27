# AGENTS.md

Guidance for AI agents working in this repository.

## What this is

The marketing website for **Tegrach Nigeria Limited**. It is a **static-first,
portable** site: one content source builds a self-contained `dist/` that deploys
to **Vercel (primary)**, **Cloudflare Pages**, or any **PHP/Apache webhost**. The
only per-host piece is the contact-form backend, which exists once per platform
behind the same `/api/contact` endpoint.

There is no framework, no bundler, no `node_modules` — `build.mjs` is plain Node
using only the standard library.

## Layout

```
build.mjs            Build: wraps src/pages/*.html in shared chrome -> dist/
vercel.json          Vercel routing/caching + build config (outputDirectory: dist)
package.json         npm scripts (build, dev, preview); ESM ("type":"module")

src/                 ── SINGLE SOURCE OF TRUTH ──
  pages/*.html       Page BODIES only (index, about, services, projects, contact)
  style.css          Site styles
  app.js             Site scripts (incl. contact-form submit)
  assets/            Images, fonts, favicons, OG image

api/contact.js       Vercel serverless function   (POST /api/contact)
platform/
  cloudflare/functions/api/contact.js   Cloudflare Pages Function (POST /api/contact)
  cloudflare/.dev.vars.example
  php/contact.php     PHP handler (POST /api/contact), returns JSON
  php/mailer.php      Resend email sender
  php/config.example.php

docs/                deploy-vercel.md · deploy-cloudflare.md · deploy-php.md
design/              Brand PDFs, mockups, reference docs — gitignored, local only
dist/                Build output — generated, gitignored
```

## Build & run

```bash
npm run build        # node build.mjs  -> dist/
npm run preview      # serve the built dist/ locally
npm run dev          # build, then serve dist/
```

`build.mjs` cleans and regenerates `dist/`, then also writes per-host sidecar
files into it: `_redirects` + `_headers` (Cloudflare) and `.htaccess` (Apache/PHP).
Vercel ignores those; the other hosts use them. The same `dist/` works everywhere.

There are no tests and no linter configured. Verify changes by building and
inspecting `dist/` output.

## Editing rules (important)

- **Page body content** → edit `src/pages/<page>.html`. These are *fragments*
  (no `<html>`/`<head>`/`<body>`), wrapped at build time.
- **Shared header, footer, `<head>`/meta, JSON-LD, nav, per-page titles** →
  edit `build.mjs`. The chrome lives ONLY here — do not reintroduce separate
  header/footer partials.
- **Styles / scripts** → `src/style.css` / `src/app.js`.
- To add a page: add `src/pages/<slug>.html` AND an entry in the `PAGES` array
  in `build.mjs`.
- Asset URLs are root-absolute (`/assets/...`, `/style.css`, `/app.js`) and
  carry `?v=N` cache-busting query strings — bump `N` when changing a cached file.
- Do **not** edit anything in `dist/` — it is regenerated on every build.

## Contact form contract

The form (in `src/pages/contact.html`, handled by `src/app.js`) POSTs JSON to
**`/api/contact`** and expects `{"ok":true}` or `{"ok":false,"error":"…"}`.

Keep all three handlers behaviourally in sync — honeypot (`website` field),
require name + valid email, send via Resend, return that JSON shape:

| Host | Handler |
|---|---|
| Vercel | `api/contact.js` |
| Cloudflare Pages | `platform/cloudflare/functions/api/contact.js` |
| PHP/Apache | `platform/php/contact.php` (+ `mailer.php`) |

The public Turnstile **site key** is intentionally hard-coded in
`src/pages/contact.html` (it is meant to be public). Turnstile **secrets** and
Resend credentials are runtime env vars / `platform/php/config.php` — never commit
them.

## Secrets & gitignore

Never commit: `platform/php/config.php`, `.env*` (except `.env.example`),
`.dev.vars*` (except the example), or anything under `design/`. These are already
in `.gitignore`.

## Gotchas

- **Node-only build.** No PHP is needed to build or deploy to Vercel/Cloudflare;
  PHP only runs the contact handler on a PHP host.
- **`dist/` can become root-owned** if a build is ever run as root (e.g. in a
  container), which then makes later local builds fail with `EACCES` on cleanup.
  Fix with `sudo rm -rf dist`.
- Endpoint path is `/api/contact` on **every** host — keep it that way so
  `src/app.js` needs no per-host changes.

## Conventions

- Match the existing code style: 2-space indent, plain ES modules, no added
  dependencies unless clearly justified.
- Keep commits focused; do not commit generated `dist/` or secret files.
- Update the relevant `docs/deploy-*.md` when you change build/deploy behaviour.
