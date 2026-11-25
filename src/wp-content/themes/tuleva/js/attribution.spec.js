const { touchFromPage, merge, hasAnalyticsConsent, capture, readCookie } = require('./attribution');

const now = new Date('2026-11-25T10:00:00Z');

describe('website attribution', () => {
  test('reads UTM tags and the ad click id', () => {
    const touch = touchFromPage(new URL('https://tuleva.ee/lapsele-kogumine/?utm_source=meta&utm_medium=paid_social&utm_campaign=lapsele&fbclid=1'), '', now);
    expect(touch).toMatchObject({ source: 'meta', medium: 'paid_social', campaign: 'lapsele', clickId: 'fbclid', landingPath: '/lapsele-kogumine/' });
  });

  test('counts an outside site, not our own sites or the payment page', () => {
    const page = new URL('https://tuleva.ee/iii-sammas/');
    expect(touchFromPage(page, 'https://www.google.com/', now).referrerHost).toBe('www.google.com');
    expect(touchFromPage(page, 'https://pension.tuleva.ee/account', now)).toBeNull();
    expect(touchFromPage(page, 'https://paymentsv2.montonio.com/', now)).toBeNull();
  });

  test('keeps the first touch and moves the last one', () => {
    const first = { landingPath: '/', at: '2026-11-01T10:00:00.000Z', source: 'google' };
    const last = { landingPath: '/iii-sammas/', at: '2026-11-25T10:00:00.000Z', source: 'meta' };
    expect(merge({ first, last: first }, last, now)).toEqual({ first, last });
  });

  test('sets the cookie only with analytics consent', () => {
    const doc = { cookie: '', referrer: 'https://www.google.com/' };
    const location = { href: 'https://tuleva.ee/iii-sammas/', hostname: 'tuleva.ee' };
    expect(capture(doc, location, now)).toBe(false);

    expect(hasAnalyticsConsent('cookieyes-consent=consent:yes,analytics:yes,advertisement:no')).toBe(true);
    let written = '';
    const consented = {
      referrer: 'https://www.google.com/',
      get cookie() { return 'cookieyes-consent=consent:yes,analytics:yes'; },
      set cookie(value) { written = value; },
    };
    expect(capture(consented, location, now)).toBe(true);
    expect(written).toContain('domain=.tuleva.ee');
    expect(readCookie(written.split(';')[0]).last.referrerHost).toBe('www.google.com');
  });
});
