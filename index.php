<?php
$page_title = 'Tegrach Nigeria Limited | Built on Precision. Driven by Excellence.';
$page_description = 'Indigenous Nigerian EPC contractor delivering civil, mechanical, dredging, electrical and QA/QC solutions to the oil & gas, power and industrial sectors.';
$canonical = '/';
$active = 'home';
$extra_head = <<<'HTML'
<!-- LCP hero image: discoverable + high priority -->
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
</script>
HTML;
include __DIR__ . '/includes/header.php';
?>
<!-- ============ HERO ============ -->
<section class="hero" id="home">
  <div class="photo-bg" aria-hidden="true">
    <picture>
      <source srcset="/assets/site/banner_img1-2.webp?v=2" type="image/webp">
      <img src="/assets/site/banner_img1-2.jpg?v=2" alt="" width="1920" height="615" fetchpriority="high" loading="eager" decoding="async">
    </picture>
  </div>
  <div class="hero-rail" aria-hidden="true">
    <span class="rail-line"></span>
    <span class="rail-label">Est. 2011</span>
  </div>
  <div class="hero-rail hero-rail--right" aria-hidden="true">
    <span class="rail-line"></span>
    <span class="rail-label">RC 991748</span>
  </div>
  <div class="grid-bg"></div>
  <div class="glow"></div>
  <div class="glow b"></div>
  <div class="super">
    <svg viewBox="0 0 96 64"><g fill="currentColor" transform="skewX(-11)"><polygon points="10,6 50,6 50,18 38,18 38,58 22,58 22,18 10,18"/><path d="M54,6 H86 V18 H66 V46 H74 V36 H86 V58 H54 Z"/></g></svg>
  </div>
  <div class="wrap">
    <span class="eyebrow">Indigenous Engineering, Procurement &amp; Construction</span>
    <h1>Built on Precision. <br><em>Driven by Excellence.</em></h1>
    <p class="lead">A wholly indigenous Nigerian contractor delivering civil, mechanical, dredging, electrical and QA/QC solutions to the oil &amp; gas, power and industrial sectors across the country and the African sub-region.</p>
    <div class="cta">
      <a href="/services" class="btn btn-orange"><span>Explore Our Services</span></a>
      <a href="/contact" class="btn btn-ghost"><span>Talk to Our Team</span></a>
    </div>
    <div class="scroll-cue"><i></i> Scroll to discover</div>
  </div>
</section>

<!-- ============ STATS ============ -->
<div class="stats"><div class="inner"><div class="wrap">
  <div class="stat reveal"><div class="n" data-count="2011">2011</div><div class="l">Operating Since</div></div>
  <div class="stat reveal d1"><div class="n"><span data-count="33">0</span></div><div class="l">Services Offered</div></div>
  <div class="stat reveal d2"><div class="n"><span data-count="11">0</span></div><div class="l">Regional Offices</div></div>
  <div class="stat reveal d3"><div class="n">ISO</div><div class="l">9001:2015 Certified</div></div>
  <div class="stat reveal d4"><div class="n"><span data-count="24">0</span>/7</div><div class="l">Site Support</div></div>
</div></div></div>

<!-- ============ ABOUT ============ -->
<section class="block light" id="about">
  <div class="wrap">
    <div class="about-grid">
      <div class="about-copy reveal">
        <span class="eyebrow">Who We Are</span>
        <h2 class="about-title">Built out of African technical necessity.</h2>
        <p class="lead-p">Tegrach Nigeria Limited integrates quality human management with deep technical resourcefulness, built on long experience across Nigeria's engineering fields.</p>
        <p><strong>Tegrach Nigeria Limited</strong> is a wholly indigenous technical company that has grown from premier engineering equipment and spare parts procurement into a specialist EPIC contractor, developing its people, procedures, safety standards and quality management systems to meet demanding customer needs.</p>
        <p>Working in cooperation with foreign technical partners and manufacturers, we focus on clients across Nigeria and the wider African sub-region, tailoring complete technical and economic solutions to specific requirements.</p>
        <p>We serve industries ranging from oil and gas and power generation to refineries, pharmaceuticals and food processing, always with our people, their health, safety and quality at the centre of everything we do.</p>
        <div style="margin-top:8px"><a href="/about" class="btn btn-orange"><span>More About Us</span></a></div>
      </div>
      <div class="about-side">
        <div class="mvcard blue reveal d1">
          <h3>Our Vision</h3>
          <p>To be the technical service company of choice in Nigeria and within the African sub-region.</p>
        </div>
        <div class="mvcard reveal d2">
          <h3>Our Mission</h3>
          <p>Acquire technology through joint ventures with our foreign partners, stimulate local co-investment, benefit from technology transfer to sustain development, and compete internationally.</p>
        </div>
        <div class="mvcard reveal d3">
          <h3>Our Values</h3>
          <p><strong>Commitment to quality</strong> first &mdash; alongside safety, integrity, innovation, teamwork, customer satisfaction and openness.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ SERVICES ============ -->
