<?php
/*
 * Local-dev router that mirrors .htaccess clean-URL behaviour for PHP's
 * built-in server (which ignores .htaccess). Run with:
 *
 *     php -S localhost:8000 router.php
 *
 * Not used in production — cPanel/Apache uses .htaccess instead.
 */

$uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = __DIR__ . $uri;

// Block includes/ from being served directly.
if (preg_match('#^/includes/#', $uri)) {
    http_response_code(403);
    echo 'Forbidden';
    return true;
}

// Send index / index.php / index.html to the bare root.
if (preg_match('#^/index(\.php|\.html)?$#', $uri)) {
    header('Location: /', true, 301);
    return true;
}

// 301 direct *.php requests to their clean URL (matches .htaccess).
if (preg_match('/\.php$/', $uri) && $uri !== '/router.php') {
    $clean = preg_replace('/\.php$/', '', $uri);
    header('Location: ' . $clean, true, 301);
    return true;
}

// Serve existing static files (assets, css, js, images) directly.
if ($uri !== '/' && file_exists($path) && !is_dir($path)) {
    return false;
}

// Root -> index.php
if ($uri === '/' || $uri === '') {
    require __DIR__ . '/index.php';
    return true;
}

// Legacy redirects
$legacy = ltrim($uri, '/');
if (preg_match('/\.html$/', $legacy)) {
    header('Location: /' . preg_replace('/\.html$/', '', $legacy), true, 301);
    return true;
}
if ($legacy === 'contact_us' || $legacy === 'contact_us.php') {
    header('Location: /contact', true, 301);
    return true;
}

// Clean URL -> matching .php file
$candidate = __DIR__ . '/' . trim($uri, '/') . '.php';
if (file_exists($candidate)) {
    require $candidate;
    return true;
}

http_response_code(404);
echo 'Not found';
return true;
