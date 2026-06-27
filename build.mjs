/*
 * Static site builder for Vercel (no PHP needed in CI).
 * Wraps each body partial in site/pages/*.html with the shared header/footer
 * chrome and writes the result to public/. Copies assets, style.css, app.js.
 *
 * Vercel: Build command = `node build.mjs`, Output directory = `public`.
 * Run locally: `node build.mjs`.
 */
import { readFileSync, writeFileSync, mkdirSync, rmSync, cpSync, existsSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import { dirname, join } from 'node:path';

const root = dirname(fileURLToPath(import.meta.url));
const out = join(root, 'public');
const SITE = 'https://www.tegrach-nigeria.com';
const OG = `${SITE}/assets/og/og-default.jpg`;

const esc = (s) =>
  String(s ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

const navItem = (href, key, active, label) =>
  `<a href="${href}"${key && key === active ? ' class="current"' : ''}>${label}</a>`;

const header = (m) => {
  const canonical = m.canonical.startsWith('http') ? m.canonical : SITE + m.canonical;
  const title = esc(m.title);
  const desc = esc(m.description);
  const ogImage = esc(m.ogImage || OG);
  return `<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="/assets/tegrach/favicon.ico?v=4" sizes="any">
  <link rel="icon" type="image/png" sizes="32x32" href="/assets/tegrach/favicon-32.png?v=4">
  <link rel="icon" type="image/png" sizes="64x64" href="/assets/tegrach/favicon-64.png?v=4">
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/tegrach/apple-touch-icon.png?v=4">
  <meta name="theme-color" content="#121212">
<title>${title}</title>
<meta name="description" content="${desc}">
  <link rel="canonical" href="${esc(canonical)}">
  <meta name="keywords" content="Tegrach, Tegrach Nigeria Limited, EPC contractor Nigeria, engineering Warri, dredging Nigeria, oil and gas procurement, pipeline construction, civil works, ISO 9001">
  <meta name="author" content="Tegrach Nigeria Limited">
  <meta name="robots" content="index, follow">
  <!-- Open Graph -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Tegrach Nigeria Limited">
  <meta property="og:locale" content="en_NG">
  <meta property="og:url" content="${esc(canonical)}">
  <meta property="og:title" content="${title}">
  <meta property="og:description" content="${desc}">
  <meta property="og:image" content="${ogImage}">
  <meta property="og:image:secure_url" content="${ogImage}">
  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt" content="Tegrach Nigeria Limited — Built on Precision. Driven by Excellence.">
  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="${title}">
  <meta name="twitter:description" content="${desc}">
  <meta name="twitter:image" content="${ogImage}">
  <meta name="twitter:image:alt" content="Tegrach Nigeria Limited logo and tagline">
<link rel="stylesheet" href="/style.css?v=12">
${m.extraHead || ''}
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
      ${navItem('/', 'home', m.active, 'Home')}
      ${navItem('/about', 'about', m.active, 'About')}
      ${navItem('/services', 'services', m.active, 'Services')}
      ${navItem('/projects', 'projects', m.active, 'Projects')}
      <a href="/#clients">Clients</a>
      <a href="/#partners">Partners</a>
      ${navItem('/contact', 'contact', m.active, 'Contact')}
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
`;
};

const footer = () => `</main>

<!-- ============ FOOTER ============ -->
<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div class="foot-brand">
        <a href="/" class="brand">
      <img class="brand-logo" src="/assets/tegrach/logo.png?v=5" alt="Tegrach Nigeria Limited" width="560" height="109" decoding="async" loading="lazy">
    </a>
        <p>Our clients and our people, in particular their health, safety and quality, are the primary focus of all Tegrach Nigeria Limited activity.</p>
      </div>
      <div class="foot-col">
        <p class="foot-title">Services</p>
        <a href="/services">Dredging</a>
        <a href="/services">Civil, Electrical &amp; Mechanical</a>
        <a href="/services">Facility Maintenance</a>
        <a href="/services">Pipe Solutions &amp; Services</a>
        <a href="/services">Oil &amp; Gas Procurement</a>
        <a href="/services">Overhaul &amp; Commissioning</a>
      </div>
      <div class="foot-col">
        <p class="foot-title">Company</p>
        <a href="/about">About Us</a>
        <a href="/projects">Projects</a>
        <a href="/#clients">Clients</a>
        <a href="/#partners">Partners</a>
        <a href="/contact">Contact</a>
      </div>
      <div class="foot-col">
        <p class="foot-title">Get In Touch</p>
        <ul>
          <li><a class="maplink" href="https://www.google.com/maps/search/?api=1&query=Plot+3+Desney+Street+Off+Refinery+Road+Warri+Delta+State+Nigeria" target="_blank" rel="noopener">Plot 3 Desney Street, Off Refinery Road, Warri, Delta State.</a></li>
          <li><a href="tel:+2347039153029">+234 703 915 3029</a></li>
          <li><a href="tel:+2349028856510">+234 902 885 6510</a></li>
          <li><a href="mailto:contact@tegrach-nigeria.com">contact@tegrach-nigeria.com</a></li>
          <li>Mon to Fri, 08:00 to 17:00</li>
        </ul>
      </div>
    </div>
    <div class="foot-bottom">
      <p>Copyright &copy; <span id="yr"></span> Tegrach Nigeria Limited. All rights reserved.</p>
      <span class="rc">RC 991748 &nbsp;|&nbsp; ISO 9001:2015</span>
    </div>
  </div>
</footer>

<script src="/app.js?v=14" defer></script>
</body>
</html>
`;

const INDEX_EXTRA_HEAD = `<!-- LCP hero image: discoverable + high priority -->
<link rel="preload" as="image" href="/assets/site/banner_img1-2.webp?v=2" type="image/webp" fetchpriority="high">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Tegrach Nigeria Limited",
  "url": "https://www.tegrach-nigeria.com",
  "logo": "https://www.tegrach-nigeria.com/assets/tegrach/logo.png",
  "image": "https://www.tegrach-nigeria.com/assets/og/og-default.jpg",
  "description": "Indigenous Nigerian EPC contractor delivering civil, mechanical, dredging, electrical and QA/QC solutions to the oil & gas, power and industrial sectors.",
  "slogan": "Built on Precision. Driven by Excellence.",
  "foundingDate": "2011",
  "email": "contact@tegrach-nigeria.com",
  "telephone": "+234-703-915-3029",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Plot 3 Desney Street, Off Refinery Road",
    "addressLocality": "Warri",
    "addressRegion": "Delta State",
    "addressCountry": "NG"
  },
  "areaServed": "NG",
  "sameAs": []
}
</script>`;

const PAGES = [
  {
    slug: 'index',
    title: 'Tegrach Nigeria Limited | Built on Precision. Driven by Excellence.',
    description:
      'Indigenous Nigerian EPC contractor delivering civil, mechanical, dredging, electrical and QA/QC solutions to the oil & gas, power and industrial sectors.',
    canonical: '/',
    active: 'home',
    extraHead: INDEX_EXTRA_HEAD,
  },
  {
    slug: 'about',
    title: 'About Us | Tegrach Nigeria Limited',
    description:
      'A wholly indigenous engineering, procurement and construction company born out of African technical necessity — ISO 9001:2015 certified, since 2011.',
    canonical: '/about',
    active: 'about',
  },
  {
    slug: 'services',
    title: 'Services | Tegrach Nigeria Limited',
    description:
      'Dredging, civil/electrical/mechanical works, facility maintenance, pipe solutions, oil & gas procurement, overhaul, installation and commissioning.',
    canonical: '/services',
    active: 'services',
  },
  {
    slug: 'projects',
    title: 'Projects | Tegrach Nigeria Limited',
    description:
      'Selected Tegrach Nigeria Limited projects across automotive, construction, energy and exploration sectors.',
    canonical: '/projects',
    active: 'projects',
  },
  {
    slug: 'contact',
    title: 'Contact Us | Tegrach Nigeria Limited',
    description:
      'Get in touch with Tegrach Nigeria Limited — request a quote or discuss your next civil, mechanical, dredging or procurement project in Nigeria.',
    canonical: '/contact',
    active: 'contact',
  },
];

// Fresh output dir.
rmSync(out, { recursive: true, force: true });
mkdirSync(out, { recursive: true });

for (const p of PAGES) {
  const body = readFileSync(join(root, 'site', 'pages', `${p.slug}.html`), 'utf8');
  writeFileSync(join(out, `${p.slug}.html`), header(p) + body + footer());
  console.log(`built ${p.slug}.html`);
}

for (const a of ['assets', 'style.css', 'app.js']) {
  if (existsSync(join(root, a))) {
    cpSync(join(root, a), join(out, a), { recursive: true });
    console.log(`copied ${a}`);
  }
}

console.log(`Build complete -> ${out}`);
