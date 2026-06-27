<?php
/*
 * Contact form handler for an Apache/PHP webhost — POST /api/contact.
 *
 * Static-first: every page is served as pre-built static HTML from dist/. This
 * is the ONLY dynamic file. The generated dist/.htaccess rewrites
 * /api/contact -> contact.php. Deploy by uploading the contents of dist/ to the
 * web root, then dropping this file plus mailer.php and config.php beside it.
 *
 * Returns JSON ({ "ok": true } or { "ok": false, "error": "..." }) to match the
 * Vercel (api/contact.js) and Cloudflare (platform/cloudflare/...) handlers, so
 * the shared front-end (src/app.js) works unchanged across all three hosts.
 *
 * Requires config.php (copy config.example.php) with the Resend credentials.
 */

require_once __DIR__ . '/mailer.php';

header('Content-Type: application/json; charset=utf-8');

function reply(array $body, int $status = 200): void {
    http_response_code($status);
    echo json_encode($body);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Allow: POST');
    reply(['ok' => false, 'error' => 'Method not allowed.'], 405);
}

// Accept either a JSON body (the front-end posts application/json) or a classic
// form-encoded POST as a fallback.
$raw = file_get_contents('php://input');
$data = [];
if ($raw !== '' && $raw !== false) {
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        $data = $decoded;
    }
}
if (!$data) {
    $data = $_POST;
}

$get = fn($k) => trim((string)($data[$k] ?? ''));

// Honeypot — real users never fill this hidden field. Silently accept bots.
if ($get('website') !== '') {
    reply(['ok' => true]);
}

$fields = [
    'name'    => $get('name'),
    'company' => $get('company'),
    'email'   => $get('email'),
    'phone'   => $get('phone'),
    'service' => $get('service'),
    'message' => $get('message'),
];

$need = [];
if ($fields['name'] === '') {
    $need[] = 'your name';
}
if ($fields['email'] === '' || !filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
    $need[] = 'a valid email address';
}
if ($need) {
    reply(['ok' => false, 'error' => 'Please provide ' . implode(' and ', $need) . '.'], 422);
}

$result = tegrach_send_contact($fields);
if (!empty($result['ok'])) {
    reply(['ok' => true]);
}
reply(['ok' => false, 'error' => $result['error'] ?: 'Something went wrong. Please try again.'], 502);
