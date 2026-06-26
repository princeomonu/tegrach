#!/usr/bin/env bash
# Local dev server for the Tegrach PHP site.
# Uses native `php` if installed, otherwise falls back to a PHP Docker image.
# Clean URLs are handled by router.php (mirrors .htaccess, which is Apache-only).
set -euo pipefail

cd "$(dirname "$0")"

HOST="${HOST:-localhost}"
PORT="${PORT:-8000}"

# First run: make sure a local config exists (gitignored, holds the Resend key).
if [[ ! -f includes/config.php ]]; then
  echo "→ includes/config.php not found — creating it from config.example.php"
  echo "  Edit it and set APP_ENV + RESEND_API_KEY before submitting the contact form."
  cp includes/config.example.php includes/config.php
fi

URL="http://${HOST}:${PORT}"

if command -v php >/dev/null 2>&1; then
  echo "→ Serving with native PHP at ${URL}  (Ctrl+C to stop)"
  exec php -S "${HOST}:${PORT}" router.php
elif command -v docker >/dev/null 2>&1; then
  IMAGE="${PHP_IMAGE:-php:8.3-cli}"
  echo "→ php not found; serving with Docker (${IMAGE}) at ${URL}  (Ctrl+C to stop)"
  exec docker run --rm -it \
    -v "$PWD":/app -w /app \
    -p "${PORT}:8000" \
    "${IMAGE}" php -S 0.0.0.0:8000 router.php
else
  echo "ERROR: neither 'php' nor 'docker' is installed." >&2
  echo "Install PHP (e.g. 'sudo apt-get install php-cli') or Docker, then re-run." >&2
  exit 1
fi
