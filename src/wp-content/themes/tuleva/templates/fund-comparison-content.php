<main id="main" class="page-container section-spacing">
<script src="https://cdn.plot.ly/plotly-2.27.0.min.js"></script>
<style>
  .fund-comparison {
    --blue: #006CE6; --amber: #f59e0b; --green: #16a34a; --red: #dc2626;
    --purple: #7c3aed; --pink: #e11d48; --teal: #0d9488; --orange: #ea580c;
    --g50: #FAFBFC; --g100: #F5F7F9; --g200: #E0E6EC; --g400: #9ca3af;
    --g600: #4b5563; --g800: #293036; --g900: #111827;
    max-width: 960px;
    margin: 0 auto;
    padding: 0 1.5rem;
    font-family: Roboto, "Segoe UI", "Helvetica Neue", sans-serif;
    color: var(--g800);
    line-height: 1.6;
  }
  .fund-comparison h1 {
    font-family: "Merriweather", Georgia, serif;
    font-size: 2rem; font-weight: 800; line-height: 1.2; margin-bottom: 0.5rem;
  }
  .fund-comparison .subtitle { font-size: 1.1rem; color: var(--g600); margin-bottom: 2rem; max-width: 640px; }
  .fund-comparison .picker-label { font-size: 0.85rem; font-weight: 600; text-transform: uppercase;
                   letter-spacing: 0.05em; color: var(--g400); margin-bottom: 0.75rem; }
  .fund-comparison .fund-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
                gap: 0.5rem; margin-bottom: 0.75rem; }
  .fund-comparison .fund-card { padding: 0.7rem 1rem; border: 2px solid var(--g200); border-radius: 10px;
               cursor: pointer; transition: all 0.15s; background: #fff; position: relative; }
  .fund-comparison .fund-card:hover { border-color: var(--g400); }
  .fund-comparison .fund-card.selected { border-color: var(--blue); background: #eff6ff; box-shadow: 0 0 0 1px var(--blue); }
  .fund-comparison .fund-card .fn { font-weight: 600; font-size: 0.88rem; line-height: 1.3; }
  .fund-comparison .fund-card .fm { font-size: 0.72rem; color: var(--g400); margin-top: 2px; }
  .fund-comparison .fc-badge { display: inline-block; font-size: 0.65rem; padding: 1px 6px; border-radius: 4px;
           font-weight: 600; margin-top: 3px; }
  .fund-comparison .fc-badge-low { background: #dcfce7; color: #166534; }
  .fund-comparison .fc-badge-high { background: #fee2e2; color: #991b1b; }
  .fund-comparison .fund-card .ck { display: none; position: absolute; top: 6px; right: 8px; width: 20px; height: 20px;
                   background: var(--blue); border-radius: 50%; color: #fff; font-size: 12px;
                   line-height: 20px; text-align: center; }
  .fund-comparison .fund-card.selected .ck { display: block; }
  .fund-comparison .hint { font-size: 0.8rem; color: var(--g400); margin-bottom: 2rem; }
  .fund-comparison .fc-section { margin-bottom: 2.5rem; display: none; }
  .fund-comparison .fc-section.visible { display: block; }
  .fund-comparison .section-title { font-family: "Merriweather", Georgia, serif;
                   font-size: 1.25rem; font-weight: 700; margin-bottom: 0.25rem; display: flex;
                   align-items: center; gap: 0.4rem; }
  .fund-comparison .section-sub { font-size: 0.92rem; color: var(--g600); margin-bottom: 1rem; }
  .fund-comparison .narrative { font-size: 1.02rem; line-height: 1.7; max-width: 700px; margin-bottom: 1.5rem; }
  .fund-comparison .narrative strong { color: var(--g900); }
  .fund-comparison .chart-container { width: 100%; margin-bottom: 0.5rem; }
  .fund-comparison .asset-bar { display: flex; height: 32px; border-radius: 6px; overflow: hidden; margin-bottom: 0.4rem; }
  .fund-comparison .asset-bar .seg { display: flex; align-items: center; justify-content: center;
                    font-size: 0.7rem; font-weight: 600; color: #fff; white-space: nowrap;
                    overflow: hidden; }
  .fund-comparison .asset-legend { display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1rem; font-size: 0.78rem; }
  .fund-comparison .asset-legend span { display: flex; align-items: center; gap: 4px; }
  .fund-comparison .asset-legend .dot { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }
  .fund-comparison .asset-bar-label { font-size: 0.8rem; font-weight: 600; color: var(--g600); margin-bottom: 2px; }
  .fund-comparison .corr-table { border-collapse: collapse; font-size: 0.85rem; margin-bottom: 1rem; }
  .fund-comparison .corr-table td, .fund-comparison .corr-table th { padding: 8px 12px; text-align: center; border: 1px solid var(--g200); }
  .fund-comparison .corr-table th { background: var(--g50); font-weight: 600; font-size: 0.8rem; color: var(--g600); }
  .fund-comparison .insight-box { background: linear-gradient(135deg, #eff6ff 0%, #f5f3ff 100%);
                 border-left: 4px solid var(--blue); border-radius: 0 10px 10px 0;
                 padding: 1.25rem 1.5rem; margin: 1.5rem 0; }
  .fund-comparison .insight-box p { font-size: 1.02rem; line-height: 1.6; }
  .fund-comparison .info-icon { display: inline-flex; align-items: center; justify-content: center;
               width: 18px; height: 18px; border-radius: 50%; background: var(--g200);
               color: var(--g600); font-size: 0.7rem; font-weight: 700; cursor: help;
               position: relative; flex-shrink: 0; }
  .fund-comparison .info-icon .info-tip { display: none; position: absolute; bottom: 130%; left: 50%;
                         transform: translateX(-50%); background: var(--g900); color: #fff;
                         font-size: 0.75rem; font-weight: 400; line-height: 1.4;
                         padding: 0.6rem 0.8rem; border-radius: 8px; width: 320px;
                         z-index: 100; text-align: left; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
  .fund-comparison .info-icon:hover .info-tip { display: block; }
  .fund-comparison .holdings-grid { display: grid; gap: 1.5rem; margin-bottom: 0.5rem; }
  .fund-comparison .fc-footer { font-size: 0.8rem; color: var(--g600); border-top: 1px solid var(--g200);
            padding-top: 1.5rem; margin-top: 2rem; line-height: 1.7; }
  .fund-comparison .meth-toggle { cursor: pointer; color: var(--blue); font-weight: 600;
                 display: inline-flex; align-items: center; gap: 4px; }
  .fund-comparison .meth-toggle:hover { text-decoration: underline; }
  .fund-comparison .meth-body { display: none; margin-top: 0.75rem; padding: 1rem 1.25rem; background: var(--g50);
               border-radius: 8px; font-size: 0.8rem; line-height: 1.7; color: var(--g800); }
  .fund-comparison .meth-body.show { display: block; }
  .fund-comparison .holdings-col-title { font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem;
                        padding-bottom: 0.3rem; border-bottom: 2px solid var(--g200); }
  .fund-comparison .corr-gauge { margin: 1.5rem 0; max-width: 480px; }
  .fund-comparison .corr-gauge-label { font-size: 0.85rem; font-weight: 600; color: var(--g600); margin-bottom: 0.4rem; }
  .fund-comparison .corr-gauge-bar { height: 12px; border-radius: 6px; background: linear-gradient(to right, #dc2626, #f59e0b, #16a34a);
                    position: relative; margin-bottom: 0.3rem; }
  .fund-comparison .corr-gauge-marker { position: absolute; top: -4px; width: 4px; height: 20px; background: var(--g900);
                       border-radius: 2px; transform: translateX(-50%); }
  .fund-comparison .corr-gauge-val { font-size: 1.1rem; font-weight: 700; }
  .fund-comparison .corr-gauge-desc { font-size: 0.85rem; color: var(--g600); margin-top: 0.15rem; }
  .fund-comparison .nav-period-btns { display: flex; gap: 0.4rem; margin-bottom: 1rem; }
  .fund-comparison .nav-period-btn { padding: 0.35rem 0.9rem; border: 1.5px solid var(--g200); border-radius: 6px;
                    background: #fff; color: var(--g600); font-size: 0.82rem; font-weight: 600;
                    cursor: pointer; transition: all 0.15s; font-family: inherit; }
  .fund-comparison .nav-period-btn:hover { border-color: var(--g400); color: var(--g800); }
  .fund-comparison .nav-period-btn.active { border-color: var(--blue); background: var(--blue);
                    color: #fff; }
  @media (max-width: 640px) {
    .fund-comparison h1 { font-size: 1.5rem; }
    .fund-comparison .fund-grid { grid-template-columns: 1fr 1fr; }
    .fund-comparison .info-icon .info-tip { width: 240px; left: 0; transform: none; }
  }
</style>

<div class="fund-comparison">
<h1>[MUSTAND] Mida sinu pensionifond tegelikult ostab?</h1>
<p class="subtitle">Vali oma fond ja vaata, kuhu sinu raha tegelikult l&auml;heb. V&otilde;id valida kuni 2 fondi v&otilde;rdlemiseks.</p>
<div class="picker-label">Vali kuni 2 fondi</div>
<div class="fund-grid" id="fundGrid"></div>
<p class="hint" id="hint">Vali v&auml;hemalt 1 fond.</p>

<div class="fc-section" id="sectionNarrative"><div class="narrative" id="narrativeText"></div></div>

<div class="fc-section" id="sectionAssets">
  <div class="section-title">Varaklass</div>
  <div class="section-sub">Mida fond sisaldab: aktsiad, v&otilde;lakirjad, erakapital, kinnisvara?</div>
  <div id="assetBars"></div>
</div>

<div class="fc-section" id="sectionNav">
  <div class="section-title">Osaku puhasv&auml;&auml;rtuse (NAV) ajalugu</div>
  <div class="section-sub" id="navSub">Normaliseeritud 100-le valitud perioodi alguses</div>
  <div class="nav-period-btns" id="navPeriodBtns">
    <button class="nav-period-btn" data-years="10">10a</button>
    <button class="nav-period-btn active" data-years="5">5a</button>
    <button class="nav-period-btn" data-years="2">2a</button>
    <button class="nav-period-btn" data-years="1">1a</button>
  </div>
  <div class="chart-container" id="chartNav"></div>
</div>

<div class="fc-section" id="sectionOverlap">
  <div class="section-title">Kui palju fondid kattuvad?</div>
  <div class="section-sub">Kas fondid investeerivad samadesse kohtadesse ja kas nad liiguvad sarnaselt?</div>
  <div style="margin-bottom:1.5rem;">
    <div id="vennArea"></div>
  </div>
  <div id="corrGaugeArea"></div>
</div>

<div class="fc-section" id="sectionHoldings">
  <div class="section-title">Suurimad investeeringud</div>
  <div class="section-sub">Top 10 l&auml;bivaatega (look-through): ETF-ide sisse vaadatakse ja n&auml;idatakse tegelikke aktsiaid.</div>
  <div id="holdingsArea"></div>
</div>

<div class="fc-section" id="sectionScatter">
  <div class="section-title">Aktsia kaalud v&otilde;rrelduna</div>
  <div class="section-sub" id="scatterSub"></div>
  <div class="chart-container" id="chartScatter"></div>
</div>

<div class="fc-section" id="sectionSunburst">
  <div class="section-title">Portfelli struktuur</div>
  <div class="section-sub">Indeksfondid: ETF &rarr; aktsiad. Aktiivfondid: varaklass &rarr; investeeringud. Suurus = osakaal portfellis.</div>
  <div id="sunburstArea" style="display:flex;flex-wrap:wrap;gap:1rem;"></div>
</div>

<div class="fc-section" id="sectionSectors">
  <div class="section-title">Sektorite jaotus</div>
  <div class="section-sub">K&otilde;ik varaklassid, mitte ainult aktsiad.</div>
  <div class="chart-container" id="chartSectors"></div>
</div>

<div class="fc-section" id="sectionCountries">
  <div class="section-title">Riigid</div>
  <div class="chart-container" id="chartCountries"></div>
</div>

<div class="fc-section" id="sectionInsight">
  <div class="insight-box"><p id="insightText"></p></div>
</div>

<div class="fc-footer">
  <div style="margin-bottom:0.5rem;">Andmed: iShares ETF holdings, investeeringute aruanded, pensionikeskus.ee. <a href="<?php echo home_url('/fondide-vordlus-allikad/'); ?>" style="color:var(--blue);font-weight:600;">Andmeallikad ja katvus &rarr;</a></div>
  <div>
    <span class="meth-toggle" id="methToggle">
      <span class="info-icon" style="font-size:0.65rem;">i</span>
      Kuidas need numbrid on leitud?
    </span>
  </div>
  <div class="meth-body" id="methBody">
    <p><strong>Andmeallikad:</strong></p>
    <p><strong>Indeksfondid (Tuleva, Swedbank, Luminor):</strong>
    Indeksfondid investeerivad l&auml;bi ETF-ide (b&ouml;rsil kaubeldavad fondid). Vaatame iga ETF-i sisse &mdash;
    millised aktsiad seal on ja kui palju &mdash; ning arvutame v&auml;lja, kui suur osa sinu rahast l&auml;heb igasse aktsiasse.
    N&auml;iteks kui fond paneb 50% MSCI World ETF-i ja see ETF hoiab 5% Apple&rsquo;it, siis sinu rahast l&auml;heb Apple&rsquo;isse 2,5%.</p>

    <p style="margin-top:0.5rem;"><strong>Aktiivfondid (LHV Ettev&otilde;tlik):</strong>
    Fond avaldab igakuise investeeringute aruande (PDF). Sealt loeme v&auml;lja k&otilde;ik investeeringud: aktsiad, erakapitalifondid, kinnisvarafondid, v&otilde;lakirjad ja ETF-id.
    Erakapitali- ja kinnisvarafondide sisse me vaadata ei saa &mdash; n&auml;itame ainult fondi nime ja osakaalu.</p>

    <p style="margin-top:1.5rem;"><strong>Graafikute metoodika:</strong></p>

    <p style="margin-top:0.5rem;"><strong>Kui palju fondid kattuvad? (Venni diagramm):</strong>
    Loeme kokku, mitu investeeringut on ainult &uuml;hes fondis ja mitu on m&otilde;lemas.
    Kattuvus rahaliselt n&auml;itab, mitu protsenti fondi rahast on paigutatud samadesse investeeringutesse.
    See protsent v&otilde;ib olla kahel fondil erinev, sest &uuml;hised investeeringud v&otilde;ivad moodustada erineva osa kummagi fondi portfellist.
    N&auml;iteks kui Fond A paneb 90% raha aktsiatesse ja Fond B ainult 50% (ja &uuml;lej&auml;&auml;nu v&otilde;lakirjadesse),
    siis kattuvad aktsiad moodustavad Fondi A rahast 90%, aga Fondi B rahast ainult 50%.</p>

    <p style="margin-top:0.5rem;"><strong>Aktsia kaalud v&otilde;rrelduna (hajuvusdiagramm):</strong>
    Iga punkt on &uuml;ks investeering. X-telg n&auml;itab selle osakaalu esimeses fondis, Y-telg teises.
    Diagonaalil olevad punktid t&auml;hendavad identset kaalu m&otilde;lemas fondis.
    r (korrelatsioon) n&auml;itab, kui sarnaselt on kaalud jaotunud: r &gt; 0.9 t&auml;hendab v&auml;ga sarnast, r &lt; 0.3 v&auml;ga erinevat portfelli.</p>

    <p style="margin-top:0.5rem;"><strong>Portfelli struktuur (sunburst):</strong>
    Indeksfondide puhul: sisemine ring n&auml;itab ETF-e, v&auml;limine ring top-aktsiaid.
    Iga aktsia on n&auml;idatud &uuml;he segmendina koos oma t&auml;isportfelli kaaluga (nt Nvidia 5,26%) &mdash;
    aktsia on paigutatud selle ETF-i alla, mis seda k&otilde;ige rohkem hoiab.
    Aktiivfondide puhul: sisemine ring n&auml;itab varaklasse (aktsiad, erakapital, kinnisvara, v&otilde;lakirjad),
    v&auml;limine ring &uuml;ksikuid investeeringuid.</p>
  </div>
</div>
</div>

<script>
(function() {
const DATA_BASE = '<?php echo get_template_directory_uri(); ?>/data/fund-comparison/';
let DATA = null, NAV = null, RET_CORR = null, OVERLAP = null;
const selected = [];
const MAX_SELECT = 2;

const COLORS = {
  'Tuleva': '#006CE6',
  'Luminor 16-50': '#e11d48',
  'SEB Indeks': '#ea580c',
  'Swedbank K1960': '#65a30d', 'Swedbank K1970': '#059669',
  'Swedbank K1980': '#16a34a', 'Swedbank K1990': '#0d9488',
  'LHV Ettev\u00f5tlik': '#7c3aed', 'LHV Julge': '#9333ea',
  'SEB 55+': '#dc2626',
};
const LABELS = {
  'Tuleva': 'Tuleva Maailma Aktsiad',
  'Luminor 16-50': 'Luminor 16\u201350',
  'SEB Indeks': 'SEB Indeks',
  'Swedbank K1960': 'Swedbank 1960\u201369', 'Swedbank K1970': 'Swedbank 1970\u201379',
  'Swedbank K1980': 'Swedbank 1980\u201389', 'Swedbank K1990': 'Swedbank 1990\u201399',
  'LHV Ettev\u00f5tlik': 'LHV Ettev\u00f5tlik', 'LHV Julge': 'LHV Julge',
  'SEB 55+': 'SEB 55+',
};
const SHORT = {
  'Tuleva': 'Tuleva',
  'Luminor 16-50': 'Lum 16\u201350',
  'SEB Indeks': 'SEB Idx',
  'Swedbank K1960': 'SWB 60\u201369', 'Swedbank K1970': 'SWB 70\u201379',
  'Swedbank K1980': 'SWB 80\u201389', 'Swedbank K1990': 'SWB 90\u201399',
  'LHV Ettev\u00f5tlik': 'LHV Ettev.', 'LHV Julge': 'LHV Julge',
  'SEB 55+': 'SEB 55+',
};
var FEES = {
  'Tuleva': 0.28, 'Luminor 16-50': 1.08, 'SEB Indeks': 0.28,
  'Swedbank K1960': 0.74, 'Swedbank K1970': 0.74,
  'Swedbank K1980': 0.72, 'Swedbank K1990': 0.31,
  'LHV Ettev\u00f5tlik': 1.57, 'LHV Julge': 1.21, 'SEB 55+': 0.99,
};
const AC_COLORS = { stocks:'#006CE6', etfs:'#60a5fa', bonds:'#6b7280',
                    pe:'#7c3aed', re:'#059669', deposits:'#d1d5db', derivatives:'#f59e0b' };
const AC_LABELS = { stocks:'Aktsiad', etfs:'ETF-id', bonds:'V\u00f5lakirjad',
                    pe:'Erakapital', re:'Kinnisvara', deposits:'Hoiused', derivatives:'Tuletisinstr.' };
const PL = {
  font: { family: 'Roboto, "Segoe UI", sans-serif', size: 12 },
  paper_bgcolor: 'rgba(0,0,0,0)', plot_bgcolor: 'rgba(0,0,0,0)',
  margin: { t: 30, r: 20, b: 50, l: 50 },
};

var navPeriodYears = 5;

document.getElementById('methToggle').addEventListener('click', function() {
  document.getElementById('methBody').classList.toggle('show');
});

document.getElementById('navPeriodBtns').addEventListener('click', function(e) {
  var btn = e.target.closest('.nav-period-btn');
  if (!btn) return;
  navPeriodYears = parseInt(btn.dataset.years);
  document.querySelectorAll('.nav-period-btn').forEach(function(b) { b.classList.remove('active'); });
  btn.classList.add('active');
  if (selected.length > 0) renderNav(selected.slice());
});

Promise.all([
  fetch(DATA_BASE + 'fund_data.json').then(r => r.json()),
  fetch(DATA_BASE + 'nav_data.json').then(r => r.json()),
  fetch(DATA_BASE + 'return_correlations.json').then(r => r.json()),
  fetch(DATA_BASE + 'overlap_stats.json').then(r => r.json()),
]).then(function(results) {
  DATA = results[0]; NAV = results[1]; RET_CORR = results[2]; OVERLAP = results[3];
  buildGrid();
});

function buildGrid() {
  var grid = document.getElementById('fundGrid');
  DATA.fund_order.forEach(function(key) {
    var fd = DATA.funds[key];
    var fee = FEES[key];
    var feeText = fee ? 'Tasu ' + fee.toFixed(2) + '%' : '';
    var bc = fee && fee <= 0.5 ? 'fc-badge-low' : 'fc-badge-high';
    var card = document.createElement('div');
    card.className = 'fund-card'; card.dataset.fund = key;
    card.innerHTML = '<div class="fn">'+(LABELS[key]||key)+'</div>'+
      '<div class="fm">'+(fd.provider||'')+'</div>'+
      '<div class="fc-badge '+bc+'">'+feeText+'</div><div class="ck">\u2713</div>';
    card.addEventListener('click', function() { toggle(key); });
    grid.appendChild(card);
  });
}

function toggle(key) {
  var idx = selected.indexOf(key);
  if (idx >= 0) selected.splice(idx, 1);
  else if (selected.length < MAX_SELECT) selected.push(key);
  else return;
  document.querySelectorAll('.fund-comparison .fund-card').forEach(function(c) {
    c.classList.toggle('selected', selected.includes(c.dataset.fund));
  });
  document.getElementById('hint').textContent = selected.length === 0
    ? 'Vali v\u00e4hemalt 1 fond.'
    : selected.length+'/2 valitud.'+(selected.length<MAX_SELECT?' V\u00f5id lisada veel.':'');
  render();
}

function render() {
  var f = selected.slice(), has = f.length > 0;
  document.querySelectorAll('.fund-comparison .fc-section').forEach(function(s) { s.classList.toggle('visible', has); });
  if (!has) return;
  renderNarrative(f); renderAssets(f); renderNav(f); renderOverlap(f);
  renderHoldings(f); renderScatter(f); renderSunburst(f); renderSectors(f);
  renderCountries(f); renderInsight(f);
}

function renderNarrative(funds) {
  var el = document.getElementById('narrativeText');
  if (funds.length === 1) {
    var f = funds[0], fd = DATA.funds[f], ac = fd.asset_classes || {};
    var top3 = fd.top_holdings.slice(0,3).map(function(h) { return h.name.split(/\s/)[0]; }).join(', ');
    var eqPct = ((ac.stocks||0)+(ac.etfs||0)).toFixed(0);
    var hasPE = (ac.pe||0) > 1;
    if (hasPE) {
      el.innerHTML = '<strong>'+LABELS[f]+'</strong> on aktiivne fond. Aktsiad moodustavad '+eqPct+'% portfellist, '+
        '\u00fclej\u00e4\u00e4nu on erakapital ('+(ac.pe||0).toFixed(0)+'%), kinnisvara ('+(ac.re||0).toFixed(0)+'%) ja '+
        'v\u00f5lakirjad ('+(ac.bonds||0).toFixed(0)+'%). Suurimad aktsiad: '+top3+'.';
    } else {
      el.innerHTML = '<strong>'+LABELS[f]+'</strong> investeerib '+fd.n_stocks+' aktsiasse. '+
        'Suurimad: '+top3+'.';
    }
  } else {
    var names = funds.map(function(f) { return '<strong>'+LABELS[f]+'</strong>'; }).join(' vs ');
    el.innerHTML = names+' \u2014 erinevad nimed, erinevad tasud. Vaata allpool, kui sarnased nad tegelikult on.';
  }
}

function renderAssets(funds) {
  var el = document.getElementById('assetBars');
  el.innerHTML = '';
  var order = ['stocks','etfs','pe','re','bonds','deposits','derivatives'];
  funds.forEach(function(f) {
    var ac = DATA.funds[f].asset_classes || {stocks:100};
    el.innerHTML += '<div class="asset-bar-label">'+LABELS[f]+'</div>';
    var bar = '<div class="asset-bar">';
    order.forEach(function(k) {
      var pct = ac[k]||0;
      if (pct < 0.3) return;
      var label = pct > 5 ? AC_LABELS[k]+' '+pct.toFixed(0)+'%' : pct > 2 ? pct.toFixed(0)+'%' : '';
      bar += '<div class="seg" style="width:'+pct+'%;background:'+AC_COLORS[k]+'">'+label+'</div>';
    });
    bar += '</div>';
    el.innerHTML += bar;
  });
  var legend = '<div class="asset-legend">';
  order.forEach(function(k) { legend += '<span><span class="dot" style="background:'+AC_COLORS[k]+'"></span>'+AC_LABELS[k]+'</span>'; });
  el.innerHTML += legend + '</div>';
}

function renderNav(funds) {
  var section = document.getElementById('sectionNav');
  var hasNav = funds.some(function(f) { return NAV && NAV[f]; });
  section.classList.toggle('visible', hasNav);
  if (!hasNav) return;

  var cutoffDate = new Date();
  cutoffDate.setFullYear(cutoffDate.getFullYear() - navPeriodYears);
  var cutoffStr = cutoffDate.toISOString().slice(0, 10);

  var traces = funds.map(function(f) {
    var nd = NAV[f]; if (!nd) return null;
    var startIdx = 0;
    for (var i = 0; i < nd.dates.length; i++) {
      if (nd.dates[i] >= cutoffStr) { startIdx = i; break; }
    }
    var dates = nd.dates.slice(startIdx);
    var rawValues = nd.values.slice(startIdx);
    var baseVal = rawValues[0] || 1;
    var normValues = rawValues.map(function(v) { return v / baseVal * 100; });
    return { x: dates, y: normValues, mode: 'lines', name: LABELS[f],
             line: { color: COLORS[f], width: 2 },
             hovertemplate: LABELS[f] + '<br>%{x}: %{y:.1f}<extra></extra>' };
  }).filter(Boolean);
  Plotly.newPlot('chartNav', traces, {
    ...PL, yaxis: { title: 'NAV (normaliseeritud = 100)' },
    xaxis: { title: '' }, legend: { orientation: 'h', y: 1.1 }, height: 380,
  }, { responsive: true, displayModeBar: false });
}

function renderOverlap(funds) {
  var section = document.getElementById('sectionOverlap');
  if (funds.length < 2) { section.classList.remove('visible'); return; }
  section.classList.add('visible');

  var f1 = funds[0], f2 = funds[1];
  var key = f1+'|'+f2;
  var ov = OVERLAP[key];

  if (ov) {
    var totalA = ov.total_a || (ov.shared + ov.only_a);
    var totalB = ov.total_b || (ov.shared + ov.only_b);
    var sharedPctA = (ov.shared_weight_a / (ov.shared_weight_a + ov.only_weight_a) * 100).toFixed(0);
    var sharedPctB = (ov.shared_weight_b / (ov.shared_weight_b + ov.only_weight_b) * 100).toFixed(0);

    var vennHtml =
      '<svg viewBox="0 0 520 320" style="width:100%;max-width:520px;display:block;margin:0 auto;">'+
        '<text x="170" y="30" text-anchor="middle" font-size="15" font-weight="700" fill="'+COLORS[f1]+'">'+SHORT[f1]+'</text>'+
        '<text x="170" y="48" text-anchor="middle" font-size="12" fill="'+COLORS[f1]+'">('+totalA+' investeeringut)</text>'+
        '<text x="350" y="30" text-anchor="middle" font-size="15" font-weight="700" fill="'+COLORS[f2]+'">'+SHORT[f2]+'</text>'+
        '<text x="350" y="48" text-anchor="middle" font-size="12" fill="'+COLORS[f2]+'">('+totalB+' investeeringut)</text>'+
        '<circle cx="200" cy="170" r="120" fill="'+COLORS[f1]+'15" stroke="'+COLORS[f1]+'" stroke-width="2.5"/>'+
        '<circle cx="320" cy="170" r="120" fill="'+COLORS[f2]+'15" stroke="'+COLORS[f2]+'" stroke-width="2.5"/>'+
        '<text x="140" y="160" text-anchor="middle" font-size="24" font-weight="800" fill="'+COLORS[f1]+'">'+ov.only_a+'</text>'+
        '<text x="140" y="182" text-anchor="middle" font-size="13" fill="'+COLORS[f1]+'">ainult</text>'+
        '<text x="140" y="198" text-anchor="middle" font-size="13" fill="'+COLORS[f1]+'">'+SHORT[f1]+'</text>'+
        '<text x="260" y="155" text-anchor="middle" font-size="30" font-weight="800" fill="#1f2937">'+ov.shared+'</text>'+
        '<text x="260" y="178" text-anchor="middle" font-size="13" fill="#4b5563">sama</text>'+
        '<text x="260" y="195" text-anchor="middle" font-size="13" fill="#4b5563">investeering</text>'+
        '<text x="380" y="160" text-anchor="middle" font-size="24" font-weight="800" fill="'+COLORS[f2]+'">'+ov.only_b+'</text>'+
        '<text x="380" y="182" text-anchor="middle" font-size="13" fill="'+COLORS[f2]+'">ainult</text>'+
        '<text x="380" y="198" text-anchor="middle" font-size="13" fill="'+COLORS[f2]+'">'+SHORT[f2]+'</text>'+
        '<text x="260" y="310" text-anchor="middle" font-size="13" fill="#4b5563">'+
          'Kattuvus rahaliselt: '+sharedPctA+'% '+SHORT[f1]+' rahast, '+sharedPctB+'% '+SHORT[f2]+' rahast'+
        '</text>'+
      '</svg>';
    document.getElementById('vennArea').innerHTML = vennHtml;
  }

  // Correlation gauge
  var r = DATA.correlation_matrix[key] || 0;
  var rPct = (Math.abs(r) * 100).toFixed(0);
  var rDesc = '';
  if (r > 0.9) rDesc = 'Fondid liiguvad peaaegu identselt \u2014 sisuliselt sama portfell.';
  else if (r > 0.7) rDesc = 'Fondid liiguvad sarnaselt \u2014 suur kattuvus.';
  else if (r > 0.4) rDesc = 'Fondid liiguvad osaliselt koos, aga on selgeid erinevusi.';
  else rDesc = 'Fondid liiguvad erinevalt \u2014 p\u00e4riselt erinev portfell.';
  document.getElementById('corrGaugeArea').innerHTML =
    '<div class="corr-gauge">'+
      '<div class="corr-gauge-label">Portfellide sarnasus</div>'+
      '<div class="corr-gauge-bar"><div class="corr-gauge-marker" style="left:'+rPct+'%"></div></div>'+
      '<div style="display:flex;justify-content:space-between;font-size:0.7rem;color:var(--g400);margin-bottom:0.4rem;">'+
        '<span>0 \u2014 erinev</span><span>1 \u2014 identne</span>'+
      '</div>'+
      '<div class="corr-gauge-val" style="color:'+(r > 0.7 ? 'var(--green)' : r > 0.4 ? 'var(--amber)' : 'var(--red)')+'">r = '+r.toFixed(3)+'</div>'+
      '<div class="corr-gauge-desc">'+rDesc+'</div>'+
      '<div style="font-size:0.75rem;color:var(--g400);margin-top:0.3rem;">Pearsoni korrelatsioon kahe fondi n\u00e4dalatootluste vahel (viimased 5 aastat). N\u00e4itab, kui sarnaselt fondide v\u00e4\u00e4rtus ajas liigub.</div>'+
    '</div>';
}

function renderHoldings(funds) {
  var el = document.getElementById('holdingsArea');
  el.innerHTML = '';
  var grid = document.createElement('div');
  grid.className = 'holdings-grid';
  grid.style.gridTemplateColumns = 'repeat('+funds.length+', 1fr)';

  funds.forEach(function(f) {
    var fd = DATA.funds[f];
    var col = document.createElement('div');
    var title = document.createElement('div');
    title.className = 'holdings-col-title';
    title.style.color = COLORS[f];
    title.textContent = SHORT[f] || f;
    col.appendChild(title);

    var items = [];
    fd.top_holdings.forEach(function(h) { items.push({ name: h.name, weight: h.weight, cat: 'stock' }); });
    if (fd.pe_holdings) fd.pe_holdings.forEach(function(h) { items.push({ name: h.name, weight: h.weight, cat: 'pe' }); });
    if (fd.re_holdings) fd.re_holdings.forEach(function(h) { items.push({ name: h.name, weight: h.weight, cat: 're' }); });
    if (fd.bond_holdings) fd.bond_holdings.forEach(function(h) { items.push({ name: h.name, weight: h.weight, cat: 'bond' }); });

    items.sort(function(a, b) { return b.weight - a.weight; });
    var top10 = items.slice(0, 10);

    var catColors = { stock:'var(--g800)', pe:'#7c3aed', re:'#059669', bond:'#6b7280', etf:'#60a5fa' };
    var catTags = { stock:'', pe:'PE', re:'RE', bond:'V\u00f5lak.', etf:'ETF' };

    top10.forEach(function(item) {
      var row = document.createElement('div');
      row.style.cssText = 'display:flex;align-items:baseline;padding:3px 0;font-size:0.82rem;gap:4px;';

      if (catTags[item.cat]) {
        var tag = document.createElement('span');
        tag.style.cssText = 'font-size:0.6rem;font-weight:700;color:#fff;background:'+catColors[item.cat]+';padding:1px 4px;border-radius:3px;flex-shrink:0;';
        tag.textContent = catTags[item.cat];
        row.appendChild(tag);
      }

      var nameSpan = document.createElement('span');
      nameSpan.style.cssText = 'flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:'+catColors[item.cat]+';';
      var dn = item.name.length > 26 ? item.name.substring(0, 26) + '\u2026' : item.name;
      nameSpan.textContent = dn;
      nameSpan.title = item.name;

      var wSpan = document.createElement('span');
      wSpan.style.cssText = 'font-weight:700;font-variant-numeric:tabular-nums;flex-shrink:0;';
      wSpan.textContent = item.weight.toFixed(2) + '%';

      row.appendChild(nameSpan);
      row.appendChild(wSpan);
      col.appendChild(row);
    });

    grid.appendChild(col);
  });

  el.appendChild(grid);
}

function renderScatter(funds) {
  if (funds.length < 2) {
    document.getElementById('sectionScatter').classList.remove('visible');
    return;
  }
  document.getElementById('sectionScatter').classList.add('visible');
  var f1 = funds[0], f2 = funds[1];
  var w1 = DATA.funds[f1].weights || {};
  var w2 = DATA.funds[f2].weights || {};
  var allKeys = new Set([...Object.keys(w1), ...Object.keys(w2)]);
  var xs = [], ys = [], names = [];
  allKeys.forEach(function(k) {
    var a = w1[k]||0, b = w2[k]||0;
    if (a > 0.02 || b > 0.02) { xs.push(a); ys.push(b); names.push(k.replace(/^(PE|RE|BOND|ETF)\|/, '')); }
  });
  document.getElementById('scatterSub').textContent =
    LABELS[f1]+' vs '+LABELS[f2]+'. Iga t\u00e4pp = \u00fcks investeering. Diagonaalil = identne kaal.';
  var mx = Math.max(...xs, ...ys) * 1.05;
  var r = DATA.correlation_matrix[f1+'|'+f2] || 0;
  Plotly.newPlot('chartScatter', [
    { x: xs, y: ys, mode:'markers', showlegend:false,
      marker:{size:8,color:'#6366f1',opacity:0.45}, text:names,
      hovertemplate:'%{text}<br>'+LABELS[f1]+': %{x:.2f}%<br>'+LABELS[f2]+': %{y:.2f}%<extra></extra>' },
    { x:[0,mx], y:[0,mx], mode:'lines', line:{dash:'dash',color:'#ccc'}, showlegend:false },
  ], { ...PL,
    xaxis:{title:LABELS[f1]+' (%)'},
    yaxis:{title:LABELS[f2]+' (%)'},
    height:450,
    annotations:[{ x:0.95, y:0.05, xref:'paper', yref:'paper',
      text:'r = '+r.toFixed(3), showarrow:false,
      font:{size:16,color:'#333'}, bgcolor:'rgba(255,255,255,0.8)' }],
  }, {responsive:true, displayModeBar:false});
}

function renderSunburst(funds) {
  var area = document.getElementById('sunburstArea');
  area.innerHTML = '';
  var sbWidth = funds.length === 2 ? 'calc(50% - 0.5rem)' : '100%';
  var pal = ['#006CE6','#16a34a','#dc2626','#f59e0b','#7c3aed','#e11d48','#0d9488','#ea580c','#6b7280'];

  funds.forEach(function(f) {
    var fd = DATA.funds[f];
    var nodes = [];
    var rootId = f + '_root';
    var ac = fd.asset_classes || { stocks: 100 };

    if (fd.etf_breakdown) {
      // Assign each top stock to its primary ETF (largest ETF among those holding it)
      var topStocks = (fd.top_holdings || []).slice(0, 40);
      var etfAssignedWeight = {};
      fd.etf_breakdown.forEach(function(_, ei) { etfAssignedWeight[ei] = 0; });

      topStocks.forEach(function(h, si) {
        var bestEi = 0, bestWeight = -1;
        fd.etf_breakdown.forEach(function(etf, ei) {
          if (etf.top_stocks.some(function(s) { return s.name === h.name; })) {
            if (etf.fund_weight > bestWeight) { bestWeight = etf.fund_weight; bestEi = ei; }
          }
        });
        var etfId = f + '_etf' + bestEi;
        var col = pal[bestEi % pal.length];
        var sn = h.name.split(/\s/).slice(0, 2).join(' ');
        nodes.push({id: f+'_s'+si, label: sn, parentId: etfId, value: h.weight, color: col+'88'});
        etfAssignedWeight[bestEi] = (etfAssignedWeight[bestEi] || 0) + h.weight;
      });

      fd.etf_breakdown.forEach(function(etf, ei) {
        var etfId = f + '_etf' + ei;
        var col = pal[ei % pal.length];
        var rest = etf.fund_weight - (etfAssignedWeight[ei] || 0);
        if (rest > 0.5) {
          nodes.push({id: etfId+'_rest', label: 'Muud', parentId: etfId, value: rest, color: col+'44'});
        }
        nodes.push({id: etfId, label: etf.name+' ('+etf.fund_weight.toFixed(0)+'%)', parentId: rootId, value: 0, color: col});
      });

    } else if (fd.pe_holdings && fd.pe_holdings.length > 0) {
      var stockId = f + '_stocks';
      fd.top_holdings.slice(0, 8).forEach(function(h, i) {
        nodes.push({id: f+'_s'+i, label: h.name.split(/\s/).slice(0,2).join(' '), parentId: stockId, value: h.weight, color: '#3b82f6'});
      });
      var sr = fd.top_holdings.slice(8).reduce(function(s,h) { return s+h.weight; }, 0);
      if (sr > 0) nodes.push({id: f+'_srest', label: 'Muud aktsiad', parentId: stockId, value: sr, color: '#93c5fd'});

      if (fd.etf_holdings && fd.etf_holdings.length > 0) {
        var etfId2 = f + '_etfs';
        fd.etf_holdings.forEach(function(h, i) {
          nodes.push({id: f+'_e'+i, label: h.name.length>20?h.name.substring(0,20)+'\u2026':h.name, parentId: etfId2, value: h.weight, color: '#93c5fd'});
        });
        nodes.push({id: etfId2, label: 'ETF-id', parentId: rootId, value: 0, color: '#60a5fa'});
      }

      var peId = f + '_pe';
      fd.pe_holdings.slice(0, 8).forEach(function(h, i) {
        nodes.push({id: f+'_p'+i, label: h.name.length>20?h.name.substring(0,20)+'\u2026':h.name, parentId: peId, value: h.weight, color: '#a78bfa'});
      });
      var pr = fd.pe_holdings.slice(8).reduce(function(s,h) { return s+h.weight; }, 0);
      if (pr > 0) nodes.push({id: f+'_prest', label: 'Muu PE', parentId: peId, value: pr, color: '#c4b5fd'});

      if (fd.re_holdings && fd.re_holdings.length > 0) {
        var reId = f + '_re';
        fd.re_holdings.forEach(function(h, i) {
          nodes.push({id: f+'_r'+i, label: h.name.length>20?h.name.substring(0,20)+'\u2026':h.name, parentId: reId, value: h.weight, color: '#34d399'});
        });
        nodes.push({id: reId, label: 'Kinnisvara', parentId: rootId, value: 0, color: '#059669'});
      }

      if (fd.bond_holdings && fd.bond_holdings.length > 0) {
        var bondId = f + '_bonds';
        fd.bond_holdings.slice(0, 6).forEach(function(h, i) {
          nodes.push({id: f+'_b'+i, label: h.name.length>20?h.name.substring(0,20)+'\u2026':h.name, parentId: bondId, value: h.weight, color: '#9ca3af'});
        });
        var br = fd.bond_holdings.slice(6).reduce(function(s,h) { return s+h.weight; }, 0);
        if (br > 0) nodes.push({id: f+'_brest', label: 'Muud v\u00f5lakirjad', parentId: bondId, value: br, color: '#d1d5db'});
        nodes.push({id: bondId, label: 'V\u00f5lakirjad', parentId: rootId, value: 0, color: '#6b7280'});
      }

      if ((ac.deposits||0) > 0.5) nodes.push({id: f+'_dep', label: 'Hoiused', parentId: rootId, value: ac.deposits, color: '#d1d5db'});

      nodes.push({id: stockId, label: 'Aktsiad', parentId: rootId, value: 0, color: '#006CE6'});
      nodes.push({id: peId, label: 'Erakapital', parentId: rootId, value: 0, color: '#7c3aed'});

    } else {
      var holdings = fd.top_holdings.slice(0, 15);
      var topWeight = holdings.reduce(function(s,h) { return s+h.weight; }, 0);
      var totalW = ac.stocks || fd.total_weight || 100;
      var restWeight = Math.max(0, totalW - topWeight);

      var bySector = {};
      holdings.forEach(function(h) { var s = h.sector||'Muu'; if (!bySector[s]) bySector[s]=[]; bySector[s].push(h); });
      if (restWeight > 1) {
        if (!bySector['Muu']) bySector['Muu'] = [];
        bySector['Muu'].push({ name: 'Muud ('+(fd.n_stocks - holdings.length)+')', weight: restWeight, _isRest: true });
      }

      var cl = Object.entries(bySector).map(function(e) { return {s:e[0],hs:e[1],total:e[1].reduce(function(sum,h){return sum+h.weight;},0)}; }).sort(function(a,b){return b.total-a.total;});
      cl.forEach(function(item, ci) {
        var col = pal[ci%pal.length], sId = f+'_sec'+ci;
        item.hs.forEach(function(h, hi) {
          var sn = h._isRest ? h.name : h.name.split(/\s/).slice(0,2).join(' ');
          nodes.push({id: sId+'_h'+hi, label: sn, parentId: sId, value: h.weight, color: h._isRest ? '#d1d5db' : col+'88'});
        });
        nodes.push({id: sId, label: item.s, parentId: rootId, value: 0, color: col});
      });
    }

    var nodeMap = {};
    nodes.forEach(function(n) { nodeMap[n.id] = n; });
    nodes.forEach(function(n) {
      if (n.value > 0 && n.parentId && nodeMap[n.parentId]) {
        nodeMap[n.parentId].value += n.value;
      }
    });
    var rootSum = 0;
    nodes.forEach(function(n) { if (n.parentId === rootId) rootSum += n.value; });

    var ids = [rootId], labels = [SHORT[f]||f], parents = [''], vals = [rootSum], cols = ['#e5e7eb'], cdata = [rootSum];
    nodes.forEach(function(n) {
      ids.push(n.id); labels.push(n.label); parents.push(n.parentId);
      vals.push(n.value); cols.push(n.color);
      cdata.push(n._hoverWeight != null ? n._hoverWeight : n.value);
    });

    var div = document.createElement('div');
    div.style.cssText = 'width:'+sbWidth+';min-width:0;flex-shrink:0;';
    var divId = 'sunburst_' + f.replace(/\s/g, '_');
    div.id = divId;
    area.appendChild(div);

    Plotly.newPlot(divId, [{
      type: 'sunburst', ids: ids, labels: labels, parents: parents, values: vals, customdata: cdata,
      branchvalues: 'total',
      hovertemplate: '<b>%{label}</b><br>%{customdata:.2f}%<extra></extra>',
      textinfo: 'label',
      insidetextorientation: 'radial',
      marker: { colors: cols, line: { width: 1, color: '#fff' } },
    }], {
      ...PL, height: 420, margin: { t: 40, r: 5, b: 5, l: 5 },
      title: { text: LABELS[f] || f, font: { size: 14, color: COLORS[f] } },
    }, { responsive: true, displayModeBar: false });
  });
}

function renderSectors(funds) {
  var sectorOrder = DATA.acwi_sector_order.slice(0,11).slice();
  funds.forEach(function(f) {
    Object.keys(DATA.funds[f].sectors).forEach(function(s) {
      if (!sectorOrder.includes(s)) sectorOrder.push(s);
    });
  });
  var traces = funds.map(function(f) {
    return {
      y: sectorOrder, x: sectorOrder.map(function(s) { return DATA.funds[f].sectors[s]||0; }),
      type:'bar', orientation:'h', name: LABELS[f], marker:{color:COLORS[f]},
    };
  });
  Plotly.newPlot('chartSectors', traces, {
    ...PL, barmode:'group', xaxis:{title:'Osakaal (%)'},
    yaxis:{autorange:'reversed'}, legend:{orientation:'h',y:1.08},
    height: Math.max(400, sectorOrder.length*28), margin:{...PL.margin,l:180},
  }, {responsive:true, displayModeBar:false});
}

function renderCountries(funds) {
  var cs = new Map();
  funds.forEach(function(f) {
    Object.entries(DATA.funds[f].countries).forEach(function(e) { cs.set(e[0],(cs.get(e[0])||0)+e[1]); });
  });
  var order = Array.from(cs.entries()).sort(function(a,b){return b[1]-a[1];}).map(function(e){return e[0];}).slice(0,12);
  var traces = funds.map(function(f) {
    return {
      x:order, y:order.map(function(c){return DATA.funds[f].countries[c]||0;}),
      type:'bar', name:LABELS[f], marker:{color:COLORS[f]},
    };
  });
  Plotly.newPlot('chartCountries', traces, {
    ...PL, barmode:'group', xaxis:{tickangle:-45},
    yaxis:{title:'Osakaal (%)'}, legend:{orientation:'h',y:1.1}, height:400,
  }, {responsive:true, displayModeBar:false});
}

function renderInsight(funds) {
  var el = document.getElementById('insightText');
  if (funds.length === 1) {
    var fd = DATA.funds[funds[0]], ac = fd.asset_classes || {};
    if ((ac.pe||0) > 1) {
      el.innerHTML = '<strong>'+LABELS[funds[0]]+'</strong> erineb indeksfondidest: '+(ac.pe||0).toFixed(0)+
        '% erakapitali ja '+(ac.re||0).toFixed(0)+'% kinnisvara on l\u00e4bipaistmatud investeeringud. '+
        'Aktsiapositsioonid on aktiivselt valitud ja erinevad indeksfondidest.';
    } else {
      el.innerHTML = '<strong>'+LABELS[funds[0]]+'</strong> investeerib samadesse ettev\u00f5tetesse '+
        'kui iga teine Eesti indeks-pensionifond. <strong>Ainus t\u00f5eline erinevus on tasu.</strong>';
    }
  } else {
    var pairs = [];
    for (var i=0; i<funds.length; i++)
      for (var j=i+1; j<funds.length; j++)
        pairs.push({ a:funds[i], b:funds[j], r: DATA.correlation_matrix[funds[i]+'|'+funds[j]]||0 });
    var allHigh = pairs.every(function(p) { return p.r > 0.9; });
    var anyLow = pairs.some(function(p) { return p.r < 0.3; });
    if (allHigh) {
      el.innerHTML = 'K\u00f5igi valitud fondide korrelatsioon on \u00fcle 0.9 \u2014 '+
        'nad investeerivad sisuliselt samadesse ettev\u00f5tetesse. '+
        '<strong>Ainus t\u00f5eline erinevus on tasu, mida sa maksad.</strong>';
    } else if (anyLow) {
      var low = pairs.find(function(p) { return p.r < 0.3; });
      el.innerHTML = '<strong>'+LABELS[low.a]+'</strong> ja <strong>'+LABELS[low.b]+'</strong> '+
        'portfellid on v\u00e4ga erinevad (r = '+low.r.toFixed(3)+'). '+
        'Aktiivne fond investeerib hoopis teistmoodi \u2014 aga kas see t\u00e4hendab paremat tootlust?';
    } else {
      el.innerHTML = 'M\u00f5ned erinevused on, aga \u00fcldpilt on sarnane: '+
        'samad suured ettev\u00f5tted domineerivad igal pool.';
    }
  }
}
})();
</script>
</main>