<section class="block" id="services">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="eyebrow">What We Do</span>
      <h2>Full-spectrum engineering capability.</h2>
      <p>From foundations to fabrication, inspection to installation, Tegrach delivers integrated solutions across six core disciplines.</p>
    </div>
    <div class="svc-grid">

      <article class="svc reveal">
        <div class="idx">01</div>
        <div class="ico"><svg viewBox="0 0 48 48"><path d="M4 32c4 0 4-3 8-3s4 3 8 3 4-3 8-3 4 3 8 3 4-3 8-3M6 24l8-12 6 8 5-6 11 18"/></svg></div>
        <h3>Dredging</h3>
        <ul>
          <li>River, lake &amp; marina dredging</li>
          <li>Sand mining &amp; haulage</li>
          <li>Land reclamation &amp; island creation</li>
          <li>Road filling support</li>
        </ul>
      </article>

      <article class="svc reveal d1">
        <div class="idx">02</div>
        <div class="ico"><svg viewBox="0 0 48 48"><path d="M4 40h40M8 40V20l16-10 16 10v20M16 40V28h16v12"/></svg></div>
        <h3>Civil, Electrical &amp; Mechanical Works</h3>
        <ul>
          <li>Civil &amp; structural engineering</li>
          <li>Electrical &amp; mechanical works</li>
          <li>Piping &amp; process engineering</li>
          <li>Buildings, roads &amp; foundations</li>
        </ul>
      </article>

      <article class="svc reveal d2">
        <div class="idx">03</div>
        <div class="ico"><svg viewBox="0 0 48 48"><path d="M14.7 18.3a6 6 0 108.4 8.4l-9-9 .6-.6 9 9a6 6 0 00-8.4-8.4zM6 42l12-12M30 18l12-12"/></svg></div>
        <h3>Facility Maintenance</h3>
        <ul>
          <li>Operations &amp; maintenance (O&amp;M)</li>
          <li>Managerial &amp; hands-on teams</li>
          <li>Planned &amp; reactive maintenance</li>
          <li>Process plant support</li>
        </ul>
      </article>

      <article class="svc reveal">
        <div class="idx">04</div>
        <div class="ico"><svg viewBox="0 0 48 48"><path d="M6 30h22M6 30a4 4 0 010-8h22M28 22h8l6 4v4h-14M12 36a3 3 0 11-6 0 3 3 0 016 0zM40 36a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
        <h3>Pipe Solutions &amp; Services</h3>
        <ul>
          <li>Pipeline design &amp; construction</li>
          <li>Corrosion &amp; abrasion protection</li>
          <li>Flow lines &amp; field joint coating</li>
          <li>Integrity &amp; NDT services</li>
        </ul>
      </article>

      <article class="svc reveal d1">
        <div class="idx">05</div>
        <div class="ico"><svg viewBox="0 0 48 48"><path d="M6 14h28v22H6zM34 20h6l4 6v10h-10M14 36a4 4 0 11-8 0 4 4 0 018 0zM42 36a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
        <h3>Oil &amp; Gas Procurement</h3>
        <ul>
          <li>Equipment &amp; material supply</li>
          <li>Spare parts &amp; fittings</li>
          <li>Foreign manufacturer sourcing</li>
          <li>Logistics across Africa &amp; beyond</li>
        </ul>
      </article>

      <article class="svc reveal d2">
        <div class="idx">06</div>
        <div class="ico"><svg viewBox="0 0 48 48"><circle cx="24" cy="24" r="6"/><path d="M24 4v6M24 38v6M4 24h6M38 24h6M10 10l4 4M34 34l4 4M38 10l-4 4M14 34l-4 4"/></svg></div>
        <h3>Overhaul, Installation &amp; Commissioning</h3>
        <ul>
          <li>Site service team support</li>
          <li>Equipment overhaul</li>
          <li>Installation &amp; commissioning</li>
          <li>On-demand high-quality support</li>
        </ul>
      </article>

    </div>
    <div style="margin-top:40px"><a href="/services" class="btn btn-ghost"><span>All Services &amp; Details</span></a></div>
  </div>
