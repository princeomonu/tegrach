<?php
/*
 * Sends the contact form via the Resend HTTP API (https://resend.com).
 * mail() is intentionally NOT used — poor deliverability and often blocked.
 * Requires includes/config.php (RESEND_API_KEY, CONTACT_TO, CONTACT_FROM).
 * If config.php is missing the page still loads — the form just reports that
 * email isn't configured yet. Copy includes/config.example.php to config.php.
 */

if (is_file(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
} else {
    error_log('Tegrach: includes/config.php is missing — copy config.example.php and set your Resend key.');
}

/**
 * @param array $data Keys: name, company, email, phone, service, message
 * @return array ['ok' => bool, 'error' => string|null]
 */
function tegrach_send_contact(array $data): array {
    if (!defined('RESEND_API_KEY') || RESEND_API_KEY === '' || strpos(RESEND_API_KEY, 're_') !== 0) {
        return ['ok' => false, 'error' => 'Email service is not configured.'];
    }

    $name    = $data['name']    ?? '';
    $company = $data['company'] ?? '';
    $email   = $data['email']   ?? '';
    $phone   = $data['phone']   ?? '';
    $service = $data['service'] ?? '';
    $message = $data['message'] ?? '';

    // Embed the logo as an inline (CID) attachment so it shows regardless of
    // whether the live site is deployed yet. Falls back to the absolute URL
    // if the file can't be read.
    $attachments = [];
    $logoSrc     = 'https://www.tegrach-nigeria.com/assets/tegrach/favicon-64.png';
    $logoPath    = __DIR__ . '/../assets/tegrach/favicon-64.png';
    if (is_readable($logoPath)) {
        $attachments[] = [
            'filename'     => 'favicon-64.png',
            'content'      => base64_encode(file_get_contents($logoPath)),
            'content_id'   => 'tegrachlogo',
            'content_type' => 'image/png',
        ];
        $logoSrc = 'cid:tegrachlogo';
    }

    $html = tegrach_contact_email_html([
        'name'    => $name,
        'company' => $company,
        'email'   => $email,
        'phone'   => $phone,
        'service' => $service,
        'message' => $message,
        'logo'    => $logoSrc,
    ]);

    $text = "New website enquiry\n\n"
        . "Name: $name\n"
        . ($company !== '' ? "Company: $company\n" : '')
        . "Email: $email\n"
        . ($phone !== '' ? "Phone: $phone\n" : '')
        . ($service !== '' ? "Service: $service\n" : '')
        . "\nMessage:\n$message\n";

    $payload = [
        'from'     => CONTACT_FROM,
        'to'       => [CONTACT_TO],
        'subject'  => 'Website enquiry' . ($service !== '' ? ': ' . $service : '') . ($name !== '' ? ' — ' . $name : ''),
        'html'     => $html,
        'text'     => $text,
    ];
    if ($attachments) {
        $payload['attachments'] = $attachments;
    }
    if ($email !== '') {
        $payload['reply_to'] = $email;
    }

    $ch = curl_init('https://api.resend.com/emails');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . RESEND_API_KEY,
            'Content-Type: application/json',
        ],
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_TIMEOUT        => 20,
    ]);

    $response = curl_exec($ch);
    $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr  = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        error_log('Resend cURL error: ' . $curlErr);
        return ['ok' => false, 'error' => 'Could not reach the email service. Please try again.'];
    }
    if ($status < 200 || $status >= 300) {
        error_log('Resend API error (' . $status . '): ' . $response);
        return ['ok' => false, 'error' => 'The email service rejected the request. Please try again later.'];
    }

    return ['ok' => true, 'error' => null];
}

/**
 * Builds the branded HTML email (Tegrach colours, dark header + logo).
 * Table-based with inline styles for broad email-client compatibility.
 */
