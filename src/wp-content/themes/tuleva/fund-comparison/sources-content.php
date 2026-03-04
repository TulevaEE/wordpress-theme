<main id="main" class="page-container section-spacing">
<style>
  .fc-sources {
    --blue: #0081EE; --navy: #013063;
    --g50: #f9fafb; --g100: #f3f4f6; --g200: #e5e7eb;
    --g400: #9ca3af; --g600: #4b5563; --g800: #1f2937; --g900: #111827;
    max-width: 960px;
    margin: 0 auto;
    padding: 0 1.5rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    color: var(--g800);
    line-height: 1.6;
  }
  .fc-sources h1 {
    font-size: 1.75rem; font-weight: 800; margin-bottom: 0.5rem;
  }
  .fc-sources h2 {
    font-size: 1.2rem; font-weight: 700; margin: 2rem 0 0.75rem;
  }
  .fc-sources .subtitle { font-size: 1rem; color: var(--g600); margin-bottom: 2rem; }
  .fc-sources .back-link { display: inline-flex; align-items: center; gap: 4px; font-size: 0.85rem;
               color: var(--blue); font-weight: 600; text-decoration: none; margin-bottom: 1.5rem; }
  .fc-sources .back-link:hover { text-decoration: underline; }
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
  .fc-sources .tag-partial { background: #dbeafe; color: #1e40af; }
  .fc-sources .note { font-size: 0.85rem; color: var(--g600); line-height: 1.7; margin-bottom: 1rem; }
  .fc-sources .fc-footer { font-size: 0.8rem; color: var(--g600); border-top: 1px solid var(--g200);
            padding-top: 1.5rem; margin-top: 2rem; }
  @media (max-width: 640px) {
    .fc-sources table { font-size: 0.75rem; }
    .fc-sources th, .fc-sources td { padding: 6px 8px; }
  }
</style>

<div class="fc-sources">
<a href="<?php echo home_url('/fondide-vordlus/'); ?>" class="back-link">&larr; Tagasi fondide juurde</a>
<h1>Andmeallikad ja katvus</h1>
<p class="subtitle">Kuidas iga fondi andmed on kogutud ja kui palju portfellist on aktsiatasemel l&auml;bipaistev.</p>

<h2>Kuidas andmed on kogutud?</h2>
<div class="note">
  <ol style="padding-left:1.2rem;">
    <li><strong>Fondide investeerimisaruanded.</strong> Iga fondi kohta laadisime fondivalitseja kodulehelt alla
        investeerimisaruande (PDF) seisuga 31.01.2025. Aruandest lugesime v&auml;lja fondi positsioonid:
        ETF-id, aktsiad, v&otilde;lakirjad, kinnisvara- ja erakapitalifondid koos osakaaludega.</li>
    <li><strong>ETF-ide ja fondide l&auml;bivaatamine (look-through).</strong> Indeksfondid investeerivad l&auml;bi ETF-ide.
        Iga ETF-i koosseisu saime avalikest allikatest (iShares&rsquo;i CSV-failid). Kui ETF-i t&auml;pne koosseis
        polnud k&auml;ttesaadav (nt L&amp;G, SPDR), kasutasime sarnast iShares&rsquo;i ETF-i l&auml;hendusena.
        Fondide puhul, mille koosseis pole avalik (nt SEB sisefondid), kasutasime EODHD API-t,
        et saada v&auml;hemalt top 10&ndash;50 positsiooni.</li>
    <li><strong>&Uuml;htne andmetabel.</strong> K&otilde;igi fondide positsioonid koondati &uuml;htsesse tabelisse, kus iga aktsia
        on identifitseeritud ISIN-koodi j&auml;rgi. See v&otilde;imaldab fondide omavahelisi v&otilde;rdlusi:
        kattuvuse, sektorite, riikide ja &uuml;ksikaktsiate l&otilde;ikes.</li>
    <li><strong>NAV ajalugu.</strong> Osakute puhasv&auml;&auml;rtuse (NAV) ajalugu p&auml;rineb
        <a href="https://www.pensionikeskus.ee/en/statistics/ii-pillar/nav-of-funded-pension/" style="color:var(--blue)">Pensionikeskuse statistikast</a>.
        Andmed laaditi alla XLS-failina iga fondi kohta eraldi (10 aasta periood) ja teisendati
        n&auml;dalaseks aegrida (reede seisuga).</li>
    <li><strong>Visualiseeringud.</strong> Andmete p&otilde;hjal loodi interaktiivsed graafikud: Venni diagramm (kattuvus),
        tulpdiagramm (sektorid, riigid), hajuvusdiagramm (&uuml;ksikaktsiate kaalud), sunburst (portfelli struktuur),
        NAV v&otilde;rdlus ja korrelatsioonianalü&uuml;s.</li>
  </ol>
</div>

<div class="note" style="background:var(--g50);border:1px solid var(--g200);border-radius:8px;padding:1rem 1.2rem;margin-bottom:2rem;">
  <strong>&#9888; Vastutuse piirang</strong><br>
  See leht on loodud informatiivsel eesm&auml;rgil. Andmed v&otilde;ivad sisaldada ebat&auml;psusi &mdash;
  kontrolli alati fakte ise fondivalitseja ametlikest allikatest. See ei ole investeerimisnõuanne.
  Lehek&uuml;lg on loodud <a href="https://claude.ai/claude-code" style="color:var(--blue)">Claude Code</a> abiga, kuid k&auml;sitsi &uuml;le kontrollitud.
</div>

<h2>Kasutatud allikad</h2>
<div class="note">
  <p style="margin-bottom:0.5rem;"><strong>Fondide investeerimisaruanded (PDF-id, seisuga 31.01.2026):</strong></p>
  <ul style="padding-left:1.2rem;margin-bottom:1rem;">
    <li><a href="https://tuleva.ee/aruanded/" style="color:var(--blue)">Tuleva Maailma Aktsiate Pensionifond</a> &mdash; investeeringute aruanne jaanuar 2026</li>
    <li><a href="https://www.luminor.ee/ee/pensionifondid/kohustuslikud-pensionifondid" style="color:var(--blue)">Luminor 16-50 Pensionifond</a> &mdash; investeeringute aruanne jaanuar 2026</li>
    <li><a href="https://www.seb.ee/pensionifondid" style="color:var(--blue)">SEB Pensionifond Indeks</a> &mdash; investeeringute raport 31.01.2026</li>
    <li><a href="https://www.seb.ee/pensionifondid" style="color:var(--blue)">SEB Pensionifond 55+</a> &mdash; investeeringute raport 31.01.2026</li>
    <li><a href="https://www.swedbank.ee/private/pensions/pillar2/comparison" style="color:var(--blue)">Swedbank K1960, K1970, K1980, K1990</a> &mdash; investment portfolio 31.01.2026</li>
    <li><a href="https://www.lhv.ee/et/pensionifond/ettevotlik" style="color:var(--blue)">LHV Pensionifond Ettev&otilde;tlik</a> &mdash; investeeringute raport 31.01.2026</li>
    <li><a href="https://www.lhv.ee/et/pensionifond/julge" style="color:var(--blue)">LHV Pensionifond Julge</a> &mdash; investeeringute raport 31.01.2026</li>
  </ul>
  <p style="margin-bottom:0.5rem;"><strong>ETF-ide koosseisud (CSV-failid):</strong></p>
  <ul style="padding-left:1.2rem;margin-bottom:1rem;">
    <li><a href="https://www.ishares.com/uk/individual/en/products/305419/" style="color:var(--blue)">iShares MSCI World ESG Screened UCITS ETF (SAWD)</a></li>
    <li><a href="https://www.ishares.com/uk/individual/en/products/305356/" style="color:var(--blue)">iShares MSCI USA ESG Screened UCITS ETF (SASU)</a></li>
    <li><a href="https://www.ishares.com/uk/individual/en/products/305363/" style="color:var(--blue)">iShares MSCI Europe ESG Screened UCITS ETF (SAEU)</a></li>
    <li><a href="https://www.ishares.com/uk/individual/en/products/305412/" style="color:var(--blue)">iShares MSCI Japan ESG Screened UCITS ETF (SAJP)</a></li>
    <li><a href="https://www.ishares.com/uk/individual/en/products/305397/" style="color:var(--blue)">iShares MSCI EM IMI ESG Screened UCITS ETF (SAEM)</a></li>
  </ul>
  <p style="margin-bottom:0.5rem;"><strong>Muud andmeallikad:</strong></p>
  <ul style="padding-left:1.2rem;">
    <li><a href="https://www.pensionikeskus.ee/statistika/ii-sammas/kogumispensioni-paevastatistika/" style="color:var(--blue)">Pensionikeskus</a> &mdash; osakute puhasv&auml;&auml;rtuse (NAV) ajalugu ja fondide tasud</li>
    <li><a href="https://eodhd.com" style="color:var(--blue)">EODHD</a> &mdash; ETF-ide ja fondide koosseisud, mida iShares&rsquo;ist otse ei saa</li>
  </ul>
</div>

<h2>Fondide &uuml;levaade</h2>
<table>
  <thead>
    <tr>
      <th>Fond</th>
      <th>T&uuml;&uuml;p</th>
      <th>Aruande kuup&auml;ev</th>
      <th>Allikas</th>
      <th>Katvus</th>
    </tr>
  </thead>
  <tbody id="fundTable"></tbody>
</table>

<h2>ETF l&auml;bivaatamine (look-through)</h2>
<p class="note">
  Indeksfondid investeerivad l&auml;bi ETF-ide. Kasutame iShares&rsquo;i avalikke CSV-faile, et n&auml;ha iga ETF-i
  sees olevaid aktsiaid. M&otilde;ne fondi sees on ka SEB sisefonde, mille kohta pole avalikke andmeid &mdash;
  need on m&auml;rgitud &ldquo;l&auml;bipaistmatu&rdquo;.
</p>
<table>
  <thead>
    <tr>
      <th>ETF / fond</th>
      <th>ISIN</th>
      <th>Andmeallikas</th>
      <th>Aktsiate arv</th>
      <th>Staatus</th>
    </tr>
  </thead>
  <tbody id="etfTable"></tbody>
</table>

<h2>Piirangud</h2>
<div class="note">
  <ul style="padding-left:1.2rem;">
    <li><strong>SEB sisefondid</strong> (SEB Emerging Markets, SEB Europe, SEB Global Exposure) on l&auml;bipaistmatud &mdash;
        fondivalitseja ei avalda nende koosseisu. Need moodustavad ~28% SEB Indeksist ja ~40% SEB 55+ portfellist.</li>
    <li><strong>L&amp;G ja SPDR ETF-id</strong> on l&auml;hendatud iShares&rsquo;i sarnaste indeksite p&otilde;hjal (nt L&amp;G Japan &rarr; iShares Japan ESG Screened).</li>
    <li><strong>Erakapitali- ja kinnisvarafondid</strong> (LHV, SEB 55+) on l&auml;bipaistmatud &mdash; n&auml;itame ainult fondi nime ja osakaalu.</li>
    <li><strong>Luminor 16-50</strong> kahe ETF-i kohta (Robeco 3D Global, Xtrackers Materials) puuduvad t&auml;ielikud andmed (~11% portfellist).</li>
    <li><strong>Swedbank K-seeria</strong> aktsiatel on ISIN-koodid otse PDF-ist &mdash; t&auml;pne andmekvaliteet.</li>
  </ul>
</div>

<div class="fc-footer">
  <a href="<?php echo home_url('/fondide-vordlus/'); ?>" style="color:var(--blue);font-weight:600;">&larr; Tagasi fondide juurde</a>
</div>
</div>

<script>
(function() {
var DATA_BASE = '<?php echo get_template_directory_uri(); ?>/fund-comparison/data/';

var ETF_SOURCES = [
  {name: 'iShares MSCI World ESG Screened (SAWD)', isin: 'IE0009FT4LX4', source: 'iShares CSV', url: 'https://www.ishares.com/uk/individual/en/products/305419/', stocks: 1224, type: 'full'},
  {name: 'iShares MSCI USA ESG Screened (SASU)', isin: 'IE00BFNM3G45', source: 'iShares CSV', url: 'https://www.ishares.com/uk/individual/en/products/305356/', stocks: 499, type: 'full'},
  {name: 'iShares MSCI Europe ESG Screened (SAEU)', isin: 'IE00BFNM3D14', source: 'iShares CSV', url: 'https://www.ishares.com/uk/individual/en/products/305363/', stocks: 390, type: 'full'},
  {name: 'iShares MSCI Japan ESG Screened (SAJP)', isin: 'IE00BFNM3L97', source: 'iShares CSV', url: 'https://www.ishares.com/uk/individual/en/products/305412/', stocks: 171, type: 'full'},
  {name: 'iShares MSCI EM IMI ESG Screened (SAEM)', isin: 'IE00BKPTWY98', source: 'iShares CSV', url: 'https://www.ishares.com/uk/individual/en/products/305397/', stocks: 2779, type: 'full'},
  {name: 'L&G Japan Equity UCITS ETF', isin: 'IE00BFXR5T61', source: 'Proxy: SAJP', url: '', stocks: 171, type: 'proxy'},
  {name: 'L&G US Equity UCITS ETF', isin: 'IE00BFXR5Q31', source: 'Proxy: SASU', url: '', stocks: 499, type: 'proxy'},
  {name: 'SPDR S&P 500 ESG Leaders', isin: 'IE00BH4GPZ28', source: 'Proxy: SASU', url: '', stocks: 499, type: 'proxy'},
  {name: 'SEB Emerging Markets Exposure', isin: 'SE0021147394', source: 'Puudub (SEB sisefond)', url: '', stocks: 0, type: 'opaque'},
  {name: 'SEB Europe Exposure Fund IC', isin: 'LU1118354460', source: 'Puudub (SEB sisefond)', url: '', stocks: 0, type: 'opaque'},
  {name: 'SEB Global Exposure Fund', isin: 'LU1711526407', source: 'Puudub (SEB sisefond)', url: '', stocks: 0, type: 'opaque'},
  {name: 'Robeco 3D Global Equity', isin: '\u2014', source: 'Puudub', url: '', stocks: 0, type: 'opaque'},
  {name: 'Xtrackers MSCI World Materials', isin: '\u2014', source: 'Puudub', url: '', stocks: 0, type: 'opaque'},
];

var TYPE_TAGS = {
  full: '<span class="tag tag-full">T\u00e4psed andmed</span>',
  proxy: '<span class="tag tag-proxy">Sarnane ETF</span>',
  opaque: '<span class="tag tag-opaque">Andmed puuduvad</span>',
};

var COVERAGE = {
  'Tuleva': { pct: 100, note: '' },
  'Luminor 16-50': { pct: 89, note: '~11% l\u00e4bipaistmatud ETF-id' },
  'SEB Indeks': { pct: 72, note: '~28% SEB sisefondid (l\u00e4bipaistmatud)' },
  'Swedbank K1960': { pct: 98, note: '' },
  'Swedbank K1970': { pct: 98, note: '' },
  'Swedbank K1980': { pct: 97, note: '' },
  'Swedbank K1990': { pct: 100, note: '' },
  'LHV Ettev\u00f5tlik': { pct: 95, note: 'PE/RE fondid ainult nime tasemel' },
  'LHV Julge': { pct: 94, note: 'PE/RE fondid ainult nime tasemel' },
  'SEB 55+': { pct: 40, note: '~60% l\u00e4bipaistmatud ETF-id ja sisefondid' },
};

Promise.all([
  fetch(DATA_BASE + 'fund_data.json').then(function(r) { return r.json(); }),
  fetch(DATA_BASE + 'data_sources.json').then(function(r) { return r.json(); }),
]).then(function(results) {
  var fundData = results[0], sources = results[1];
  var tbody = document.getElementById('fundTable');
  fundData.fund_order.forEach(function(key) {
    var fd = fundData.funds[key];
    var src = sources[key] || {};
    var typeLabel = fd.type === 'index' ? 'Indeks (ETF)' :
                    fd.type === 'mixed' ? 'Segafond' : 'Aktiivne';
    var cov = COVERAGE[key] || { pct: 0, note: '' };
    var covColor = cov.pct >= 90 ? '#166534' : cov.pct >= 70 ? '#92400e' : '#991b1b';
    var covBg = cov.pct >= 90 ? '#dcfce7' : cov.pct >= 70 ? '#fef3c7' : '#fee2e2';
    var covHtml = '<span class="tag" style="background:'+covBg+';color:'+covColor+'">'+cov.pct+'%</span>';
    if (cov.note) covHtml += '<div style="font-size:0.7rem;color:#6b7280;margin-top:2px;">'+cov.note+'</div>';

    var tr = document.createElement('tr');
    tr.innerHTML = '<td><strong>'+key+'</strong></td>'+
      '<td>'+typeLabel+'</td>'+
      '<td>'+(src.date || '\u2014')+'</td>'+
      '<td style="font-size:0.8rem;">'+(src.pdf || '\u2014')+'</td>'+
      '<td>'+covHtml+'</td>';
    tbody.appendChild(tr);
  });

  var etfBody = document.getElementById('etfTable');
  ETF_SOURCES.forEach(function(etf) {
    var tr = document.createElement('tr');
    var srcHtml = etf.url
      ? '<a href="'+etf.url+'" target="_blank" style="color:var(--blue);font-weight:600;">'+etf.source+'</a>'
      : etf.source;
    tr.innerHTML = '<td>'+etf.name+'</td>'+
      '<td style="font-family:monospace;font-size:0.8rem;">'+etf.isin+'</td>'+
      '<td style="font-size:0.8rem;">'+srcHtml+'</td>'+
      '<td>'+(etf.stocks || '\u2014')+'</td>'+
      '<td>'+TYPE_TAGS[etf.type]+'</td>';
    etfBody.appendChild(tr);
  });
});
})();
</script>
</main>