</section>

<!-- ============ CAPABILITIES ============ -->
<section class="block caps" id="capabilities">
  <div class="super"><svg viewBox="0 0 96 64"><g fill="currentColor" transform="skewX(-11)"><polygon points="10,6 50,6 50,18 38,18 38,58 22,58 22,18 10,18"/><path d="M54,6 H86 V18 H66 V46 H74 V36 H86 V58 H54 Z"/></g></svg></div>
  <div class="wrap">
    <div class="caps-grid">
      <div class="reveal">
        <span class="eyebrow">Industries Served</span>
        <h2 class="display" style="font-size:clamp(1.9rem,3.8vw,2.8rem);margin:18px 0 22px;line-height:1.05">Trusted across Nigeria's most demanding sectors.</h2>
        <div class="chips">
          <span class="chip"><span>Oil &amp; Gas</span></span>
          <span class="chip"><span>Power Plants</span></span>
          <span class="chip"><span>Refineries</span></span>
          <span class="chip"><span>Pharmaceutical</span></span>
          <span class="chip"><span>Food Processing</span></span>
        </div>
        <div class="iso-badge reveal d2">
          <span class="ring">ISO</span>
          <span><b>ISO 9001:2015</b><small>Quality Management System certified</small></span>
        </div>
      </div>
      <div class="qa-list reveal d1">
        <div class="qa">
          <span class="b"><svg viewBox="0 0 24 24"><path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6z"/><path d="M9 12l2 2 4-4"/></svg></span>
          <div><h3>HSE-Led Delivery</h3><p>A distinctive health, safety and environmental management system governs every project from mobilisation to handover.</p></div>
        </div>
        <div class="qa">
          <span class="b"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-5-5"/></svg></span>
          <div><h3>Inspection &amp; Integrity</h3><p>Full non-destructive testing suite (MT, PT, RT, UT, LT), cathodic protection and corrosion control for critical assets.</p></div>
        </div>
        <div class="qa">
          <span class="b"><svg viewBox="0 0 24 24"><path d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-6h6v6"/></svg></span>
          <div><h3>Local Content, Global Standards</h3><p>Indigenous capability backed by foreign technical partners, sustaining technology transfer and local co-investment.</p></div>
        </div>
        <div class="qa">
          <span class="b"><svg viewBox="0 0 24 24"><path d="M12 2v20M2 12h20"/><circle cx="12" cy="12" r="9"/></svg></span>
          <div><h3>End-to-End EPC</h3><p>Engineering, procurement, construction and commissioning delivered under one accountable team.</p></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ PROJECTS ============ -->