function tegrach_contact_email_html(array $d): string {
    $site   = 'https://www.tegrach-nigeria.com';
    $orange = '#FD6600';
    $ink    = '#161616';
    $font   = "'Segoe UI',Tahoma,Geneva,Verdana,Arial,sans-serif";
    $logo   = $d['logo'] ?? ($site . '/assets/tegrach/logo.png');

    $esc = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');

    // One details row; skipped entirely when the value is empty.
    $row = function (string $label, string $value, bool $accent = false) use ($esc, $orange, $font): string {
        if (trim($value) === '') {
            return '';
        }
        $val = nl2br($esc($value));
        $valStyle = $accent
            ? "font-family:$font;font-size:15px;color:$orange;font-weight:700"
            : "font-family:$font;font-size:15px;color:#161616";
        return '<tr>'
            . '<td style="padding:13px 16px;border-bottom:1px solid #ececec;background:#fafafa;'
            . "font-family:$font;font-size:11px;letter-spacing:.08em;text-transform:uppercase;"
            . 'color:#8a8a8a;font-weight:700;white-space:nowrap;vertical-align:top;width:120px">' . $esc($label) . '</td>'
            . '<td style="padding:13px 18px;border-bottom:1px solid #ececec;' . $valStyle . '">' . $val . '</td>'
            . '</tr>';
    };

    // Note: we deliberately avoid a mailto: button to the sender — its domain
    // would differ from the sending domain and trip spam filters. The Reply-To
    // header already routes a normal "Reply" straight to the customer, and the
    // footer reminds the reader of that.

    return '<!DOCTYPE html><html><head><meta charset="utf-8">'
        . '<meta name="viewport" content="width=device-width,initial-scale=1"></head>'
        . '<body style="margin:0;padding:0;background:#f4f4f2">'
        . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f2;padding:28px 12px">'
        . '<tr><td align="center">'
        . '<table role="presentation" width="600" cellpadding="0" cellspacing="0" '
        . 'style="width:600px;max-width:100%;background:#ffffff;border:1px solid #e6e6e6">'

        // top accent bar
        . '<tr><td style="height:5px;background:' . $orange . ';font-size:0;line-height:0">&nbsp;</td></tr>'

        // dark header with logo
        . '<tr><td style="background:' . $ink . ';padding:26px 28px">'
        . '<img src="' . $esc($logo) . '" alt="Tegrach Nigeria Limited" '
        . 'width="44" height="44" style="display:block;border:0;width:44px;height:44px">'
        . '<p style="margin:14px 0 0;font-family:' . $font . ';font-size:12px;letter-spacing:.14em;'
        . 'text-transform:uppercase;color:' . $orange . ';font-weight:700">New Website Enquiry</p>'
        . '</td></tr>'

        // intro
        . '<tr><td style="padding:26px 28px 6px">'
        . '<p style="margin:0;font-family:' . $font . ';font-size:15px;line-height:1.55;color:#444">'
        . 'A new enquiry was submitted through the contact form on '
        . '<a href="' . $site . '" style="color:' . $orange . ';text-decoration:none">tegrach-nigeria.com</a>.</p>'
        . '</td></tr>'

        // details
        . '<tr><td style="padding:14px 28px 4px">'
        . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" '
        . 'style="border:1px solid #ececec;border-collapse:collapse">'
        . $row('Name', $d['name'])
        . $row('Company', $d['company'])
        . $row('Email', $d['email'])
        . $row('Phone', $d['phone'])
        . $row('Service', $d['service'], true)
        . $row('Message', $d['message'])
        . '</table></td></tr>'

        // footer
        . '<tr><td style="background:' . $ink . ';padding:22px 28px;margin-top:20px">'
        . '<p style="margin:0;font-family:' . $font . ';font-size:13px;line-height:1.6;color:#bdbdbd">'
        . '<strong style="color:#fff">Tegrach Nigeria Limited</strong><br>'
        . 'Plot 3 Desney Street, Off Refinery Road, Warri, Delta State.<br>'
        . '+234 703 915 3029 &nbsp;&middot;&nbsp; contact@tegrach-nigeria.com</p>'
        . '<p style="margin:14px 0 0;font-family:' . $font . ';font-size:11px;color:#7a7a7a">'
        . 'RC 991748 &nbsp;|&nbsp; ISO 9001:2015 &nbsp;|&nbsp; Reply directly to this email to respond to the sender.</p>'
        . '</td></tr>'

        . '</table></td></tr></table></body></html>';
}
