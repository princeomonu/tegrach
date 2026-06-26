<?php
/*
 * Static site builder for Cloudflare Pages.
 * Renders each PHP page (using the shared includes) to a static .html file in
 * dist/, then copies assets and writes _redirects / _headers.
 *
 * Cloudflare Pages → Build command:  php build.php
 *                    Build output dir: dist
 * Run locally with:  php build.php   (or ./build.sh via Docker)
 *
 * The contact form is handled at runtime by functions/contact.js, not here.
 */

$root = __DIR__;
$dist = $root . '/dist';

// Pages rendered to static HTML. 'index' becomes dist/index.html (served at /).
$pages = ['index', 'about', 'services', 'projects', 'contact'];

// Static files/dirs copied verbatim into dist/.
$assets = ['assets', 'style.css', 'app.js'];

function rrmdir(string $dir): void {
    if (!is_dir($dir)) return;
    foreach (scandir($dir) as $f) {
        if ($f === '.' || $f === '..') continue;
        $p = "$dir/$f";
        is_dir($p) ? rrmdir($p) : unlink($p);
    }
    rmdir($dir);
}

function rcopy(string $src, string $dst): void {
    if (is_dir($src)) {
        @mkdir($dst, 0755, true);
        foreach (scandir($src) as $f) {
            if ($f === '.' || $f === '..') continue;
            rcopy("$src/$f", "$dst/$f");
        }
    } else {
        copy($src, $dst);
    }
}

// Fresh output dir.
rrmdir($dist);
mkdir($dist, 0755, true);

// Render each page in its own PHP process so shared functions/vars don't bleed
// across pages (header.php is included once per process).
$php = PHP_BINARY ?: 'php';
foreach ($pages as $page) {
    $file = "$root/$page.php";
    $html = shell_exec(escapeshellarg($php) . ' ' . escapeshellarg($file));
    if ($html === null || $html === '') {
        fwrite(STDERR, "Build failed: empty output for $page.php\n");
        exit(1);
    }
    file_put_contents("$dist/$page.html", $html);
    echo "rendered $page.html\n";
}

// Copy static assets.
foreach ($assets as $a) {
    if (file_exists("$root/$a")) {
        rcopy("$root/$a", "$dist/$a");
        echo "copied $a\n";
    }
}

// Legacy redirects (Cloudflare Pages already 301s *.html -> clean URL).
file_put_contents("$dist/_redirects", implode("\n", [
    '/index.html        /          301',
    '/contact_us        /contact   301',
    '/contact_us.php    /contact   301',
    '',
]));
echo "wrote _redirects\n";

// Long-cache immutable assets.
file_put_contents("$dist/_headers", implode("\n", [
    '/assets/*',
    '  Cache-Control: public, max-age=31536000, immutable',
    '/style.css',
    '  Cache-Control: public, max-age=31536000, immutable',
    '/app.js',
    '  Cache-Control: public, max-age=31536000, immutable',
    '',
]));
echo "wrote _headers\n";

echo "Build complete -> $dist\n";