<section class="block light" id="projects">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="eyebrow">Selected Work</span>
      <h2>Capability proven in the field.</h2>
      <p>Whether it is new construction, preventative maintenance or a full performance overhaul, our teams deliver on the ground.</p>
    </div>
    <div class="proj-grid home">
      <div class="proj reveal"><div class="pw"><svg viewBox="0 0 96 64"><g fill="currentColor" transform="skewX(-11)"><polygon points="10,6 50,6 50,18 38,18 38,58 22,58 22,18 10,18"/><path d="M54,6 H86 V18 H66 V46 H74 V36 H86 V58 H54 Z"/></g></svg></div><div class="meta"><div class="cat">Pipeline</div><h3>Flow Line Construction &amp; Installation</h3></div></div>
      <div class="proj reveal d1"><div class="pw"><svg viewBox="0 0 96 64"><g fill="currentColor" transform="skewX(-11)"><polygon points="10,6 50,6 50,18 38,18 38,58 22,58 22,18 10,18"/><path d="M54,6 H86 V18 H66 V46 H74 V36 H86 V58 H54 Z"/></g></svg></div><div class="meta"><div class="cat">Dredging</div><h3>River Dredging &amp; Land Reclamation</h3></div></div>
      <div class="proj reveal d2"><div class="pw"><svg viewBox="0 0 96 64"><g fill="currentColor" transform="skewX(-11)"><polygon points="10,6 50,6 50,18 38,18 38,58 22,58 22,18 10,18"/><path d="M54,6 H86 V18 H66 V46 H74 V36 H86 V58 H54 Z"/></g></svg></div><div class="meta"><div class="cat">Fabrication</div><h3>Storage Tank &amp; Pressure Vessel Build</h3></div></div>
      <div class="proj reveal"><div class="pw"><svg viewBox="0 0 96 64"><g fill="currentColor" transform="skewX(-11)"><polygon points="10,6 50,6 50,18 38,18 38,58 22,58 22,18 10,18"/><path d="M54,6 H86 V18 H66 V46 H74 V36 H86 V58 H54 Z"/></g></svg></div><div class="meta"><div class="cat">Civil</div><h3>Road &amp; Drainage Construction</h3></div></div>
      <div class="proj reveal d1"><div class="pw"><svg viewBox="0 0 96 64"><g fill="currentColor" transform="skewX(-11)"><polygon points="10,6 50,6 50,18 38,18 38,58 22,58 22,18 10,18"/><path d="M54,6 H86 V18 H66 V46 H74 V36 H86 V58 H54 Z"/></g></svg></div><div class="meta"><div class="cat">Electrical</div><h3>Power Distribution &amp; Fire Systems</h3></div></div>
      <div class="proj reveal d2"><div class="pw"><svg viewBox="0 0 96 64"><g fill="currentColor" transform="skewX(-11)"><polygon points="10,6 50,6 50,18 38,18 38,58 22,58 22,18 10,18"/><path d="M54,6 H86 V18 H66 V46 H74 V36 H86 V58 H54 Z"/></g></svg></div><div class="meta"><div class="cat">QA / QC</div><h3>NDT &amp; Corrosion Control Programme</h3></div></div>
    </div>
    <div style="margin-top:40px"><a href="/projects" class="btn btn-orange"><span>View All Projects</span></a></div>
  </div>
</section>

<!-- ============ OFFICES ============ -->
<section class="block light" id="offices" style="background:var(--paper-2)">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="eyebrow">Where We Operate</span>
      <h2>A presence across the South-South and beyond.</h2>
      <p>Eleven regional offices keep our teams close to the projects and clients we serve.</p>
    </div>
    <div class="off-grid">
      <div class="off head reveal"><div class="pin"><svg viewBox="0 0 24 24"><path d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg></div><h3>Warri</h3><span>Head Office &middot; Delta State</span></div>
      <div class="off reveal d1"><div class="pin"><svg viewBox="0 0 24 24"><path d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg></div><h3>Port-Harcourt</h3><span>Rivers State</span></div>
      <div class="off reveal d2"><div class="pin"><svg viewBox="0 0 24 24"><path d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg></div><h3>Uyo</h3><span>Akwa Ibom State</span></div>
      <div class="off reveal d3"><div class="pin"><svg viewBox="0 0 24 24"><path d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg></div><h3>Bayelsa</h3><span>Regional Office</span></div>
      <div class="off reveal d4"><div class="pin"><svg viewBox="0 0 24 24"><path d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg></div><h3>Lagos</h3><span>Lagos State</span></div>
      <div class="off reveal d1"><div class="pin"><svg viewBox="0 0 24 24"><path d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg></div><h3>Abuja</h3><span>FCT</span></div>
      <div class="off reveal d2"><div class="pin"><svg viewBox="0 0 24 24"><path d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg></div><h3>Benin</h3><span>Edo State</span></div>
      <div class="off reveal d3"><div class="pin"><svg viewBox="0 0 24 24"><path d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg></div><h3>Anambra</h3><span>Regional Office</span></div>
      <div class="off reveal d4"><div class="pin"><svg viewBox="0 0 24 24"><path d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg></div><h3>Enugu</h3><span>Enugu State</span></div>
      <div class="off reveal d1"><div class="pin"><svg viewBox="0 0 24 24"><path d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg></div><h3>Ebonyi</h3><span>Regional Office</span></div>
      <div class="off reveal d2"><div class="pin"><svg viewBox="0 0 24 24"><path d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg></div><h3>Ondo</h3><span>Ondo State</span></div>
    </div>
  </div>
</section>

