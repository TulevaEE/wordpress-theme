<main id="main" class="page-container section-spacing">
<script src="https://cdn.plot.ly/plotly-2.27.0.min.js"></script>
<style>
  .fund-comparison {
    --blue: #0081EE; --navy: #013063; --amber: #f59e0b; --green: #16a34a; --red: #dc2626;
    --purple: #7c3aed; --pink: #e11d48; --teal: #0d9488; --orange: #ea580c;
    --g50: #f9fafb; --g100: #f3f4f6; --g200: #e5e7eb; --g400: #9ca3af;
    --g600: #4b5563; --g800: #1f2937; --g900: #111827;
    --bg-pale: #EEF7FD;
    max-width: 960px;
    margin: 0 auto;
    padding: 0 1.5rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    color: var(--navy);
    line-height: 1.6;
  }
  .fund-comparison * { box-sizing: border-box; }
  .fund-comparison h1 { font-size: 2rem; font-weight: 800; line-height: 1.2; margin-bottom: 0.5rem; color: var(--navy); }
  .fund-comparison .subtitle { font-size: 1.1rem; color: var(--g600); margin-bottom: 2rem; max-width: 640px; }
  .fund-comparison .picker-label { font-size: 0.85rem; font-weight: 600; text-transform: uppercase;
                   letter-spacing: 0.05em; color: var(--g400); margin-bottom: 0.75rem; }
  .fund-comparison .fund-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
                gap: 0.5rem; margin-bottom: 0.75rem; }
  .fund-comparison .fund-card { padding: 0.7rem 1rem; border: 2px solid var(--g200); border-radius: 10px;
               cursor: pointer; transition: all 0.15s; background: #fff; position: relative; }
  .fund-comparison .fund-card:hover { border-color: var(--g400); }
  .fund-comparison .fund-card.selected { border-color: var(--navy); background: var(--bg-pale); box-shadow: 0 0 0 1px var(--navy); }
  .fund-comparison .fund-card .fn { font-weight: 600; font-size: 0.88rem; line-height: 1.3; }
  .fund-comparison .fund-card .fm { font-size: 0.72rem; color: var(--g400); margin-top: 2px; }
  .fund-comparison .badge { display: inline-block; font-size: 0.65rem; padding: 1px 6px; border-radius: 4px;
           font-weight: 600; margin-top: 3px; }
  .fund-comparison .badge-low { background: #dcfce7; color: #166534; }
  .fund-comparison .badge-high { background: #fee2e2; color: #991b1b; }
  .fund-comparison .fund-card .ck { display: none; position: absolute; top: 6px; right: 8px; width: 20px; height: 20px;
                   background: var(--navy); border-radius: 50%; color: #fff; font-size: 12px;
                   line-height: 20px; text-align: center; }
  .fund-comparison .fund-card.selected .ck { display: block; }
  .fund-comparison .hint { font-size: 0.8rem; color: var(--g400); margin-bottom: 2rem; }
  .fund-comparison .section { margin-bottom: 2.5rem; display: none; }
  .fund-comparison .section.visible { display: block; }
  .fund-comparison .section-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 0.25rem; display: flex;
                   align-items: center; gap: 0.4rem; color: var(--navy); }
  .fund-comparison .section-sub { font-size: 0.92rem; color: var(--g600); margin-bottom: 1rem; }
  .fund-comparison .narrative { font-size: 1.02rem; line-height: 1.7; max-width: 700px; margin-bottom: 1.5rem; }
  .fund-comparison .narrative strong { color: var(--navy); }
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
  .fund-comparison .num-accent { font-family: Georgia, 'Times New Roman', serif; font-weight: 700; }
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
  .fund-comparison .holdings-col-title { font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem;
                        padding-bottom: 0.3rem; border-bottom: 2px solid var(--g200); }
  .fund-comparison .nav-period-btns { display: flex; gap: 0.4rem; margin-bottom: 1rem; }
  .fund-comparison .nav-period-btn { padding: 0.35rem 0.9rem; border: 1.5px solid var(--g200); border-radius: 6px;
                    background: #fff; color: var(--g600); font-size: 0.82rem; font-weight: 600;
                    cursor: pointer; transition: all 0.15s; font-family: inherit; }
  .fund-comparison .nav-period-btn:hover { border-color: var(--g400); color: var(--g800); }
  .fund-comparison .nav-period-btn.active { border-color: var(--navy); background: var(--navy); color: #fff; }
  .fund-comparison .return-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; margin-top: 1rem; }
  .fund-comparison .return-table th, .fund-comparison .return-table td { padding: 8px 12px; text-align: left; border-bottom: 1px solid var(--g200); }
  .fund-comparison .return-table th { font-size: 0.75rem; font-weight: 600; color: var(--g400); text-transform: uppercase; letter-spacing: 0.03em; }
  .fund-comparison .return-table td { font-variant-numeric: tabular-nums; }
  .fund-comparison .return-table .fund-name { font-weight: 600; }
  .fund-comparison .return-table .fee-diff { font-size: 0.78rem; color: var(--red); font-weight: 600; }
  .fund-comparison .footer { font-size: 0.8rem; color: var(--g600); border-top: 1px solid var(--g200);
            padding-top: 1.5rem; margin-top: 2rem; line-height: 1.7; }
  .fund-comparison .meth-toggle { cursor: pointer; color: #999; font-size: 0.85rem; font-weight: 400;
                 display: inline-flex; align-items: center; gap: 4px; }
  .fund-comparison .meth-toggle:hover { text-decoration: underline; color: var(--g600); }
  .fund-comparison .meth-body { display: none; margin-top: 0.75rem; padding: 1rem 1.25rem; background: var(--g50);
               border-radius: 8px; font-size: 0.8rem; line-height: 1.7; color: var(--g800); }
  .fund-comparison .meth-body.show { display: block; }
  .fund-comparison .summary-fees { font-size: 0.95rem; color: var(--g800); margin-bottom: 0.25rem; }
  .fund-comparison .summary-diff { font-size: 1rem; font-weight: 700; color: var(--red); margin-bottom: 0.75rem; }
  .fund-comparison .summary-verdict { font-size: 1.2rem; font-weight: 800; color: var(--navy); }
  .fund-comparison .cta-box { background: var(--bg-pale); border-radius: 12px; padding: 2rem; text-align: center; margin: 2rem 0; }
  .fund-comparison .cta-box p { font-size: 1rem; color: var(--navy); margin-bottom: 1rem; line-height: 1.5; }
  .fund-comparison .cta-btn { display: inline-block; background: var(--navy); color: #fff; font-size: 1.05rem; font-weight: 700;
             padding: 0.85rem 2.5rem; border-radius: 10px; text-decoration: none; transition: background 0.15s; }
  .fund-comparison .cta-btn:hover { background: var(--blue); }
  .fund-comparison .copy-link-btn { display: inline-flex; align-items: center; gap: 6px; background: none; border: 1.5px solid var(--g200);
                   border-radius: 6px; padding: 0.4rem 0.8rem; font-size: 0.8rem; color: var(--g600); cursor: pointer;
                   font-family: inherit; transition: all 0.15s; margin-top: 0.75rem; }
  .fund-comparison .copy-link-btn:hover { border-color: var(--navy); color: var(--navy); }
  .fund-comparison .provider-tabs { display: flex; gap: 0.25rem; margin-bottom: 0.75rem; flex-wrap: wrap; }
  .fund-comparison .provider-tab { padding: 0.4rem 0.9rem; border: 1.5px solid var(--g200); border-radius: 6px;
                  background: #fff; color: var(--g600); font-size: 0.82rem; font-weight: 600;
                  cursor: pointer; transition: all 0.15s; font-family: inherit; position: relative; }
  .fund-comparison .provider-tab:hover { border-color: var(--g400); color: var(--g800); }
  .fund-comparison .provider-tab.active { border-color: var(--navy); background: var(--navy); color: #fff; }
  .fund-comparison .provider-tab.has-selected { border-color: var(--blue); color: var(--blue); }
  .fund-comparison .provider-tab.has-selected::after { content: ''; position: absolute; top: -3px; right: -3px;
    width: 8px; height: 8px; background: var(--blue); border-radius: 50%; }
  @media (max-width: 640px) {
    .fund-comparison h1 { font-size: 1.5rem; }
    .fund-comparison .fund-grid { grid-template-columns: 1fr 1fr; }
    .fund-comparison .info-icon .info-tip { width: 240px; left: 0; transform: none; }
    .fund-comparison .summary-card { padding: 1.25rem 1.25rem; }
  }
</style>

<div class="fund-comparison">
<h1>Fondide r&ouml;ntgen</h1>
<p class="subtitle">Vaata, mis on sinu fondi sees ja mida see maksab.</p>
<div class="picker-label">Vali kuni 2 fondi</div>
<div class="provider-tabs" id="providerTabs"></div>
<div class="fund-grid" id="fundGrid"></div>
<p class="hint" id="hint">Vali v&auml;hemalt 1 fond.</p>
<p style="font-size:0.8rem;color:var(--g400);margin-top:-1rem;margin-bottom:2rem;">Ei tea oma fondi? <a href="https://pension.tuleva.ee/login" style="color:var(--g400);text-decoration:underline;">Vaata siit &rarr;</a></p>

<div class="section" id="sectionNarrative"><div class="narrative" id="narrativeText"></div></div>

<div class="section" id="sectionOverlap">
  <div class="section-title" style="font-size:1.35rem;">Kas fondid investeerivad samasse kohta?</div>
  <div class="section-sub">Enamik fonde ei osta aktsiaid otse, vaid l&auml;bi b&ouml;rsil kaubeldavate fondide (ETF). Siin vaatame ETF-ide sisse ja v&otilde;rdleme tegelikke aktsiaid.</div>
  <div id="overlapContent"></div>
</div>

<div class="section" id="sectionAssets">
  <div class="section-title">Varaklass</div>
  <div class="section-sub">Kuhu fond investeerib: aktsiad, v&otilde;lakirjad, erakapital, kinnisvara?</div>
  <div id="assetBars"></div>
</div>

<div class="section" id="sectionHoldings">
  <div class="section-title">Suurimad investeeringud</div>
  <div class="section-sub">Enamik fonde ei osta aktsiaid otse, vaid l&auml;bi b&ouml;rsil kaubeldavate fondide (ETF). Siin vaatame ETF-ide sisse ja n&auml;itame tegelikke aktsiaid.</div>
  <div id="holdingsArea"></div>
</div>

<div class="section" id="sectionNav">
  <div class="section-title">Fondi v&auml;&auml;rtuse ajalugu</div>
  <div class="section-sub" id="navSub">Normaliseeritud 100-le valitud perioodi alguses</div>
  <div class="nav-period-btns" id="navPeriodBtns">
    <button class="nav-period-btn" data-years="10">10a</button>
    <button class="nav-period-btn active" data-years="5">5a</button>
    <button class="nav-period-btn" data-years="2">2a</button>
    <button class="nav-period-btn" data-years="1">1a</button>
  </div>
  <div class="chart-container" id="chartNav"></div>
  <div id="navReturnTable"></div>
</div>



<div class="section" id="sectionCTA"></div>

<div class="section" id="sectionShare"></div>

<div class="section" id="sectionDetails">
  <span class="meth-toggle" id="detailsToggle">Vaata detailsemalt</span>
  <div class="meth-body" id="detailsBody">
    <div id="detailScatter" style="margin-bottom:2rem;">
      <div class="section-title">Investeeringute kaalud v&otilde;rrelduna</div>
      <div class="section-sub" id="scatterSub"></div>
      <div class="chart-container" id="chartScatter"></div>
    </div>
    <div id="detailSunburst" style="margin-bottom:2rem;">
      <div class="section-title">Portfelli struktuur</div>
      <div class="section-sub">Sisemine ring: varaklassid. V&auml;limine ring: &uuml;ksikud investeeringud (ETF-ide sees). Suurus = osakaal portfellis.</div>
      <div id="sunburstArea" style="display:flex;flex-wrap:wrap;gap:1rem;"></div>
    </div>
    <div id="detailSectors" style="margin-bottom:2rem;">
      <div class="section-title">Sektorite jaotus</div>
      <div class="section-sub">K&otilde;ik varaklassid, mitte ainult aktsiad.</div>
      <div class="chart-container" id="chartSectors"></div>
    </div>
    <div id="detailCountries">
      <div class="section-title">Riigid</div>
      <div class="chart-container" id="chartCountries"></div>
    </div>
  </div>
</div>

<div class="footer">
  <div id="dataFreshness" style="font-weight:600;color:var(--g800);margin-bottom:0.5rem;"></div>
  <div>Andmed: investeerimisaruanded, pensionikeskus.ee, iShares, Swedbank Robur.</div>
  <div style="margin-top:0.5rem;"><a href="<?php echo home_url('/fondide-vordlus-allikad/'); ?>" style="color:var(--blue);font-weight:600;">Kuidas need numbrid on leitud? Andmeallikad ja metoodika &rarr;</a></div>
</div>
</div>

<script>
var DATA_BASE = '<?php echo get_template_directory_uri(); ?>/fund-comparison/data/';

var DATA = null, NAV = null, RET_CORR = null, OVERLAP = null;
var selected = [];
var MAX_SELECT = 2;

var COLORS = {
  'Tuleva': '#013063', 'Tuleva V\u00f5lakirjad': '#4b9cd3',
  'Luminor 16-50': '#D63B84', 'Luminor Indeks': '#ec4899',
  'Luminor 50-56': '#f472b6', 'Luminor 56+': '#fb7185', 'Luminor 61-65': '#fda4af',
  'SEB Indeks': '#51c26c', 'SEB 18+': '#f97316', 'SEB 55+': '#ea580c',
  'SEB 60+': '#fb923c', 'SEB 65+': '#fdba74',
  'Swedbank K1960': '#e8a317', 'Swedbank K1970': '#e8a317',
  'Swedbank K1980': '#F7A600', 'Swedbank K1990': '#d4900e',
  'Swedbank Indeks': '#ca8a04', 'Swedbank 2000-09': '#eab308', 'Swedbank Konservatiivne': '#fde047',
  'LHV Ettev\u00f5tlik': '#7c3aed', 'LHV Julge': '#9333ea',
  'LHV Rahulik': '#a78bfa', 'LHV Indeks': '#6d28d9', 'LHV Tasakaalukas': '#8b5cf6'
};
var LABELS = {
  'Tuleva': 'Tuleva Maailma Aktsiad', 'Tuleva V\u00f5lakirjad': 'Tuleva V\u00f5lakirjad',
  'Luminor 16-50': 'Luminor 16\u201350', 'Luminor Indeks': 'Luminor Indeks',
  'Luminor 50-56': 'Luminor 50\u201356', 'Luminor 56+': 'Luminor 56+', 'Luminor 61-65': 'Luminor 61\u201365',
  'SEB Indeks': 'SEB Indeks', 'SEB 18+': 'SEB 18+', 'SEB 55+': 'SEB 55+',
  'SEB 60+': 'SEB 60+', 'SEB 65+': 'SEB 65+',
  'Swedbank K1960': 'Swedbank 1960\u201369', 'Swedbank K1970': 'Swedbank 1970\u201379',
  'Swedbank K1980': 'Swedbank 1980\u201389', 'Swedbank K1990': 'Swedbank 1990\u201399',
  'Swedbank Indeks': 'Swedbank Indeks', 'Swedbank 2000-09': 'Swedbank 2000\u201309',
  'Swedbank Konservatiivne': 'Swedbank Kons.',
  'LHV Ettev\u00f5tlik': 'LHV Ettev\u00f5tlik', 'LHV Julge': 'LHV Julge',
  'LHV Rahulik': 'LHV Rahulik', 'LHV Indeks': 'LHV Indeks', 'LHV Tasakaalukas': 'LHV Tasakaalukas'
};
var SHORT = {
  'Tuleva': 'Tuleva', 'Tuleva V\u00f5lakirjad': 'Tuleva VK',
  'Luminor 16-50': 'Luminor 16\u201350', 'Luminor Indeks': 'Lum. Ind.',
  'Luminor 50-56': 'Lum. 50\u201356', 'Luminor 56+': 'Lum. 56+', 'Luminor 61-65': 'Lum. 61\u201365',
  'SEB Indeks': 'SEB Indeks', 'SEB 18+': 'SEB 18+', 'SEB 55+': 'SEB 55+',
  'SEB 60+': 'SEB 60+', 'SEB 65+': 'SEB 65+',
  'Swedbank K1960': 'Swedbank 60\u201369', 'Swedbank K1970': 'Swedbank 70\u201379',
  'Swedbank K1980': 'Swedbank 80\u201389', 'Swedbank K1990': 'Swedbank 90\u201399',
  'Swedbank Indeks': 'SWB Ind.', 'Swedbank 2000-09': 'SWB 00\u201309', 'Swedbank Konservatiivne': 'SWB Kons.',
  'LHV Ettev\u00f5tlik': 'LHV Ettev.', 'LHV Julge': 'LHV Julge',
  'LHV Rahulik': 'LHV Rah.', 'LHV Indeks': 'LHV Ind.', 'LHV Tasakaalukas': 'LHV Tasak.'
};
var FEES = {
  'Tuleva': 0.28, 'Tuleva V\u00f5lakirjad': 0.28,
  'Luminor 16-50': 1.08, 'Luminor Indeks': 0.27,
  'Luminor 50-56': 1.13, 'Luminor 56+': 1.14, 'Luminor 61-65': 0.88,
  'SEB Indeks': 0.28, 'SEB 18+': 0.95, 'SEB 55+': 0.99,
  'SEB 60+': 0.90, 'SEB 65+': 0.50,
  'Swedbank K1960': 0.74, 'Swedbank K1970': 0.74,
  'Swedbank K1980': 0.72, 'Swedbank K1990': 0.31,
  'Swedbank Indeks': 0.27, 'Swedbank 2000-09': 0.75, 'Swedbank Konservatiivne': 0.47,
  'LHV Ettev\u00f5tlik': 1.57, 'LHV Julge': 1.21,
  'LHV Rahulik': 0.53, 'LHV Indeks': 0.27, 'LHV Tasakaalukas': 1.13
};
var AC_COLORS = { stocks:'#0081EE', etfs:'#60a5fa', bonds:'#6b7280',
                    pe:'#7c3aed', re:'#059669', deposits:'#d1d5db', derivatives:'#f59e0b' };
var AC_LABELS = { stocks:'Aktsiad', etfs:'Fondid (l\u00e4bipaistmatud)', bonds:'V\u00f5lakirjad',
                    pe:'Erakapital', re:'Kinnisvara', deposits:'Hoiused', derivatives:'Tuletisinstr.' };
var PL = {
  font: { family: '-apple-system, BlinkMacSystemFont, sans-serif', size: 12, color: '#013063' },
  paper_bgcolor: 'rgba(0,0,0,0)', plot_bgcolor: 'rgba(0,0,0,0)',
  margin: { t: 30, r: 20, b: 50, l: 50 }
};

var COUNTRY_ET = {
  'United States':'USA', 'Japan':'Jaapan', 'United Kingdom':'Suurbritannia',
  'France':'Prantsusmaa', 'Germany':'Saksamaa', 'Switzerland':'\u0160veits',
  'Canada':'Kanada', 'Taiwan':'Taiwan', 'China':'Hiina', 'Korea (South)':'L\u00f5una-Korea',
  'Netherlands':'Holland', 'India':'India', 'Sweden':'Rootsi', 'Australia':'Austraalia',
  'Spain':'Hispaania', 'Italy':'Itaalia', 'Denmark':'Taani', 'Norway':'Norra',
  'Finland':'Soome', 'Estonia':'Eesti', 'Lithuania':'Leedu', 'Latvia':'L\u00e4ti',
  'Ireland':'Iirimaa', 'Singapore':'Singapur', 'Hong Kong':'Hongkong',
  'Brazil':'Brasiilia', 'South Africa':'LAV', 'Mexico':'Mehhiko',
  'Bermuda':'Bermuda', 'Cayman Islands':'Cayman', 'Jersey':'Jersey',
  'Luxembourg':'Luxembourg', 'Belgium':'Belgia', 'Austria':'Austria',
  'Eesti (PE/RE/v\u00f5lak.)':'Eesti (PE/RE/v\u00f5lak.)'
};
var SECTOR_ET = {
  'Information Technology':'IT', 'Financials':'Finants', 'Industrials':'T\u00f6\u00f6stus',
  'Consumer Discretionary':'Tarbijakaup', 'Health Care':'Tervishoid',
  'Communication':'Kommunikatsioon', 'Consumer Staples':'P\u00f5hitarve',
  'Materials':'Materjalid', 'Energy':'Energia', 'Utilities':'Kommunaalteenused',
  'Real Estate':'Kinnisvara', 'Other':'Muu', 'Direct Investment':'Otse',
  'Erakapital':'Erakapital', 'Kinnisvara':'Kinnisvara', 'V\u00f5lakirjad':'V\u00f5lakirjad',
  'ETF-id':'ETF-id'
};
function trCountry(c) { return COUNTRY_ET[c] || c; }
function trSector(s) { return SECTOR_ET[s] || s; }

var navPeriodYears = 5;

var CTA_CONFIG = (function() {
  var now = new Date();
  var y = now.getFullYear();
  var mar31 = new Date(y, 2, 31);
  var nov30 = new Date(y, 10, 30);
  var deadline;
  if (now <= mar31) deadline = '31. m\u00e4rts ' + y;
  else if (now <= nov30) deadline = '30. november ' + y;
  else deadline = '31. m\u00e4rts ' + (y + 1);
  return {
    text: 'Fondivahetus on tasuta ja v\u00f5tab paar minutit. J\u00e4rgmine t\u00e4htaeg: ' + deadline + '.',
    buttonText: 'Vaata l\u00e4hemalt \u2192',
    buttonUrl: 'https://tuleva.ee/fondivahetuse-avaldus/'
  };
})();

document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('detailsToggle').addEventListener('click', function() {
    document.getElementById('detailsBody').classList.toggle('show');
  });
  document.getElementById('navPeriodBtns').addEventListener('click', function(e) {
    var btn = e.target.closest('.nav-period-btn');
    if (!btn) return;
    navPeriodYears = parseInt(btn.dataset.years);
    document.querySelectorAll('.nav-period-btn').forEach(function(b) { b.classList.remove('active'); });
    btn.classList.add('active');
    if (selected.length > 0) renderNav([].concat(selected));
  });
});

Promise.all([
  fetch(DATA_BASE + 'fund_data.json').then(function(r) { return r.json(); }),
  fetch(DATA_BASE + 'nav_data.json').then(function(r) { return r.json(); }),
  fetch(DATA_BASE + 'return_correlations.json').then(function(r) { return r.json(); }),
  fetch(DATA_BASE + 'overlap_stats.json').then(function(r) { return r.json(); })
]).then(function(results) {
  var data = results[0], nav = results[1], retCorr = results[2], overlap = results[3];
  DATA = data; NAV = nav; RET_CORR = retCorr; OVERLAP = overlap;
  if (DATA.fees) { FEES = DATA.fees; }
  var dataMonth = DATA.data_month || '';
  if (dataMonth) {
    var MONTHS_ET = {
      '01':'jaanuar','02':'veebruar','03':'m\u00e4rts','04':'aprill','05':'mai','06':'juuni',
      '07':'juuli','08':'august','09':'september','10':'oktoober','11':'november','12':'detsember'
    };
    var parts = dataMonth.split('-');
    var yr = parts[0], mo = parts[1];
    var moName = MONTHS_ET[mo] || mo;
    document.getElementById('dataFreshness').textContent = 'Andmed seisuga: ' + moName + ' ' + yr;
  }
  buildGrid();
  var urlParams = new URLSearchParams(window.location.search);
  var vParam = urlParams.get('v');
  if (vParam) {
    var names = vParam.split(',').map(function(s) { return s.trim(); });
    names.forEach(function(name) {
      var key = DATA.fund_order.find(function(k) { return k === name || (LABELS[k]||'') === name; });
      if (key && selected.indexOf(key) === -1 && selected.length < MAX_SELECT) {
        selected.push(key);
      }
    });
    if (selected.length > 0) {
      var firstProvider = DATA.funds[selected[0]].provider || PROVIDER_ORDER[0];
      document.querySelectorAll('.provider-tab').forEach(function(t) {
        t.classList.toggle('active', t.textContent === firstProvider);
      });
      showProvider(firstProvider);
      updateTabBadges();
      document.getElementById('hint').textContent = selected.length + '/2 valitud.' + (selected.length < MAX_SELECT ? ' V\u00f5id lisada veel.' : '');
      render();
    }
  }
});

var fundGroups = {};
var PROVIDER_ORDER = ['Tuleva','Swedbank','LHV','SEB','Luminor'];

function buildGrid() {
  DATA.fund_order.forEach(function(key) {
    var p = DATA.funds[key].provider || 'Muu';
    if (!fundGroups[p]) fundGroups[p] = [];
    fundGroups[p].push(key);
  });
  var tabs = document.getElementById('providerTabs');
  PROVIDER_ORDER.forEach(function(p, i) {
    if (!fundGroups[p]) return;
    var tab = document.createElement('button');
    tab.className = 'provider-tab' + (i === 0 ? ' active' : '');
    tab.textContent = p;
    tab.addEventListener('click', function() {
      tabs.querySelectorAll('.provider-tab').forEach(function(t) { t.classList.remove('active'); });
      tab.classList.add('active');
      showProvider(p);
      updateTabBadges();
    });
    tabs.appendChild(tab);
  });
  showProvider(PROVIDER_ORDER[0]);
}

function showProvider(provider) {
  var grid = document.getElementById('fundGrid');
  grid.innerHTML = '';
  (fundGroups[provider] || []).forEach(function(key) {
    var fee = FEES[key];
    var feeText = fee ? 'Tasu ' + fee.toFixed(2) + '%' : '';
    var bc = fee && fee <= 0.5 ? 'badge-low' : 'badge-high';
    var card = document.createElement('div');
    card.className = 'fund-card' + (selected.indexOf(key) >= 0 ? ' selected' : '');
    card.dataset.fund = key;
    card.innerHTML = '<div class="fn">' + (LABELS[key]||key) + '</div>' +
      '<div class="badge ' + bc + '">' + feeText + '</div><div class="ck">\u2713</div>';
    card.addEventListener('click', function() { toggle(key); });
    grid.appendChild(card);
  });
}

function updateTabBadges() {
  document.querySelectorAll('.provider-tab').forEach(function(tab) {
    var provider = tab.textContent;
    var hasSel = selected.some(function(k) { return (DATA.funds[k]||{}).provider === provider; });
    tab.classList.toggle('has-selected', hasSel && !tab.classList.contains('active'));
  });
}

function toggle(key) {
  var idx = selected.indexOf(key);
  if (idx >= 0) selected.splice(idx, 1);
  else if (selected.length < MAX_SELECT) selected.push(key);
  else return;
  document.querySelectorAll('.fund-card').forEach(function(c) {
    c.classList.toggle('selected', selected.indexOf(c.dataset.fund) >= 0);
  });
  document.getElementById('hint').textContent = selected.length === 0
    ? 'Vali v\u00e4hemalt 1 fond.'
    : selected.length + '/2 valitud.' + (selected.length < MAX_SELECT ? ' V\u00f5id lisada veel.' : '');
  updateTabBadges();
  if (selected.length > 0) {
    var url = new URL(window.location);
    url.searchParams.set('v', selected.join(','));
    history.replaceState(null, '', url);
  } else {
    var url2 = new URL(window.location);
    url2.searchParams.delete('v');
    history.replaceState(null, '', url2);
  }
  render();
}

function calcMWO(f1, f2) {
  var w1 = DATA.funds[f1].weights || {};
  var w2 = DATA.funds[f2].weights || {};
  var allKeys = Object.keys(w1).concat(Object.keys(w2));
  var seen = {};
  var mwo = 0;
  allKeys.forEach(function(k) {
    if (seen[k]) return;
    seen[k] = true;
    mwo += Math.min(w1[k]||0, w2[k]||0);
  });
  return mwo;
}

function topSharedHoldings(f1, f2, n) {
  var w1 = DATA.funds[f1].weights || {};
  var w2 = DATA.funds[f2].weights || {};
  var shared = [];
  Object.keys(w1).forEach(function(k) {
    if (w2[k]) shared.push({ name: k, weight: Math.min(w1[k], w2[k]) });
  });
  shared.sort(function(a, b) { return b.weight - a.weight; });
  return shared.slice(0, n);
}

function topDifferentHoldings(f1, f2, n) {
  var w1 = DATA.funds[f1].weights || {};
  var w2 = DATA.funds[f2].weights || {};
  var onlyF1 = [];
  Object.keys(w1).forEach(function(k) {
    var diff = w1[k] - (w2[k]||0);
    if (diff > 0.01) onlyF1.push({ name: k, weight: diff });
  });
  var onlyF2 = [];
  Object.keys(w2).forEach(function(k) {
    var diff = w2[k] - (w1[k]||0);
    if (diff > 0.01) onlyF2.push({ name: k, weight: diff });
  });
  onlyF1.sort(function(a, b) { return b.weight - a.weight; });
  onlyF2.sort(function(a, b) { return b.weight - a.weight; });
  return { f1: onlyF1.slice(0, n), f2: onlyF2.slice(0, n) };
}

var TULEVA_KEY = 'Tuleva';
function render() {
  var f = [].concat(selected);
  var has = f.length > 0;
  document.querySelectorAll('.section').forEach(function(s) { s.classList.toggle('visible', has); });
  if (!has) return;
  if (f.length === 1 && f[0] !== TULEVA_KEY) f.push(TULEVA_KEY);
  renderNarrative(f);
  renderOverlap(f);
  renderAssets(f);
  renderHoldings(f);
  renderNav(f);
  renderCTA(f);
  renderShareLink(f);
  renderScatter(f); renderSunburst(f); renderSectors(f); renderCountries(f);
}

function renderNarrative(funds) {
  var el = document.getElementById('narrativeText');
  el.innerHTML = '';
}

function renderShareLink(funds) {
  var el = document.getElementById('sectionShare');
  if (funds.length < 2) { el.classList.remove('visible'); return; }
  el.classList.add('visible');
  var shareUrl = new URL(window.location);
  shareUrl.searchParams.set('v', funds.join(','));
  el.innerHTML = '<div style="text-align:center;">' +
    '<button class="copy-link-btn" id="copyLinkBtn">Kopeeri v\u00f5rdluse link</button>' +
    '</div>';
  document.getElementById('copyLinkBtn').addEventListener('click', function() {
    var btn = this;
    navigator.clipboard.writeText(shareUrl.toString()).then(function() {
      btn.textContent = '\u2713 Kopeeritud!';
      setTimeout(function() { btn.textContent = 'Kopeeri v\u00f5rdluse link'; }, 2000);
    });
  });
}

function renderCTA(funds) {
  var el = document.getElementById('sectionCTA');
  if (funds.length < 2) { el.classList.remove('visible'); return; }
  el.classList.add('visible');

  var hasTuleva = funds.indexOf(TULEVA_KEY) >= 0;
  var otherFund = funds.find(function(f) { return f !== TULEVA_KEY; });
  var feeOther = otherFund ? (FEES[otherFund] || 0) : 0;
  var feeTuleva = FEES[TULEVA_KEY] || 0;
  var cheaper = feeTuleva < feeOther;

  var mwo = (hasTuleva && otherFund) ? Math.round(calcMWO(TULEVA_KEY, otherFund)) : 0;
  var ctaParts = [];

  if (hasTuleva && otherFund && mwo > 50) {
    ctaParts.push('Fondide portfellid kattuvad ' + mwo + '%.');
  }

  if (hasTuleva && otherFund && cheaper) {
    var diff = feeOther - feeTuleva;
    var eurosPer10k = Math.round(diff / 100 * 10000);
    if (eurosPer10k >= 10) {
      ctaParts.push('Tuleva tasu on ' + diff.toFixed(2) + ' protsendipunkti madalam \u2014 see on ' + eurosPer10k + '\u20ac aastas iga 10\u2009000\u20ac kohta.');
    }
  }

  var ctaText;
  if (ctaParts.length > 0) {
    ctaText = ctaParts.join(' ') + ' ' + CTA_CONFIG.text;
  } else {
    ctaText = CTA_CONFIG.text;
  }

  el.innerHTML = '<div class="cta-box">' +
    '<p>' + ctaText + '</p>' +
    '<a href="' + CTA_CONFIG.buttonUrl + '" class="cta-btn" target="_blank">' + CTA_CONFIG.buttonText + '</a>' +
    '</div>';
}

function renderAssets(funds) {
  var el = document.getElementById('assetBars');
  el.innerHTML = '';
  var order = ['stocks','etfs','pe','re','bonds','deposits','derivatives'];
  funds.forEach(function(f) {
    var ac = DATA.funds[f].asset_classes || {stocks:100};
    el.innerHTML += '<div class="asset-bar-label">' + LABELS[f] + '</div>';
    var bar = '<div class="asset-bar">';
    var AC_SHORT = { stocks:'Akt.', etfs:'Fondid', bonds:'V\u00f5lak.', pe:'PE', re:'Kinn.', deposits:'Hoi.', derivatives:'Tul.' };
    var total = order.reduce(function(s,k) { return s + (ac[k]||0); }, 0) || 100;
    order.forEach(function(k) {
      var pct = ac[k]||0;
      if (pct < 0.3) return;
      var w = pct / total * 100;
      var label = '';
      if (pct >= 15) label = AC_LABELS[k]+' '+pct.toFixed(0)+'%';
      else if (pct >= 10) label = AC_SHORT[k]+' '+pct.toFixed(0)+'%';
      bar += '<div class="seg" style="width:'+w+'%;background:'+AC_COLORS[k]+'" title="'+AC_LABELS[k]+' '+pct.toFixed(1)+'%">'+label+'</div>';
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

  var commonStart = cutoffStr;
  funds.forEach(function(f) {
    var nd = NAV[f]; if (!nd) return;
    for (var i = 0; i < nd.dates.length; i++) {
      if (nd.dates[i] >= cutoffStr) {
        if (nd.dates[i] > commonStart) commonStart = nd.dates[i];
        break;
      }
    }
  });

  var lastDate = funds.reduce(function(latest, f) {
    var nd = NAV[f]; if (!nd) return latest;
    var d = nd.dates[nd.dates.length - 1];
    return d > latest ? d : latest;
  }, '');
  var actualYears = lastDate ? ((new Date(lastDate) - new Date(commonStart)) / (365.25 * 86400000)) : navPeriodYears;
  var navSub = document.getElementById('navSub');
  if (Math.abs(actualYears - navPeriodYears) > 0.3) {
    navSub.textContent = 'Normaliseeritud 100-le. Tegelik periood: ' + actualYears.toFixed(1) + ' aastat (andmed alates ' + commonStart + ')';
  } else {
    navSub.textContent = 'Normaliseeritud 100-le valitud perioodi alguses';
  }

  var traces = funds.map(function(f) {
    var nd = NAV[f]; if (!nd) return null;
    var startIdx = 0;
    for (var i = 0; i < nd.dates.length; i++) {
      if (nd.dates[i] >= commonStart) { startIdx = i; break; }
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
    font: PL.font, paper_bgcolor: PL.paper_bgcolor, plot_bgcolor: PL.plot_bgcolor, margin: PL.margin,
    yaxis: { title: 'NAV (normaliseeritud = 100)' },
    xaxis: { title: '' }, legend: { orientation: 'h', y: 1.1 }, height: 380
  }, { responsive: true, displayModeBar: false });

  var retTable = document.getElementById('navReturnTable');
  var rows = funds.map(function(f) {
    var nd = NAV[f]; if (!nd) return null;
    var si = 0;
    for (var i = 0; i < nd.dates.length; i++) {
      if (nd.dates[i] >= commonStart) { si = i; break; }
    }
    var startVal = nd.values[si], endVal = nd.values[nd.values.length - 1];
    var startDate = nd.dates[si], endDate = nd.dates[nd.dates.length - 1];
    var years = (new Date(endDate) - new Date(startDate)) / (365.25 * 86400000);
    if (years <= 0 || !startVal) return null;
    var annReturn = (Math.pow(endVal / startVal, 1 / years) - 1) * 100;
    var fee = FEES[f] || 0;
    return { f: f, annReturn: annReturn, fee: fee, years: years };
  }).filter(Boolean);

  if (rows.length > 0) {
    var periodLabel = navPeriodYears + 'a';
    var html = '<table class="return-table"><thead><tr>' +
      '<th>Fond</th><th>Tootlus (' + periodLabel + ')</th><th>Jooksev tasu</th>' +
      '</tr></thead><tbody>';
    rows.forEach(function(r) {
      html += '<tr>' +
        '<td class="fund-name" style="color:' + COLORS[r.f] + '">' + LABELS[r.f] + '</td>' +
        '<td style="font-weight:700">' + (r.annReturn >= 0 ? '+' : '') + r.annReturn.toFixed(2) + '%</td>' +
        '<td>' + r.fee.toFixed(2) + '%</td>' +
        '</tr>';
    });
    if (rows.length === 2) {
      var feeDiff = Math.abs(rows[0].fee - rows[1].fee);
      if (feeDiff >= 0.05) {
        var expensive = rows[0].fee > rows[1].fee ? rows[0] : rows[1];
        var eurosPer10k = (feeDiff / 100 * 10000).toFixed(0);
        html += '<tr><td colspan="3" class="fee-diff" style="border-top:2px solid var(--g200);padding-top:10px;">' +
          LABELS[expensive.f] + ' tasu on <span class="num-accent">' + feeDiff.toFixed(2) + '</span> protsendipunkti k\u00f5rgem \u2014 ' +
          'see on <span class="num-accent">' + eurosPer10k + '\u20ac</span> aastas iga 10\u2009000\u20ac kohta.' +
          '</td></tr>';
      }
    }
    html += '</tbody></table>';
    html += '<div style="font-size:0.75rem;color:var(--g400);margin-top:0.5rem;">Graafik n\u00e4itab, kuidas fondide v\u00e4\u00e4rtus on muutunud. Isegi sarnase portfelliga fondid v\u00f5ivad ajalooliselt erineda, sest varasematel aastatel v\u00f5is nende koosseis olla teistsugune. Mineviku tulemused ei garanteeri tulevikku.</div>';
    retTable.innerHTML = html;
  } else {
    retTable.innerHTML = '';
  }
}

function renderOverlap(funds) {
  var section = document.getElementById('sectionOverlap');
  if (funds.length < 2) { section.classList.remove('visible'); return; }
  section.classList.add('visible');

  var f1 = funds[0], f2 = funds[1];
  var mwo = Math.round(calcMWO(f1, f2));
  var diffPct = 100 - mwo;
  var feeA = FEES[f1] || 0, feeB = FEES[f2] || 0;
  var feeDiff = Math.abs(feeA - feeB);
  var eurosPer10k = (feeDiff / 100 * 10000).toFixed(0);
  var top5 = topSharedHoldings(f1, f2, 5);
  var diff = topDifferentHoldings(f1, f2, 5);

  var verdict;
  if (mwo > 80 && feeDiff > 0.2) verdict = 'V\u00e4ga sarnane sisu, erinev hind.';
  else if (mwo > 80) verdict = 'V\u00e4ga sarnane sisu ja hind.';
  else if (mwo > 50 && feeDiff > 0.2) verdict = 'Osaliselt kattuvad, erinev hind.';
  else if (mwo > 50) verdict = 'Osaliselt kattuvad portfellid.';
  else verdict = 'Erinevad portfellid.';

  function holdingList(items) {
    if (!items.length) return '<div style="font-size:0.82rem;color:var(--g400);padding:0.5rem 0;">Ei ole</div>';
    return items.map(function(h) {
      var dn = h.name.length > 28 ? h.name.substring(0,28)+'\u2026' : h.name;
      return '<div style="display:flex;justify-content:space-between;padding:0.2rem 0;border-bottom:1px solid var(--g100);font-size:0.84rem;">' +
        '<span title="' + h.name + '">' + dn + '</span><span style="font-weight:600;font-variant-numeric:tabular-nums;">' + h.weight.toFixed(1) + '%</span>' +
        '</div>';
    }).join('');
  }

  var story = '<strong>' + mwo + '%</strong> m\u00f5lema fondi rahast l\u00e4heb samadesse kohtadesse.';

  var sharedHtml = top5.length > 0
    ? '<div>' +
        '<div style="font-weight:700;margin-bottom:0.4rem;font-size:0.8rem;color:var(--g600);text-transform:uppercase;letter-spacing:0.03em;">\u00dchised investeeringud</div>' +
        holdingList(top5) +
      '</div>'
    : '';

  var diffHtml = (diff.f1.length > 0 || diff.f2.length > 0)
    ? '<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">' +
        '<div>' +
          '<div style="font-weight:700;margin-bottom:0.2rem;font-size:0.8rem;color:' + COLORS[f1] + ';text-transform:uppercase;letter-spacing:0.03em;">' + LABELS[f1] + '</div>' +
          '<div style="font-size:0.75rem;color:var(--g400);margin-bottom:0.4rem;">suurem kaal</div>' +
          holdingList(diff.f1) +
        '</div>' +
        '<div>' +
          '<div style="font-weight:700;margin-bottom:0.2rem;font-size:0.8rem;color:' + COLORS[f2] + ';text-transform:uppercase;letter-spacing:0.03em;">' + LABELS[f2] + '</div>' +
          '<div style="font-size:0.75rem;color:var(--g400);margin-bottom:0.4rem;">suurem kaal</div>' +
          holdingList(diff.f2) +
        '</div>' +
      '</div>'
    : '';

  document.getElementById('overlapContent').innerHTML =
    '<div>' +
      '<div style="position:relative;height:28px;border-radius:8px;overflow:hidden;background:var(--g200);margin-bottom:0.4rem;">' +
        '<div style="height:100%;width:' + Math.max(mwo,2) + '%;background:var(--navy);border-radius:8px 0 0 8px;display:flex;align-items:center;' + (mwo >= 20 ? 'justify-content:center;' : 'justify-content:flex-end;padding-right:6px;') + 'color:#fff;font-size:0.9rem;font-weight:800;">' + mwo + '%</div>' +
      '</div>' +
      '<div style="display:flex;justify-content:space-between;font-size:0.78rem;color:var(--g400);margin-bottom:1rem;">' +
        '<span>\u00dchine osa</span><span>Erinev (' + diffPct + '%)</span>' +
      '</div>' +
      '<div class="narrative" style="margin-bottom:1rem;">' + story + '</div>' +
      '<div style="margin-bottom:1.5rem;">' +
        '<div class="summary-fees">' + LABELS[f1] + ' &nbsp; <strong>' + feeA.toFixed(2) + '%</strong> aastas</div>' +
        '<div class="summary-fees">' + LABELS[f2] + ' &nbsp; <strong>' + feeB.toFixed(2) + '%</strong> aastas</div>' +
        (feeDiff >= 0.05 ? '<div class="summary-diff" style="margin-top:0.5rem;">Vahe: ' + eurosPer10k + '\u20ac aastas iga 10\u2009000\u20ac kohta</div>' : '') +
        '<div class="summary-verdict">' + verdict + '</div>' +
      '</div>' +
      sharedHtml +
      '<div style="margin-top:1.25rem;">' + diffHtml + '</div>' +
    '</div>';
}

function renderHoldings(funds) {
  var el = document.getElementById('holdingsArea');
  var section = document.getElementById('sectionHoldings');
  if (funds.length >= 2) { section.classList.remove('visible'); return; }
  section.classList.add('visible');
  el.innerHTML = '';
  var grid = document.createElement('div');
  grid.className = 'holdings-grid';
  grid.style.gridTemplateColumns = 'repeat(' + funds.length + ', 1fr)';

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
        tag.style.cssText = 'font-size:0.6rem;font-weight:700;color:#fff;background:' + catColors[item.cat] + ';padding:1px 4px;border-radius:3px;flex-shrink:0;';
        tag.textContent = catTags[item.cat];
        row.appendChild(tag);
      }

      var nameSpan = document.createElement('span');
      nameSpan.style.cssText = 'flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:' + catColors[item.cat] + ';';
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
  var detailScatter = document.getElementById('detailScatter');
  if (funds.length < 2) {
    detailScatter.style.display = 'none';
    return;
  }
  detailScatter.style.display = 'block';
  var f1 = funds[0], f2 = funds[1];
  var w1 = DATA.funds[f1].weights || {};
  var w2 = DATA.funds[f2].weights || {};
  var allKeysObj = {};
  Object.keys(w1).forEach(function(k) { allKeysObj[k] = true; });
  Object.keys(w2).forEach(function(k) { allKeysObj[k] = true; });
  var xs = [], ys = [], names = [];
  Object.keys(allKeysObj).forEach(function(k) {
    var a = w1[k]||0, b = w2[k]||0;
    if (a > 0.02 || b > 0.02) { xs.push(a); ys.push(b); names.push(k.replace(/^(PE|RE|BOND|ETF)\|/, '')); }
  });
  document.getElementById('scatterSub').textContent =
    LABELS[f1] + ' vs ' + LABELS[f2] + '. Iga t\u00e4pp = \u00fcks investeering. Diagonaalil = identne kaal.';
  var mx = Math.max.apply(null, xs.concat(ys)) * 1.05;
  var r = DATA.correlation_matrix[f1 + '|' + f2] || 0;
  Plotly.newPlot('chartScatter', [
    { x: xs, y: ys, mode:'markers', showlegend:false,
      marker:{size:8,color:'#0081EE',opacity:0.45}, text:names,
      hovertemplate:'%{text}<br>'+LABELS[f1]+': %{x:.2f}%<br>'+LABELS[f2]+': %{y:.2f}%<extra></extra>' },
    { x:[0,mx], y:[0,mx], mode:'lines', line:{dash:'dash',color:'#ccc'}, showlegend:false }
  ], { font: PL.font, paper_bgcolor: PL.paper_bgcolor, plot_bgcolor: PL.plot_bgcolor, margin: PL.margin,
    xaxis:{title:LABELS[f1]+' (%)'},
    yaxis:{title:LABELS[f2]+' (%)'},
    height:450,
    annotations:[{ x:0.95, y:0.05, xref:'paper', yref:'paper',
      text:'r = ' + r.toFixed(3), showarrow:false,
      font:{size:16,color:'#333'}, bgcolor:'rgba(255,255,255,0.8)' }]
  }, {responsive:true, displayModeBar:false});
}

function renderSunburst(funds) {
  var area = document.getElementById('sunburstArea');
  area.innerHTML = '';
  var sbWidth = funds.length === 2 ? 'calc(50% - 0.5rem)' : '100%';

  funds.forEach(function(f) {
    var fd = DATA.funds[f];
    var nodes = [];
    var rootId = f + '_root';
    var ac = fd.asset_classes || { stocks: 100 };
    var pal = ['#0081EE','#16a34a','#dc2626','#F7A600','#7c3aed','#D63B84','#0d9488','#ea580c','#6b7280'];

    if (fd.etf_breakdown) {
      var acBaseColors = { stocks:'#0081EE', etfs:'#60a5fa', bonds:'#6b7280', deposits:'#d1d5db' };

      var stocksPct = (ac.stocks || 0) + (ac.etfs || 0);
      var bondsPct = ac.bonds || 0;
      var depositsPct = ac.deposits || 0;
      var etfsPct = ac.etfs || 0;

      var stocksId = f + '_ac_stocks';
      if (stocksPct > 0.5) nodes.push({id: stocksId, label: 'Aktsiad ' + (ac.stocks||0).toFixed(0) + '%', parentId: rootId, value: 0, color: acBaseColors.stocks});
      if (etfsPct > 0.5) {
        var etfsId = f + '_ac_etfs';
        nodes.push({id: etfsId, label: 'ETF-id ' + etfsPct.toFixed(0) + '%', parentId: rootId, value: etfsPct, color: acBaseColors.etfs});
      }
      if (bondsPct > 0.5) nodes.push({id: f+'_ac_bonds', label: 'V\u00f5lakirjad ' + bondsPct.toFixed(0) + '%', parentId: rootId, value: bondsPct, color: acBaseColors.bonds});
      if (depositsPct > 0.5) nodes.push({id: f+'_ac_deposits', label: 'Hoiused ' + depositsPct.toFixed(0) + '%', parentId: rootId, value: depositsPct, color: acBaseColors.deposits});

      var topStocks = (fd.top_holdings || []).slice(0, 40);
      var assignedStockWeight = 0;
      topStocks.forEach(function(h, si) {
        var sn = h.name.split(/\s/).slice(0, 2).join(' ');
        var hue = 215 + (si % 8) * 3;
        var light = 50 + (si % 6) * 4;
        var col = 'hsl(' + hue + ', 70%, ' + light + '%)';
        nodes.push({id: f+'_s'+si, label: sn, parentId: stocksId, value: h.weight, color: col});
        assignedStockWeight += h.weight;
      });
      var restStocks = (ac.stocks || 0) - assignedStockWeight;
      if (restStocks > 0.5) {
        nodes.push({id: f+'_srest', label: 'Muud aktsiad', parentId: stocksId, value: restStocks, color: '#93c5fd'});
      }

    } else if (fd.pe_holdings && fd.pe_holdings.length > 0) {
      var stockId = f + '_stocks';
      fd.top_holdings.slice(0, 8).forEach(function(h, i) {
        nodes.push({id: f+'_s'+i, label: h.name.split(/\s/).slice(0,2).join(' '), parentId: stockId, value: h.weight, color: '#0081EE'});
      });
      var sr = fd.top_holdings.slice(8).reduce(function(s,h) { return s+h.weight; }, 0);
      if (sr > 0) nodes.push({id: f+'_srest', label: 'Muud aktsiad', parentId: stockId, value: sr, color: '#93c5fd'});

      if (fd.etf_holdings && fd.etf_holdings.length > 0) {
        var etfId = f + '_etfs';
        fd.etf_holdings.forEach(function(h, i) {
          nodes.push({id: f+'_e'+i, label: h.name.length>20?h.name.substring(0,20)+'\u2026':h.name, parentId: etfId, value: h.weight, color: '#93c5fd'});
        });
        nodes.push({id: etfId, label: 'ETF-id', parentId: rootId, value: 0, color: '#60a5fa'});
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

      nodes.push({id: stockId, label: 'Aktsiad', parentId: rootId, value: 0, color: '#0081EE'});
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
        bySector['Muu'].push({ name: 'Muud (' + (fd.n_stocks - holdings.length) + ')', weight: restWeight, _isRest: true });
      }

      var cl = Object.keys(bySector).map(function(s) {
        var hs = bySector[s];
        return {s:s, hs:hs, total:hs.reduce(function(sum,h){return sum+h.weight;},0)};
      }).sort(function(a,b){return b.total-a.total;});
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
    div.style.cssText = 'width:' + sbWidth + ';min-width:0;flex-shrink:0;';
    var divId = 'sunburst_' + f.replace(/\s/g, '_');
    div.id = divId;
    area.appendChild(div);

    Plotly.newPlot(divId, [{
      type: 'sunburst', ids: ids, labels: labels, parents: parents, values: vals, customdata: cdata,
      branchvalues: 'total',
      hovertemplate: '<b>%{label}</b><br>%{customdata:.2f}%<extra></extra>',
      textinfo: 'label',
      insidetextorientation: 'radial',
      marker: { colors: cols, line: { width: 1, color: '#fff' } }
    }], {
      font: PL.font, paper_bgcolor: PL.paper_bgcolor, plot_bgcolor: PL.plot_bgcolor,
      height: 420, margin: { t: 40, r: 5, b: 5, l: 5 },
      title: { text: LABELS[f] || f, font: { size: 14, color: COLORS[f] } }
    }, { responsive: true, displayModeBar: false });
  });
}

function renderSectors(funds) {
  var sectorOrder = DATA.acwi_sector_order.slice(0,11).concat([]);
  funds.forEach(function(f) {
    Object.keys(DATA.funds[f].sectors).forEach(function(s) {
      if (sectorOrder.indexOf(s) === -1) sectorOrder.push(s);
    });
  });
  var sectorLabels = sectorOrder.map(trSector);
  var traces = funds.map(function(f) {
    return {
      y: sectorLabels, x: sectorOrder.map(function(s) { return DATA.funds[f].sectors[s]||0; }),
      type:'bar', orientation:'h', name: LABELS[f], marker:{color:COLORS[f]}
    };
  });
  Plotly.newPlot('chartSectors', traces, {
    font: PL.font, paper_bgcolor: PL.paper_bgcolor, plot_bgcolor: PL.plot_bgcolor, margin: {t:30,r:20,b:50,l:140},
    barmode:'group', xaxis:{title:'Osakaal (%)'},
    yaxis:{autorange:'reversed'}, legend:{orientation:'h',y:1.08},
    height: Math.max(400, sectorOrder.length*28)
  }, {responsive:true, displayModeBar:false});
}

function renderCountries(funds) {
  var cs = {};
  funds.forEach(function(f) {
    var countries = DATA.funds[f].countries;
    Object.keys(countries).forEach(function(c) { cs[c] = (cs[c]||0) + countries[c]; });
  });
  var order = Object.keys(cs).sort(function(a,b) { return cs[b]-cs[a]; }).slice(0,12);
  var countryLabels = order.map(trCountry);
  var traces = funds.map(function(f) {
    return {
      y: countryLabels, x: order.map(function(c) { return DATA.funds[f].countries[c]||0; }),
      type:'bar', orientation:'h', name:LABELS[f], marker:{color:COLORS[f]}
    };
  });
  Plotly.newPlot('chartCountries', traces, {
    font: PL.font, paper_bgcolor: PL.paper_bgcolor, plot_bgcolor: PL.plot_bgcolor, margin: {t:30,r:20,b:50,l:140},
    barmode:'group',
    yaxis:{autorange:'reversed'}, xaxis:{title:'Osakaal (%)'},
    legend:{orientation:'h',y:1.1}, height: Math.max(350, order.length*30)
  }, {responsive:true, displayModeBar:false});
}

</script>
</main>
