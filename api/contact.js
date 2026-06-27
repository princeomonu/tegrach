/*
 * Vercel Serverless Function — POST /api/contact (the contact form).
 * Validates, checks honeypot + Cloudflare Turnstile, then emails via Resend.
 *
 * Environment variables (Vercel → Project → Settings → Environment Variables):
 *   RESEND_API_KEY         Resend key (re_...)            [required]
 *   CONTACT_TO             recipient address              [required]
 *   CONTACT_FROM           verified Resend sender         [required]
 *   TURNSTILE_SECRET_KEY   Turnstile secret               [optional — enables verification]
 *   SITE_URL               e.g. https://tegrach-nigeria.com [optional]
 */

const esc = (s) =>
  String(s ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
const nl2br = (s) => esc(s).replace(/\r?\n/g, '<br>');

function bufToBase64(buf) {
  return Buffer.from(buf).toString('base64');
}

function buildEmailHtml(d, siteUrl, logo) {
  const orange = '#FD6600';
  const ink = '#161616';
  const font = "'Segoe UI',Tahoma,Geneva,Verdana,Arial,sans-serif";
  logo = logo || `${siteUrl}/assets/tegrach/favicon-64.png`;

  const row = (label, value, accent = false) => {
    if (!value || !String(value).trim()) return '';
    const valStyle = accent
      ? `font-family:${font};font-size:15px;color:${orange};font-weight:700`
      : `font-family:${font};font-size:15px;color:#161616`;
    return (
      '<tr>' +
      `<td style="padding:13px 16px;border-bottom:1px solid #ececec;background:#fafafa;` +
      `font-family:${font};font-size:11px;letter-spacing:.08em;text-transform:uppercase;` +
      `color:#8a8a8a;font-weight:700;white-space:nowrap;vertical-align:top;width:120px">${esc(label)}</td>` +
      `<td style="padding:13px 18px;border-bottom:1px solid #ececec;${valStyle}">${nl2br(value)}</td>` +
      '</tr>'
    );
  };

  return (
    '<!DOCTYPE html><html><head><meta charset="utf-8">' +
    '<meta name="viewport" content="width=device-width,initial-scale=1"></head>' +
    '<body style="margin:0;padding:0;background:#f4f4f2">' +
    '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f2;padding:28px 12px">' +
    '<tr><td align="center">' +
    '<table role="presentation" width="600" cellpadding="0" cellspacing="0" ' +
    'style="width:600px;max-width:100%;background:#ffffff;border:1px solid #e6e6e6">' +
    `<tr><td style="height:5px;background:${orange};font-size:0;line-height:0">&nbsp;</td></tr>` +
    `<tr><td style="background:${ink};padding:26px 28px">` +
    `<img src="${esc(logo)}" alt="Tegrach Nigeria Limited" width="44" height="44" ` +
    'style="display:block;border:0;width:44px;height:44px">' +
    `<p style="margin:14px 0 0;font-family:${font};font-size:12px;letter-spacing:.14em;` +
    `text-transform:uppercase;color:${orange};font-weight:700">New Website Enquiry</p>` +
    '</td></tr>' +
    '<tr><td style="padding:26px 28px 6px">' +
    `<p style="margin:0;font-family:${font};font-size:15px;line-height:1.55;color:#444">` +
    'A new enquiry was submitted through the contact form on ' +
    `<a href="${siteUrl}" style="color:${orange};text-decoration:none">tegrach-nigeria.com</a>.</p>` +
    '</td></tr>' +
    '<tr><td style="padding:14px 28px 4px">' +
    '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" ' +
    'style="border:1px solid #ececec;border-collapse:collapse">' +
    row('Name', d.name) +
    row('Company', d.company) +
    row('Email', d.email) +
    row('Phone', d.phone) +
    row('Service', d.service, true) +
    row('Message', d.message) +
    '</table></td></tr>' +
    `<tr><td style="background:${ink};padding:22px 28px">` +
    `<p style="margin:0;font-family:${font};font-size:13px;line-height:1.6;color:#bdbdbd">` +
    '<strong style="color:#fff">Tegrach Nigeria Limited</strong><br>' +
    'Plot 3 Desney Street, Off Refinery Road, Warri, Delta State.<br>' +
    '+234 703 915 3029 &nbsp;&middot;&nbsp; contact@tegrach-nigeria.com</p>' +
    `<p style="margin:14px 0 0;font-family:${font};font-size:11px;color:#7a7a7a">` +
    'RC 991748 &nbsp;|&nbsp; ISO 9001:2015 &nbsp;|&nbsp; Reply directly to this email to respond to the sender.</p>' +
    '</td></tr>' +
    '</table></td></tr></table></body></html>'
  );
}

export default async function handler(req, res) {
  if (req.method !== 'POST') {
    res.setHeader('Allow', 'POST');
    return res.status(405).json({ ok: false, error: 'Method not allowed.' });
  }

  const b = req.body || {};
  const get = (k) => (b[k] == null ? '' : String(b[k]).trim());

  // Honeypot — silently accept and drop.
  if (get('website') !== '') {
    return res.status(200).json({ ok: true });
  }

  const d = {
    name: get('name'),
    company: get('company'),
    email: get('email'),
    phone: get('phone'),
    service: get('service'),
    message: get('message'),
  };

  const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(d.email);
  if (!d.name || !emailOk) {
    const need = [];
    if (!d.name) need.push('your name');
    if (!emailOk) need.push('a valid email address');
    return res.status(422).json({ ok: false, error: 'Please provide ' + need.join(' and ') + '.' });
  }

  // Cloudflare Turnstile — verify only when a secret is configured.
  if (process.env.TURNSTILE_SECRET_KEY) {
    const token = get('cf-turnstile-response');
    if (!token) {
      return res.status(422).json({ ok: false, error: 'Please complete the verification challenge.' });
    }
    try {
      const ip = (req.headers['x-forwarded-for'] || '').split(',')[0].trim() || req.headers['x-real-ip'];
      const verify = await fetch('https://challenges.cloudflare.com/turnstile/v0/siteverify', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ secret: process.env.TURNSTILE_SECRET_KEY, response: token, remoteip: ip || undefined }),
      });
      const result = await verify.json();
      if (!result.success) {
        console.log('Turnstile failed', result['error-codes']);
        return res.status(403).json({ ok: false, error: 'Verification failed. Please try again.' });
      }
    } catch (err) {
      console.log('Turnstile verify error', err);
      return res.status(502).json({ ok: false, error: 'Could not verify the challenge. Please try again.' });
    }
  }

  const missing = ['RESEND_API_KEY', 'CONTACT_TO', 'CONTACT_FROM'].filter((k) => !process.env[k]);
  if (missing.length) {
    console.log('Missing env vars:', missing.join(', '));
    return res.status(500).json({ ok: false, error: 'Email service is not configured. Missing: ' + missing.join(', ') });
  }

  const proto = req.headers['x-forwarded-proto'] || 'https';
  const host = req.headers['x-forwarded-host'] || req.headers.host;
  const siteUrl = process.env.SITE_URL || `${proto}://${host}`;

  // Inline the logo (CID) by fetching it from this deployment.
  let logoSrc = `${siteUrl}/assets/tegrach/favicon-64.png`;
  let attachments;
  try {
    const r = await fetch(`${siteUrl}/assets/tegrach/favicon-64.png`);
    if (r.ok) {
      attachments = [
        {
          filename: 'favicon-64.png',
          content: bufToBase64(await r.arrayBuffer()),
          content_id: 'tegrachlogo',
          content_type: 'image/png',
        },
      ];
      logoSrc = 'cid:tegrachlogo';
    }
  } catch (err) {
    console.log('Logo embed failed, using URL', err);
  }

  const subject =
    'Website enquiry' + (d.service ? ': ' + d.service : '') + (d.name ? ' — ' + d.name : '');
  const text =
    `New website enquiry\n\nName: ${d.name}\n` +
    (d.company ? `Company: ${d.company}\n` : '') +
    `Email: ${d.email}\n` +
    (d.phone ? `Phone: ${d.phone}\n` : '') +
    (d.service ? `Service: ${d.service}\n` : '') +
    `\nMessage:\n${d.message}\n`;

  const payload = {
    from: process.env.CONTACT_FROM,
    to: [process.env.CONTACT_TO],
    reply_to: d.email,
    subject,
    text,
    html: buildEmailHtml(d, siteUrl, logoSrc),
  };
  if (attachments) payload.attachments = attachments;

  try {
    const r = await fetch('https://api.resend.com/emails', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${process.env.RESEND_API_KEY}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(payload),
    });
    if (!r.ok) {
      console.log('Resend error', r.status, await r.text());
      return res.status(502).json({ ok: false, error: 'The email service rejected the request. Please try again later.' });
    }
  } catch (err) {
    console.log('Resend fetch failed', err);
    return res.status(502).json({ ok: false, error: 'Could not reach the email service. Please try again.' });
  }

  return res.status(200).json({ ok: true });
}
