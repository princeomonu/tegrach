<?php
/*
 * Copy this file to config.php (in this same platform/php/ folder) and fill in
 * real values. config.php is gitignored and must NEVER be committed.
 *
 * On the live cPanel host set APP_ENV to 'production'.
 * Locally / on staging keep it 'development' so enquiries route to the test inbox.
 */

// 'production' => emails go to contact@tegrach-nigeria.com
// anything else => emails go to the dev inbox below
define('APP_ENV', 'development');

// Resend API key — create at https://resend.com/api-keys
define('RESEND_API_KEY', 're_xxxxxxxxxxxxxxxxxxxxxxxx');

// Where contact-form submissions are delivered.
define('CONTACT_TO', APP_ENV === 'production'
    ? 'contact@tegrach-nigeria.com'
    : 'developer@tegrach-nigeria.com');

// Verified Resend sender. The domain must be verified in Resend before this works.
// Use a REAL, monitored mailbox (not a no-reply) so replies/bounces don't vanish
// and so spam filters are friendlier. The submitter's address is set as Reply-To,
// so hitting "Reply" still goes to the customer.
// Until the domain is verified you can use 'Tegrach Website <onboarding@resend.dev>'
// (Resend's test sender), which only delivers to your own Resend account email.
define('CONTACT_FROM', 'Tegrach Nigeria <website@tegrach-nigeria.com>');
