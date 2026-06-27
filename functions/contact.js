/*
 * Cloudflare Pages Function — handles POST /contact (the contact form).
 * GET /contact falls through to the static contact.html (only POST is defined).
 *
 * Required environment variables (Pages → Settings → Environment variables):
 *   RESEND_API_KEY   your Resend key (re_...)
 *   CONTACT_TO       recipient, e.g. contact@tegrach-nigeria.com  (set a dev
 *                    value like developer@tegrach-nigeria.com in the Preview env)
 *   CONTACT_FROM     verified sender, e.g. "Tegrach Nigeria <website@tegrach-nigeria.com>"
 * Optional:
 *   SITE_URL         defaults to https://tegrach-nigeria.com (used for the logo)
 */

const json = (obj, status = 200) =>
  new Response(JSON.stringify(obj), {
    status,
    headers: { 'Content-Type': 'application/json' },
  });

const esc = (s) =>
  String(s ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

const nl2br = (s) => esc(s).replace(/\r?\n/g, '<br>');

function buildEmailHtml(d, siteUrl) {
  const orange = '#FD6600';
  const ink = '#161616';
  const font = "'Segoe UI',Tahoma,Geneva,Verdana,Arial,sans-serif";
  const logo = `${siteUrl}/assets/tegrach/favicon-64.png`;

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

export async function onRequestPost({ request, env }) {
  let form;
  try {
    form = await request.formData();
  } catch {
    return json({ ok: false, error: 'Invalid submission.' }, 400);
  }

  const get = (k) => (form.get(k) || '').toString().trim();

  // Honeypot — silently accept and drop.
  if (get('website') !== '') {
    return json({ ok: true });
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
    return json({ ok: false, error: 'Please provide ' + need.join(' and ') + '.' }, 422);
  }

  const missing = ['RESEND_API_KEY', 'CONTACT_TO', 'CONTACT_FROM'].filter((k) => !env[k]);
  if (missing.length) {
    console.log('Missing env vars:', missing.join(', '));
    return json(
      { ok: false, error: 'Email service is not configured. Missing: ' + missing.join(', ') },
      500
    );
  }

  const siteUrl = env.SITE_URL || 'https://tegrach-nigeria.com';
  const subject =
    'Website enquiry' +
    (d.service ? ': ' + d.service : '') +
    (d.name ? ' — ' + d.name : '');

  const text =
    `New website enquiry\n\nName: ${d.name}\n` +
    (d.company ? `Company: ${d.company}\n` : '') +
    `Email: ${d.email}\n` +
    (d.phone ? `Phone: ${d.phone}\n` : '') +
    (d.service ? `Service: ${d.service}\n` : '') +
    `\nMessage:\n${d.message}\n`;

  const payload = {
    from: env.CONTACT_FROM,
    to: [env.CONTACT_TO],
    reply_to: d.email,
    subject,
    text,
    html: buildEmailHtml(d, siteUrl),
  };

  try {
    const res = await fetch('https://api.resend.com/emails', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${env.RESEND_API_KEY}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(payload),
    });

    if (!res.ok) {
      const body = await res.text();
      console.log('Resend error', res.status, body);
      return json({ ok: false, error: 'The email service rejected the request. Please try again later.' }, 502);
    }
  } catch (err) {
    console.log('Resend fetch failed', err);
    return json({ ok: false, error: 'Could not reach the email service. Please try again.' }, 502);
  }

  return json({ ok: true });
}
