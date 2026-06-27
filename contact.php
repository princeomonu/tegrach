<?php
require_once __DIR__ . '/includes/mailer.php';

$contact_status = null;   // 'success' | 'error'
$contact_msg    = '';
$old            = ['name' => '', 'company' => '', 'email' => '', 'phone' => '', 'service' => '', 'message' => ''];

$services = [
    'Dredging',
    'Civil, Electrical & Mechanical Works',
    'Facility Maintenance',
    'Pipe Solutions & Services',
    'Oil & Gas Procurement',
    'Overhaul, Installation & Commissioning',
    'Other / General Enquiry',
];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    // Honeypot — real users never fill this hidden field.
    if (trim($_POST['website'] ?? '') !== '') {
        $contact_status = 'success'; // silently drop bots
        $contact_msg    = 'Thank you — your enquiry has been received.';
    } else {
        foreach ($old as $k => $_) {
            $old[$k] = trim($_POST[$k] ?? '');
        }

        $errors = [];
        if ($old['name'] === '') {
            $errors[] = 'your name';
        }
        if ($old['email'] === '' || !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'a valid email address';
        }

        if ($errors) {
            $contact_status = 'error';
            $contact_msg    = 'Please provide ' . implode(' and ', $errors) . '.';
        } else {
            $result = tegrach_send_contact($old);
            if ($result['ok']) {
                $contact_status = 'success';
                $contact_msg    = 'Thank you — your enquiry has been sent. Our team will respond within one working day.';
                $old = array_map(fn($v) => '', $old); // clear form on success
            } else {
                $contact_status = 'error';
                $contact_msg    = $result['error'] ?: 'Something went wrong. Please try again.';
            }
        }
    }
}

$h = fn($s) => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

// Cloudflare Turnstile site key (public — safe to commit; it lives in client HTML
// by design). Env var overrides the default if you ever rotate the widget.
$turnstile_site_key = getenv('TURNSTILE_SITE_KEY') ?: '0x4AAAAAADrp2mMW2neVCsDi';

$page_title = 'Contact Us | Tegrach Nigeria Limited';
$page_description = 'Get in touch with Tegrach Nigeria Limited — request a quote or discuss your next civil, mechanical, dredging or procurement project in Nigeria.';
$canonical = '/contact';
$active = 'contact';
include __DIR__ . '/includes/header.php';
?>

<!-- ============ PAGE HERO ============ -->
<section class="page-hero">
  <div class="photo-bg" style="background-image:url('/assets/site/banner_img3-2.jpg')"></div>
  <div class="grid-bg"></div>
  <div class="glow"></div>
  <div class="wrap">
    <span class="eyebrow reveal">Get In Touch</span>
    <h1 class="reveal d1">Let's talk about your <em>next build.</em></h1>
    <nav class="breadcrumb reveal d2"><a href="/">Home</a> / <span>Contact</span></nav>
  </div>
</section>

<!-- ============ CONTACT ============ -->
<section class="block">
  <div class="wrap">
    <div class="contact-grid">
      <div class="reveal">
        <span class="eyebrow">Contact Details</span>
        <h2 class="display" style="font-size:clamp(1.9rem,3.8vw,2.8rem);margin:18px 0 30px;line-height:1.05">We're ready when you are.</h2>
        <div class="ci"><span class="ic"><svg viewBox="0 0 24 24"><path d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg></span><div><h3>Head Office</h3><p><a class="maplink" href="https://www.google.com/maps/search/?api=1&query=Plot+3+Desney+Street+Off+Refinery+Road+Warri+Delta+State+Nigeria" target="_blank" rel="noopener">Plot 3 Desney Street, Off Refinery Road, Warri, Delta State.</a></p></div></div>
        <div class="ci"><span class="ic"><svg viewBox="0 0 24 24"><path d="M6 3h4l2 5-3 2a14 14 0 006 6l2-3 5 2v4a2 2 0 01-2 2A18 18 0 014 5a2 2 0 012-2z"/></svg></span><div><h3>Phone</h3><p><a href="tel:+2347039153029">+234 703 915 3029</a><br><a href="tel:+2349028856510">+234 902 885 6510</a></p></div></div>
        <div class="ci"><span class="ic"><svg viewBox="0 0 24 24"><path d="M3 5h18v14H3z"/><path d="M3 6l9 7 9-7"/></svg></span><div><h3>Email</h3><p><a href="mailto:contact@tegrach-nigeria.com">contact@tegrach-nigeria.com</a></p></div></div>
        <div class="ci"><span class="ic"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg></span><div><h3>Working Hours</h3><p>Monday to Friday, 08:00 to 17:00</p></div></div>
      </div>

      <div class="form reveal d1">
        <?php if ($contact_status === 'success'): ?>
          <div class="form-status form-status--ok" role="status">
            <strong>Enquiry received.</strong> <?= $h($contact_msg) ?>
          </div>
        <?php elseif ($contact_status === 'error'): ?>
          <div class="form-status form-status--err" role="alert">
            <?= $h($contact_msg) ?>
          </div>
        <?php endif; ?>

        <!-- JS (Cloudflare Pages Function) injects success/error here -->
        <div id="formStatus" aria-live="polite"></div>

        <form method="POST" action="/contact" novalidate>
          <!-- honeypot: hidden from humans, tempting to bots -->
          <div aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden">
            <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
          </div>

          <div class="row">
            <div class="field"><label for="f-name">Full Name</label><input id="f-name" name="name" type="text" placeholder="Your name" value="<?= $h($old['name']) ?>" required></div>
            <div class="field"><label for="f-company">Company</label><input id="f-company" name="company" type="text" placeholder="Company name" value="<?= $h($old['company']) ?>"></div>
          </div>
          <div class="row">
            <div class="field"><label for="f-email">Email</label><input id="f-email" name="email" type="email" placeholder="you@company.com" value="<?= $h($old['email']) ?>" required></div>
            <div class="field"><label for="f-phone">Phone</label><input id="f-phone" name="phone" type="tel" placeholder="+234..." value="<?= $h($old['phone']) ?>"></div>
          </div>
          <div class="field"><label for="f-service">Service of Interest</label>
            <select id="f-service" name="service" aria-label="Service of Interest">
              <?php foreach ($services as $svc): ?>
                <option<?= $old['service'] === $svc ? ' selected' : '' ?>><?= $h($svc) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field"><label for="f-msg">Project Details</label><textarea id="f-msg" name="message" placeholder="Tell us about your project, scope and timeline..."><?= $h($old['message']) ?></textarea></div>
          <?php if ($turnstile_site_key !== ''): ?>
          <div class="field cf-turnstile" data-sitekey="<?= $h($turnstile_site_key) ?>" data-theme="dark"></div>
          <?php endif; ?>
          <button class="btn btn-orange" id="submit" type="submit"><span>Send Enquiry</span></button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php if ($turnstile_site_key !== ''): ?>
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<?php endif; ?>

<?php include __DIR__ . "/includes/footer.php"; ?>
