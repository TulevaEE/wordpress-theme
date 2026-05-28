<main id="main" class="page-container section-spacing">
<style>
  .fc-sources {
    --blue: #2563eb; --navy: #013063; --g50: #f9fafb; --g100: #f3f4f6; --g200: #e5e7eb;
    --g400: #9ca3af; --g600: #4b5563; --g800: #1f2937; --g900: #111827;
    max-width: 960px;
    margin: 0 auto;
    padding: 0 1.5rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    color: var(--g800);
    line-height: 1.6;
  }
  .fc-sources * { box-sizing: border-box; }
  .fc-sources h1 { font-size: 1.75rem; font-weight: 800; margin-bottom: 0.5rem; }
  .fc-sources .subtitle { font-size: 1rem; color: var(--g600); margin-bottom: 2rem; }
  .fc-sources h2 { font-size: 1.2rem; font-weight: 700; margin: 2.5rem 0 0.75rem; color: var(--navy); }
  .fc-sources h2:first-of-type { margin-top: 0; }
  .fc-sources .back-link { display: inline-flex; align-items: center; gap: 4px; font-size: 0.85rem;
               color: var(--blue); font-weight: 600; text-decoration: none; margin-bottom: 1.5rem; }
  .fc-sources .back-link:hover { text-decoration: underline; }
  .fc-sources a { color: var(--blue); }
  .fc-sources table { width: 100%; border-collapse: collapse; font-size: 0.85rem; margin-bottom: 1.5rem; }
  .fc-sources th, .fc-sources td { padding: 8px 12px; text-align: left; border-bottom: 1px solid var(--g200); }
  .fc-sources th { background: var(--g50); font-weight: 600; color: var(--g600); font-size: 0.8rem;
       text-transform: uppercase; letter-spacing: 0.03em; }
  .fc-sources tr:hover { background: var(--g50); }
  .fc-sources .tag { display: inline-block; font-size: 0.7rem; padding: 2px 6px; border-radius: 4px;
         font-weight: 600; }
  .fc-sources .tag-full { background: #dcfce7; color: #166534; }
  .fc-sources .tag-proxy { background: #fef3c7; color: #92400e; }
  .fc-sources .tag-opaque { background: #fee2e2; color: #991b1b; }
  .fc-sources .note { font-size: 0.88rem; color: var(--g600); line-height: 1.7; margin-bottom: 1rem; }
  .fc-sources .example-box { background: var(--g50); border: 1px solid var(--g200); border-radius: 8px;
                 padding: 1rem 1.2rem; margin: 1rem 0 1.5rem; font-size: 0.88rem; line-height: 1.7; }
  .fc-sources .formula { font-family: Georgia, 'Times New Roman', serif; font-size: 1rem; font-weight: 700;
             color: var(--navy); display: block; margin: 0.75rem 0; text-align: center; }
  .fc-sources .warn-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px;
              padding: 1rem 1.2rem; margin-bottom: 2rem; font-size: 0.85rem; line-height: 1.7; }
  .fc-sources .fc-footer { font-size: 0.8rem; color: var(--g600); border-top: 1px solid var(--g200);
            padding-top: 1.5rem; margin-top: 2rem; }
  @media (max-width: 640px) {
    .fc-sources table { font-size: 0.78rem; }
    .fc-sources th, .fc-sources td { padding: 6px 8px; }
  }
</style>

<div class="fc-sources">
<a href="<?php echo home_url('/fondide-vordlus/'); ?>" class="back-link">&larr; Tagasi fondide juurde</a>
<h1>Andmeallikad ja metoodika</h1>
<p class="subtitle">Kuidas andmed on kogutud, kuidas kattuvust arvutatakse ja kust numbrid tulevad.</p>

<h2>Kuidas me kattuvust m&otilde;&otilde;dame?</h2>

<div class="note">
  <p>Fondide kattuvus on arvutatud valemiga <strong>1 &minus; Active Share</strong>, kus:</p>
  <span class="formula">Active Share = &frac12; &times; &Sigma; |kaal<sub>A</sub>[i] &minus; kaal<sub>B</sub>[i]|</span>
  <span class="formula">Kattuvus = 1 &minus; Active Share = &Sigma; min(kaal<sub>A</sub>[i], kaal<sub>B</sub>[i])</span>
  <p>Active Share m&otilde;&otilde;dab, kui suur osa portfellist erineb v&otilde;rdlusalusest.
  Kattuvus on Active Share&rsquo;i peegelpilt: see n&auml;itab, mitu protsenti m&otilde;lema fondi rahast
  l&auml;heb t&auml;pselt samadesse investeeringutesse.</p>
  <p style="margin-top:0.5rem;font-size:0.82rem;color:var(--g600);">Jagamine kahega v&auml;ldib topeltloendust:
  kui Nvidia on &uuml;hes fondis 1% &uuml;lekaalus, peab midagi muud olema 1% alakaalus &mdash;
  see on sama erinevus, mitte kaks erinevat.</p>
  <p style="margin-top:0.75rem;">Active Share&rsquo;i t&ouml;&ouml;tasid v&auml;lja Cremers ja Petajisto (2009).
  Originaalvalem m&otilde;&otilde;dab fondi erinevust v&otilde;rdlusindeksist.
  Meie kasutame sama valemit kahe fondi v&otilde;rdlemiseks, v&otilde;ttes &uuml;ht fondi &ldquo;indeksina&rdquo;.
  Kuna valem on s&uuml;mmeetriline (|w<sub>A</sub> &minus; w<sub>B</sub>| = |w<sub>B</sub> &minus; w<sub>A</sub>|), pole vahet, kumba fondi indeksiks v&otilde;tta.</p>
</div>

<div class="example-box">
  <strong>N&auml;ide:</strong><br>
  Fond A: Nvidia 33%, Apple 33%, Microsoft 33%<br>
  Fond B: Nvidia 50%, Apple 50%<br><br>
  Active Share = &frac12; &times; (|33&minus;50| + |33&minus;50| + |33&minus;0|) = &frac12; &times; (17+17+33) = <strong>33.5%</strong><br>
  Kattuvus = 100% &minus; 33.5% = <strong>66.5%</strong><br><br>
  Kontroll: min(33,50) + min(33,50) + min(33,0) = 33 + 33 + 0 = <strong>66%</strong><br>
  <span style="font-size:0.82rem;color:var(--g400);">(V&auml;ike &uuml;marduserinevus, kuna fondid ei summeeru t&auml;pselt 100%-ni.)</span>
</div>

<div class="note">
  <p><strong>Miks mitte lihtsam meetod?</strong>
  Lihtne arvup&otilde;hine kattuvus (&ldquo;245 &uuml;hist aktsiat 1952-st&rdquo;)
  ei arvesta kaalusid &mdash; 0,01% positsioon loeb sama palju kui 5% positsioon.
  Active Share arvestab tegelikke rahapaigutusi ja on akadeemilises kirjanduses laialdaselt kasutatud.</p>

  <p style="margin-top:0.75rem;"><strong>Viited:</strong></p>
  <ul style="padding-left:1.2rem;">
    <li>Cremers, K.J.M. &amp; Petajisto, A. (2009).
      <a href="https://doi.org/10.1093/rfs/hhp057">&ldquo;How Active Is Your Fund Manager? A New Measure That Predicts Performance.&rdquo;</a>
      <em>The Review of Financial Studies</em>, 22(9), 3329&ndash;3365.</li>
    <li>Petajisto, A. (2013).
      <a href="https://doi.org/10.2469/faj.v69.n4.7">&ldquo;Active Share and Mutual Fund Performance.&rdquo;</a>
      <em>Financial Analysts Journal</em>, 69(4), 73&ndash;93.</li>
  </ul>
</div>

<h2>Kuidas andmed on kogutud?</h2>
<div class="note">
  <ol style="padding-left:1.2rem;">
    <li><strong>Investeerimisaruanded.</strong> Iga fondi kohta laadisime alla fondivalitseja v&otilde;i Pensionikeskuse
        investeerimisaruande (PDF) seisuga jaanuar 2026. Aruandest lugesime v&auml;lja fondi positsioonid:
        ETF-id, aktsiad, v&otilde;lakirjad, kinnisvara- ja erakapitalifondid koos osakaaludega.
        Lingid aruannetele on allpool tabelis.</li>
    <li><strong>ETF-ide l&auml;bivaatamine (look-through).</strong> Indeksfondid investeerivad l&auml;bi ETF-ide.
        Iga ETF-i koosseisu saime avalikest allikatest (iShares CSV-failid, Swedbank Robur aastaaruanded).
        Kui ETF-i t&auml;pne koosseis polnud k&auml;ttesaadav (nt L&amp;G, SPDR), kasutasime sarnast iShares ETF-i l&auml;hendusena.
        SEB sisefondide kohta kasutasime EODHD API-t.</li>
    <li><strong>&Uuml;htne andmetabel.</strong> K&otilde;igi fondide positsioonid koondati &uuml;htsesse tabelisse, kus iga aktsia
        on identifitseeritud ISIN-koodi j&auml;rgi. See v&otilde;imaldab MWO arvutust ja muid v&otilde;rdlusi.</li>
    <li><strong>NAV ajalugu ja tasud.</strong>
        <a href="https://www.pensionikeskus.ee/statistika/ii-sammas/kogumispensioni-paevastatistika/">Pensionikeskuse statistikast</a>.</li>
  </ol>
</div>

<h2>Allikad</h2>

<div class="note">
  <p style="margin-bottom:0.5rem;"><strong>Fondide investeerimisaruanded (jaanuar 2026):</strong></p>
  <ul style="padding-left:1.2rem;margin-bottom:1rem;">
    <li><strong>Tuleva:</strong>
      <a href="https://www.pensionikeskus.ee/files/raport/TUK75/est_TUK75_raport_20260130.pdf">Tuleva Maailma Aktsiad</a>,
      <a href="https://www.pensionikeskus.ee/files/raport/TUK00/est_TUK00_raport_20260130.pdf">Tuleva V&otilde;lakirjad</a></li>
    <li><strong>Luminor:</strong>
      <a href="https://www.pensionikeskus.ee/files/raport/NPK75/est_NPK75_raport_20260131.pdf">Luminor 16-50</a>,
      <a href="https://www.pensionikeskus.ee/files/raport/NIK100/est_NIK100_raport_20260131.pdf">Luminor Indeks</a>,
      <a href="https://www.pensionikeskus.ee/files/raport/NPK50/est_NPK50_raport_20260131.pdf">Luminor 50-56</a>,
      <a href="https://www.pensionikeskus.ee/files/raport/NPK25/est_NPK25_raport_20260131.pdf">Luminor 56+</a>,
      <a href="https://www.pensionikeskus.ee/files/raport/NPK00/est_NPK00_raport_20260131.pdf">Luminor 61-65</a></li>
    <li><strong>SEB:</strong>
      <a href="https://www.pensionikeskus.ee/files/raport/SIK75/est_SIK75_raport_20260131.pdf">SEB Indeks</a>,
      <a href="https://www.pensionikeskus.ee/files/raport/SEK100/est_SEK100_raport_20260131.pdf">SEB 18+</a>,
      <a href="https://www.pensionikeskus.ee/files/raport/SEK50/est_SEK50_raport_20260131.pdf">SEB 55+</a>,
      <a href="https://www.pensionikeskus.ee/files/raport/SEK25/est_SEK25_raport_20260131.pdf">SEB 60+</a>,
      <a href="https://www.pensionikeskus.ee/files/raport/SEK00/est_SEK00_raport_20260131.pdf">SEB 65+</a></li>
    <li><strong>Swedbank:</strong>
      <a href="https://swedbank.ee/static/investor/funds/Ki_investment_portfolio.pdf">Swedbank Indeks</a>,
      <a href="https://swedbank.ee/static/investor/funds/K1960_investment_portfolio.pdf">K1960</a>,
      <a href="https://swedbank.ee/static/investor/funds/K1970_investment_portfolio.pdf">K1970</a>,
      <a href="https://swedbank.ee/static/investor/funds/K1980_investment_portfolio.pdf">K1980</a>,
      <a href="https://swedbank.ee/static/investor/funds/K1990_investment_portfolio.pdf">K1990</a>,
      <a href="https://swedbank.ee/static/investor/funds/K2000_investment_portfolio.pdf">2000-09</a>,
      <a href="https://www.swedbank.ee/static/investor/funds/KKONS_investment_portfolio.pdf">Konservatiivne</a></li>
    <li><strong>LHV:</strong>
      <a href="https://www.pensionikeskus.ee/files/raport/LIK75/est_LIK75_raport_20260131.pdf">LHV Indeks</a>,
      <a href="https://www.pensionikeskus.ee/files/raport/LLK50/est_LLK50_raport_20260131.pdf">LHV Ettev&otilde;tlik</a>,
      <a href="https://www.pensionikeskus.ee/files/raport/LXK75/est_LXK75_raport_20260131.pdf">LHV Julge</a>,
      <a href="https://www.pensionikeskus.ee/files/raport/LXK00/est_LXK00_raport_20260131.pdf">LHV Rahulik</a>,
      <a href="https://www.pensionikeskus.ee/files/raport/LMK25/est_LMK25_raport_20260131.pdf">LHV Tasakaalukas</a></li>
  </ul>
  <p style="margin-bottom:0.5rem;"><strong>ETF-ide koosseisud:</strong></p>
  <ul style="padding-left:1.2rem;margin-bottom:1rem;">
    <li><a href="https://www.ishares.com/uk/individual/en/products/305419/">iShares MSCI World ESG Screened (SAWD)</a></li>
    <li><a href="https://www.ishares.com/uk/individual/en/products/305356/">iShares MSCI USA ESG Screened (SASU)</a></li>
    <li><a href="https://www.ishares.com/uk/individual/en/products/305363/">iShares MSCI Europe ESG Screened (SAEU)</a></li>
    <li><a href="https://www.ishares.com/uk/individual/en/products/305412/">iShares MSCI Japan ESG Screened (SAJP)</a></li>
    <li><a href="https://www.ishares.com/uk/individual/en/products/305397/">iShares MSCI EM IMI ESG Screened (SAEM)</a></li>
    <li><a href="https://www.ishares.com/uk/individual/en/products/251850/">iShares MSCI ACWI (SSAC)</a> (v&otilde;rdlusalus)</li>
    <li><a href="https://www.ishares.com/uk/individual/en/products/297617/">iShares MSCI India (NDIA)</a></li>
    <li><a href="https://www.ishares.com/uk/individual/en/products/304304/">iShares MSCI Brazil (4BRZ)</a></li>
    <li><a href="https://www.ishares.com/uk/individual/en/products/273192/">iShares MSCI China A (CNYA)</a></li>
    <li><a href="https://www.ishares.com/uk/individual/en/products/279996/">iShares MSCI Saudi Arabia (IKSA)</a></li>
    <li><a href="https://www.ssga.com/uk/en_gb/institutional/etfs/funds/spdr-sp-500-esg-leaders-ucits-etf-acc-zprs">SPDR S&amp;P 500 ESG Leaders (SPPY)</a></li>
    <li>Xtrackers MSCI Japan ESG Screened (XTJP) (aastaaruandest)</li>
    <li>Swedbank Robur Globalfond A (GLOBALFOND_A) &mdash; top 30 positsiooni fondlista.se-st</li>
    <li style="list-style:none;margin-top:0.5rem;font-size:0.82rem;color:#6b7280;">T&auml;psed aktsiate arvud on allpool tabelis &ldquo;ETF-ide l&auml;bivaatamine&rdquo;.</li>
  </ul>
  <p style="margin-bottom:0.5rem;"><strong>Muud:</strong></p>
  <ul style="padding-left:1.2rem;">
    <li><a href="https://www.pensionikeskus.ee/statistika/ii-sammas/kogumispensioni-paevastatistika/">Pensionikeskus</a> &mdash; NAV ajalugu, valitsemistasud</li>
    <li><a href="https://eodhd.com">EODHD</a> &mdash; ETF-ide koosseisud: Amundi MSCI EM Ex China (EMXU, 46 positsiooni), Amundi Euro Stoxx Banks (BNKE, 10 positsiooni)</li>
  </ul>
</div>

<h2>Fondide katvus (24 fondi)</h2>
<table>
  <thead>
    <tr>
      <th>Fond</th>
      <th>T&uuml;&uuml;p</th>
      <th>Kuup&auml;ev</th>
      <th>Aruanne</th>
      <th>Katvus</th>
    </tr>
  </thead>
  <tbody id="fundTable"></tbody>
</table>

<h2>ETF-ide l&auml;bivaatamine</h2>
<p class="note">
  Indeksfondid investeerivad l&auml;bi ETF-ide. Kasutame iShares CSV-sid, SPDR CSV-sid, EODHD API-t ja Swedbank Roburi
  avalikke andmeid, et n&auml;ha iga ETF-i sees olevaid aktsiaid. SEB sisefondid on l&auml;hendatud regionaalsete
  iShares ETF-idega.
</p>
<table>
  <thead>
    <tr>
      <th>ETF / fond</th>
      <th>Andmeallikas</th>
      <th>Aktsiate arv</th>
      <th>Staatus</th>
    </tr>
  </thead>
  <tbody id="etfTable"></tbody>
</table>

<h2>Proxy l&auml;hendused</h2>
<p class="note">
  Need fondid/ETF-id on l&auml;hendatud sarnase ETF-i andmetega, kuna t&auml;psed koosseisud pole avalikult k&auml;ttesaadavad.
</p>
<table>
  <thead>
    <tr>
      <th>ISIN</th>
      <th>L&auml;hendus (mapped to)</th>
      <th>Staatus</th>
    </tr>
  </thead>
  <tbody id="proxyTable"></tbody>
</table>

<h2>Piirangud</h2>
<div class="note">
  <ul style="padding-left:1.2rem;">
    <li><strong>SEB sisefondid</strong> (SEB Emerging Markets, Europe, Global Exposure) on l&auml;bipaistmatud.
        Need moodustavad ~28% SEB Indeksist. L&auml;hendusena kasutame vastavat iShares regiooni-ETF-i (nt SEB EM &rarr; SAEM).
        SEB 55+ ja SEB 18+ varem l&auml;bipaistmatud aktiivsed fondid (T Rowe Price, Morgan Stanley jt) on n&uuml;&uuml;d
        l&auml;hendatud l&auml;bi SAWD/SAEM, Amundi Euro Stoxx Banks BNKE aga t&auml;psete andmetega.</li>
    <li><strong>L&amp;G ETF-id</strong> on l&auml;hendatud iShares sarnaste indeksite p&otilde;hjal.
        SPDR S&amp;P 500 ESG Leaders on t&auml;psete andmetega (187 aktsiat SPDR CSV-st).</li>
    <li><strong>PE/RE fondid</strong> (LHV Ettev&otilde;tlik, LHV Julge) &mdash; n&auml;itame ainult fondi nime ja osakaalu.</li>
    <li><strong>SAEM piiratud:</strong> iShares MSCI EM IMI ESG Screened sisaldab ~2700 aktsiat, kuid anal&uuml;&uuml;sis kasutame ainult 1500 suurimat (kaalu j&auml;rgi). &Uuml;lej&auml;&auml;nud ~1200 v&auml;ikest positsiooni j&auml;etakse v&auml;lja.</li>
    <li><strong>V&otilde;lakirjafondid</strong> (Tuleva V&otilde;lakirjad, Swedbank Konservatiivne jt) &mdash;
        kattuvuse v&otilde;rdlus toimib v&otilde;lakirjafondide tasemel, mitte &uuml;ksikv&otilde;lakirjade tasemel.</li>
    <li><strong>Swedbank 2000-09</strong> investeerib ~71% l&auml;bi Swedbank Roburi fondide (koosseisud aastaaruannetest).</li>
    <li><strong>Aruannete kuup&auml;ev</strong> &mdash; andmed on jaanuari 2026 seisuga. Fondide koosseis muutub pidevalt.</li>
  </ul>
</div>

<div class="warn-box">
  <strong>&#9888; Vastutuse piirang.</strong>
  See leht on loodud informatiivsel eesm&auml;rgil. Andmed v&otilde;ivad sisaldada ebat&auml;psusi &mdash;
  kontrolli fakte fondivalitseja ametlikest allikatest. See ei ole investeerimisnõuanne.
  Loodud <a href="https://claude.ai/claude-code">Claude Code</a> abiga, k&auml;sitsi &uuml;le kontrollitud.
</div>

<div class="fc-footer">
  <a href="<?php echo home_url('/fondide-vordlus/'); ?>" style="color:var(--blue);font-weight:600;">&larr; Tagasi fondide juurde</a>
</div>
</div>

<script>
(function() {
var DATA_BASE = '<?php echo get_template_directory_uri(); ?>/fund-comparison/data/';

var TYPE_TAGS = {
  full: '<span class="tag tag-full">T\u00e4psed andmed</span>',
  proxy: '<span class="tag tag-proxy">Sarnane ETF</span>',
  opaque: '<span class="tag tag-opaque">Andmed puuduvad</span>'
};

var TYPE_LABELS = {
  'index': 'Indeks',
  'mixed': 'Segafond',
  'active': 'Aktiivne',
  'conservative': 'Konservatiivne',
  'bond': 'V\u00f5lakirjad'
};

Promise.all([
  fetch(DATA_BASE + 'fund_data.json').then(function(r) { return r.json(); }),
  fetch(DATA_BASE + 'data_sources.json').then(function(r) { return r.json(); }),
  fetch(DATA_BASE + 'etf_metadata.json').then(function(r) { return r.json(); })
]).then(function(results) {
  var fundData = results[0], sources = results[1], meta = results[2];
  var COVERAGE = meta.coverage;
  var ETF_SOURCES = meta.etf_sources;
  var PROXY_MAPPINGS = meta.proxy_mappings;

  var tbody = document.getElementById('fundTable');
  fundData.fund_order.forEach(function(key) {
    var fd = fundData.funds[key];
    var src = sources[key] || {};
    var typeLabel = TYPE_LABELS[fd.type] || fd.type;
    var cov = COVERAGE[key] || { pct: 0 };
    var covColor = cov.pct >= 90 ? '#166534' : cov.pct >= 70 ? '#92400e' : '#991b1b';
    var covBg = cov.pct >= 90 ? '#dcfce7' : cov.pct >= 70 ? '#fef3c7' : '#fee2e2';
    var covHtml = '<span class="tag" style="background:' + covBg + ';color:' + covColor + '">' + cov.pct + '%</span>';
    if (cov.note) covHtml += '<div style="font-size:0.7rem;color:#6b7280;margin-top:2px;">' + cov.note + '</div>';

    var pdfLink = src.url
      ? '<a href="' + src.url + '" target="_blank" style="font-size:0.8rem;">PDF &rarr;</a>'
      : '<span style="font-size:0.8rem;color:var(--g400);">' + (src.pdf || '\u2014') + '</span>';

    var tr = document.createElement('tr');
    tr.innerHTML = '<td><strong>' + key + '</strong></td>' +
      '<td>' + typeLabel + '</td>' +
      '<td>' + (src.date || '\u2014') + '</td>' +
      '<td>' + pdfLink + '</td>' +
      '<td>' + covHtml + '</td>';
    tbody.appendChild(tr);
  });

  var etfBody = document.getElementById('etfTable');
  ETF_SOURCES.forEach(function(etf) {
    var tr = document.createElement('tr');
    tr.innerHTML = '<td>' + etf.ticker + '</td>' +
      '<td style="font-size:0.8rem;">' + etf.source + '</td>' +
      '<td>' + etf.stocks + '</td>' +
      '<td>' + TYPE_TAGS[etf.type] + '</td>';
    etfBody.appendChild(tr);
  });

  if (PROXY_MAPPINGS && PROXY_MAPPINGS.length > 0) {
    var proxyBody = document.getElementById('proxyTable');
    if (proxyBody) {
      PROXY_MAPPINGS.forEach(function(p) {
        var tr = document.createElement('tr');
        tr.innerHTML = '<td style="font-family:monospace;font-size:0.8rem;">' + p.isin + '</td>' +
          '<td>' + p.mapped_to + '</td>' +
          '<td>' + TYPE_TAGS['proxy'] + '</td>';
        proxyBody.appendChild(tr);
      });
    }
  }
});
})();
</script>
</main>
