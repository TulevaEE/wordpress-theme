/*
 * Remembers where a visitor came from (UTM tags, ad click id, outside site, landing page) in the
 * tuleva_attribution cookie on .tuleva.ee, shared both ways with the app at pension.tuleva.ee, which
 * sends it to Tuleva's own analytics when the person logs in. First touch and last touch, 90 days.
 * Only with analytics consent (CookieYes).
 */
(function (root) {
  var COOKIE = 'tuleva_attribution';
  var MAX_AGE_MS = 90 * 24 * 60 * 60 * 1000;
  var TRANSIT_HOSTS = /(^|\.)(tuleva\.ee|montonio\.com|smart-id\.com|sk\.ee|eid\.ee)$/i;

  function clip(value) {
    return value === null ? null : String(value).slice(0, 100);
  }

  function referrerHost(referrer) {
    try {
      var host = referrer ? new URL(referrer).hostname : '';
      return host && !TRANSIT_HOSTS.test(host) ? host : null;
    } catch (e) {
      return null;
    }
  }

  function touchFromPage(url, referrer, now) {
    var params = url.searchParams;
    var utm = function (name) { return clip(params.get('utm_' + name)); };
    var source = utm('source');
    if (source === 'e2e') return null;
    var clickId = params.has('gclid') ? 'gclid' : (params.has('fbclid') ? 'fbclid' : null);
    var host = referrerHost(referrer);
    if (!source && !utm('medium') && !utm('campaign') && !clickId && !host) return null;
    return {
      source: source, medium: utm('medium'), campaign: utm('campaign'), content: utm('content'),
      term: utm('term'), clickId: clickId, referrerHost: host, landingPath: url.pathname,
      at: now.toISOString()
    };
  }

  function isTouch(value) {
    return !!value && typeof value.at === 'string' && typeof value.landingPath === 'string';
  }

  function readCookie(cookieString) {
    var entry = (cookieString || '').split('; ').filter(function (c) { return c.indexOf(COOKIE + '=') === 0; })[0];
    if (!entry) return null;
    try {
      var value = JSON.parse(decodeURIComponent(entry.slice(COOKIE.length + 1)));
      return value && isTouch(value.first) && isTouch(value.last) ? value : null;
    } catch (e) {
      return null;
    }
  }

  function merge(existing, touch, now) {
    var fresh = function (t) { return isTouch(t) && now.getTime() - Date.parse(t.at) < MAX_AGE_MS; };
    var touches = [existing && existing.first, existing && existing.last, touch].filter(fresh);
    if (!touches.length) return null;
    touches.sort(function (a, b) { return a.at < b.at ? -1 : (a.at > b.at ? 1 : 0); });
    return { first: touches[0], last: touches[touches.length - 1] };
  }

  function hasAnalyticsConsent(cookieString) {
    return /(^|; )cookieyes-consent=[^;]*analytics:yes/.test(cookieString || '');
  }

  function capture(doc, location, now) {
    if (!hasAnalyticsConsent(doc.cookie)) return false;
    var touch = touchFromPage(new URL(location.href), doc.referrer, now);
    var existing = readCookie(doc.cookie);
    if (!touch) return false;
    var attribution = merge(existing, touch, now);
    if (!attribution) return false;
    var domain = /(^|\.)tuleva\.ee$/.test(location.hostname) ? '; domain=.tuleva.ee; Secure' : '';
    doc.cookie = COOKIE + '=' + encodeURIComponent(JSON.stringify(attribution)) +
      '; max-age=' + Math.round(MAX_AGE_MS / 1000) + '; path=/; SameSite=Lax' + domain;
    return true;
  }

  if (typeof module !== 'undefined' && module.exports) {
    module.exports = { touchFromPage: touchFromPage, merge: merge, hasAnalyticsConsent: hasAnalyticsConsent, capture: capture, readCookie: readCookie };
  } else if (root.document) {
    capture(root.document, root.location, new Date());
    // A first-time visitor consents after the page has loaded
    root.document.addEventListener('cookieyes_consent_update', function () {
      capture(root.document, root.location, new Date());
    });
  }
})(typeof window !== 'undefined' ? window : this);
