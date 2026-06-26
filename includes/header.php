<?php
/*
 * Shared site header: <head>, top contact strip, primary nav and mobile drawer.
 * Pages set these variables BEFORE including this file:
 *   $page_title        (string)  <title> + og/twitter title
 *   $page_description  (string)  meta description + og/twitter description
 *   $canonical         (string)  canonical/og:url path or absolute URL
 *   $active            (string)  nav key: home|about|services|projects|contact
 *   $og_image          (string)  optional, absolute OG image URL
 *   $extra_head        (string)  optional, raw HTML injected into <head>
 */
$SITE = 'https://www.tegrach-nigeria.com';

$page_title       = $page_title       ?? 'Tegrach Nigeria Limited';
$page_description = $page_description ?? 'Indigenous Nigerian EPC contractor delivering civil, mechanical, dredging, electrical and QA/QC solutions to the oil & gas, power and industrial sectors.';
$active           = $active           ?? '';
$og_image         = $og_image         ?? $SITE . '/assets/og/og-default.jpg';
$extra_head       = $extra_head       ?? '';

// Normalise canonical to an absolute URL.
$canonical = $canonical ?? '/';
if (strpos($canonical, 'http') !== 0) {
    $canonical = $SITE . $canonical;
}

if (!function_exists('nav_class')) {
    function nav_class($key, $active) {
        return $key === $active ? ' class="current"' : '';
    }
}
$e = fn($s) => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="/assets/tegrach/favicon.ico?v=4" sizes="any">
  <link rel="icon" type="image/png" sizes="32x32" href="/assets/tegrach/favicon-32.png?v=4">
  <link rel="icon" type="image/png" sizes="64x64" href="/assets/tegrach/favicon-64.png?v=4">
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/tegrach/apple-touch-icon.png?v=4">
  <meta name="theme-color" content="#121212">
<title><?= $e($page_title) ?></title>
<meta name="description" content="<?= $e($page_description) ?>">
  <link rel="canonical" href="<?= $e($canonical) ?>">
  <meta name="keywords" content="Tegrach, Tegrach Nigeria Limited, EPC contractor Nigeria, engineering Warri, dredging Nigeria, oil and gas procurement, pipeline construction, civil works, ISO 9001">
  <meta name="author" content="Tegrach Nigeria Limited">
  <meta name="robots" content="index, follow">
  <!-- Open Graph -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Tegrach Nigeria Limited">
  <meta property="og:locale" content="en_NG">
  <meta property="og:url" content="<?= $e($canonical) ?>">
  <meta property="og:title" content="<?= $e($page_title) ?>">
  <meta property="og:description" content="<?= $e($page_description) ?>">
  <meta property="og:image" content="<?= $e($og_image) ?>">
  <meta property="og:image:secure_url" content="<?= $e($og_image) ?>">
  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt" content="Tegrach Nigeria Limited — Built on Precision. Driven by Excellence.">
  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= $e($page_title) ?>">
  <meta name="twitter:description" content="<?= $e($page_description) ?>">
  <meta name="twitter:image" content="<?= $e($og_image) ?>">
  <meta name="twitter:image:alt" content="Tegrach Nigeria Limited logo and tagline">
<link rel="stylesheet" href="/style.css?v=12">
<?= $extra_head ?>
</head>
<body>

<!-- ============ TOP CONTACT STRIP ============ -->
<div class="topbar">
  <div class="wrap">
    <div class="left">
      <span class="ti"><svg viewBox="0 0 24 24"><path d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7zm0 9.5A2.5 2.5 0 1112 6.5a2.5 2.5 0 010 5z"/></svg> <a class="maplink" href="https://www.google.com/maps/search/?api=1&query=Plot+3+Desney+Street+Off+Refinery+Road+Warri+Delta+State+Nigeria" target="_blank" rel="noopener">Plot 3 Desney Street, Off Refinery Road, Warri</a></span>
      <span class="ti iso"><svg viewBox="0 0 24 24"><path d="M12 1l3 5 6 .9-4.5 4.3 1 6L12 18l-5.5 3.2 1-6L3 6.9 9 6z"/></svg> ISO 9001:2015 Certified</span>
    </div>
    <div class="right">
      <a class="ti" href="tel:+2347039153029"><svg viewBox="0 0 24 24"><path d="M6 3h4l2 5-3 2a14 14 0 006 6l2-3 5 2v4a2 2 0 01-2 2A18 18 0 014 5a2 2 0 012-2z"/></svg> +234 703 915 3029</a>
      <a class="ti" href="tel:+2349028856510"><svg viewBox="0 0 24 24"><path d="M6 3h4l2 5-3 2a14 14 0 006 6l2-3 5 2v4a2 2 0 01-2 2A18 18 0 014 5a2 2 0 012-2z"/></svg> +234 902 885 6510</a>
      <a class="ti" href="mailto:contact@tegrach-nigeria.com"><svg viewBox="0 0 24 24"><path d="M3 5h18v14H3z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M3 6l9 7 9-7" fill="none" stroke="currentColor" stroke-width="2"/></svg> contact@tegrach-nigeria.com</a>
    </div>
  </div>
</div>

<!-- ============ NAV ============ -->
<header class="nav" id="nav">
  <div class="wrap">
    <a href="/" class="brand" aria-label="Tegrach Nigeria Limited">
      <img class="brand-logo" src="/assets/tegrach/logo.png?v=5" alt="Tegrach Nigeria Limited" width="560" height="109" decoding="async" fetchpriority="low">
    </a>
    <nav class="menu" id="menu">
      <a href="/"<?= nav_class('home', $active) ?>>Home</a>
      <a href="/about"<?= nav_class('about', $active) ?>>About</a>
      <a href="/services"<?= nav_class('services', $active) ?>>Services</a>
      <a href="/projects"<?= nav_class('projects', $active) ?>>Projects</a>
      <a href="/#clients">Clients</a>
      <a href="/#partners">Partners</a>
      <a href="/contact"<?= nav_class('contact', $active) ?>>Contact</a>
      <a href="/contact" class="btn btn-orange"><span>Request a Quote</span></a>
    </nav>
    <button class="burger" id="burger" aria-label="Menu"><span></span><span></span><span></span></button>
  </div>
</header>
<div class="nav-overlay" id="overlay"></div>
<div class="drawer-clip">
  <aside class="drawer" id="drawer" aria-label="Mobile navigation">
    <button class="drawer-close" id="drawerClose" aria-label="Close menu">&times;</button>
    <a href="/">Home</a>
    <a href="/about">About</a>
    <a href="/services">Services</a>
    <a href="/projects">Projects</a>
    <a href="/#clients">Clients</a>
    <a href="/#partners">Partners</a>
    <a href="/contact">Contact</a>
    <a href="/contact" class="btn btn-orange"><span>Request a Quote</span></a>
  </aside>
</div>

<main>
