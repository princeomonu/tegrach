#!/usr/bin/env bash
# Build the static site and deploy it (plus functions/) to Cloudflare Pages.
#
#   ./deploy.sh              # build + deploy to the "tegrach" Pages project
#   ./deploy.sh --branch x   # extra args are passed through to wrangler
#
# Requires: php (or Docker) for the build, and Node/npx for wrangler.
# First run will prompt you to log in to Cloudflare in the browser.
set -euo pipefail
cd "$(dirname "$0")"

PROJECT="${PAGES_PROJECT:-tegrach}"

echo "==> Building static site into dist/ ..."
./build.sh

echo "==> Deploying to Cloudflare Pages project: $PROJECT ..."
npx wrangler pages deploy dist --project-name "$PROJECT" "$@"

echo "==> Done."