<!-- ============ CLIENTS ============ -->
<section class="block light" id="clients">
  <div class="wrap">
    <div class="sec-head center reveal">
      <span class="eyebrow">Clients</span>
      <h2>Trusted by industry leaders.</h2>
      <p>Our clients and our people &mdash; in particular their health, safety and quality &mdash; are the primary focus of all Tegrach Nigeria Limited activity.</p>
    </div>
    <div class="client-grid">
      <div class="client-cell reveal"><span>Lee Engineering &amp; Const. Co. Ltd</span></div>
      <div class="client-cell reveal d1"><span>Heritage Oil</span></div>
      <div class="client-cell reveal d2"><span>NDDC</span></div>
      <div class="client-cell reveal d3"><span>NNPC</span></div>
      <div class="client-cell reveal"><span>Central Bank of Nigeria</span></div>
      <div class="client-cell reveal d1"><span>Falcon</span></div>
      <div class="client-cell reveal d2"><span>Nestoil</span></div>
      <div class="client-cell reveal d3"><span>Daewoo Engineering &amp; Construction</span></div>
      <div class="client-cell reveal"><span>SAIPEM</span></div>
      <div class="client-cell reveal d1"><span>LEVANT</span></div>
      <div class="client-cell reveal d2"><span>CCECC</span></div>
      <div class="client-cell reveal d3"><span>JBN</span></div>
    </div>
  </div>
</section>

<!-- ============ PARTNERS ============ -->
<section class="block" id="partners">
  <div class="wrap">
    <div class="sec-head center reveal">
      <span class="eyebrow">Partners</span>
      <h2>In consortium with global firms.</h2>
      <p>Tegrach works in cooperation with foreign O&amp;M, OEM and EPC partners &mdash; a platform of expertise and technology transfer that strengthens local Nigerian capability.</p>
    </div>
  </div>
  <div class="marquee reveal">
    <div class="track">
      <div class="pcell"><img src="/assets/partners/partner1.jpg" alt="Partner logo" loading="lazy"></div>
      <div class="pcell"><img src="/assets/partners/partner2.jpg" alt="Partner logo" loading="lazy"></div>
      <div class="pcell"><img src="/assets/partners/partner3.jpg" alt="Partner logo" loading="lazy"></div>
      <div class="pcell"><img src="/assets/partners/partner4.jpg" alt="Partner logo" loading="lazy"></div>
      <div class="pcell"><img src="/assets/partners/partner5.jpg" alt="Partner logo" loading="lazy"></div>
      <div class="pcell"><img src="/assets/partners/partner6.jpg" alt="Partner logo" loading="lazy"></div>
      <div class="pcell"><img src="/assets/partners/partner7.jpg" alt="Partner logo" loading="lazy"></div>
      <div class="pcell"><img src="/assets/partners/partner8.jpg" alt="Partner logo" loading="lazy"></div>
      <div class="pcell"><img src="/assets/partners/partner9.jpg" alt="Partner logo" loading="lazy"></div>
      <div class="pcell"><img src="/assets/partners/partner1.jpg" alt="Partner logo" loading="lazy"></div>
      <div class="pcell"><img src="/assets/partners/partner2.jpg" alt="Partner logo" loading="lazy"></div>
      <div class="pcell"><img src="/assets/partners/partner3.jpg" alt="Partner logo" loading="lazy"></div>
      <div class="pcell"><img src="/assets/partners/partner4.jpg" alt="Partner logo" loading="lazy"></div>
      <div class="pcell"><img src="/assets/partners/partner5.jpg" alt="Partner logo" loading="lazy"></div>
      <div class="pcell"><img src="/assets/partners/partner6.jpg" alt="Partner logo" loading="lazy"></div>
      <div class="pcell"><img src="/assets/partners/partner7.jpg" alt="Partner logo" loading="lazy"></div>
      <div class="pcell"><img src="/assets/partners/partner8.jpg" alt="Partner logo" loading="lazy"></div>
      <div class="pcell"><img src="/assets/partners/partner9.jpg" alt="Partner logo" loading="lazy"></div>
    </div>
  </div>
</section>

<!-- ============ CTA BAND ============ -->
<section class="cta-band">
  <div class="super"><svg viewBox="0 0 96 64"><g fill="currentColor" transform="skewX(-11)"><polygon points="10,6 50,6 50,18 38,18 38,58 22,58 22,18 10,18"/><path d="M54,6 H86 V18 H66 V46 H74 V36 H86 V58 H54 Z"/></g></svg></div>
  <div class="wrap">
    <h2 class="reveal">Have a project? Let's build it right.</h2>
    <a href="/contact" class="btn btn-orange reveal d1"><span>Request a Quote</span></a>
  </div>
</section>

<?php include __DIR__ . "/includes/footer.php"; ?>
