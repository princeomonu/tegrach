#!/usr/bin/env bash
# Build the static site into dist/ for Cloudflare Pages.
# Uses native php if present, otherwise Docker.
set -euo pipefail
cd "$(dirname "$0")"

if command -v php >/dev/null 2>&1; then
  php build.php
elif command -v docker >/dev/null 2>&1; then
  docker run --rm -v "$PWD":/app -w /app "${PHP_IMAGE:-php:8.3-cli}" php build.php
else
  echo "ERROR: need php or docker to build." >&2
  exit 1
fi
