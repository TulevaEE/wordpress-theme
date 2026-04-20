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
  .fund-comparison h1 {
    font-size: 2rem; font-weight: 800; line-height: 1.2; margin-bottom: 0.5rem; color: var(--navy);
  }
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
  .fund-comparison .fc-badge { display: inline-block; font-size: 0.65rem; padding: 1px 6px; border-radius: 4px;
           font-weight: 600; margin-top: 3px; }
  .fund-comparison .fc-badge-low { background: #dcfce7; color: #166534; }
  .fund-comparison .fc-badge-high { background: #fee2e2; color: #991b1b; }
  .fund-comparison .fc-badge-proxy { background: #fef3c7; color: #92400e; font-size: 0.7rem; margin-top: 2px; }
  .fund-comparison .fund-card .ck { display: none; position: absolute; top: 6px; right: 8px; width: 20px; height: 20px;
                   background: var(--navy); border-radius: 50%; color: #fff; font-size: 12px;
                   line-height: 20px; text-align: center; }
  .fund-comparison .fund-card.selected .ck { display: block; }
  .fund-comparison .hint { font-size: 0.8rem; color: var(--g400); margin-bottom: 2rem; }
  .fund-comparison .fc-section { margin-bottom: 2.5rem; display: none; }
  .fund-comparison .fc-section.visible { display: block; }
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
  .fund-comparison .fc-footer { font-size: 0.8rem; color: var(--g600); border-top: 1px solid var(--g200);
            padding-top: 1.5rem; margin-top: 2rem; line-height: 1.7; }
  .fund-comparison .meth-toggle { cursor: pointer; color: var(--navy); font-size: 1rem; font-weight: 600;
                 display: inline-flex; align-items: center; gap: 6px; padding: 0.6rem 1.2rem;
                 border: 2px solid var(--navy); border-radius: 8px; transition: all 0.15s; }
  .fund-comparison .meth-toggle:hover { background: var(--navy); color: #fff; }
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
  .fund-comparison .selected-chips-wrap { margin-bottom: 1rem; }
  .fund-comparison .selected-chips-title { font-size: 0.85rem; font-weight: 600; text-transform: uppercase;
                          letter-spacing: 0.05em; color: var(--g400); margin-bottom: 0.5rem; }
  .fund-comparison .selected-chips { display: flex; flex-wrap: wrap; gap: 0.5rem; }
  .fund-comparison .selected-chip { display: flex; align-items: center; justify-content: space-between;
                   width: calc(50% - 0.25rem); min-width: 0; box-sizing: border-box; padding: 0.7rem 1rem;
                   background: var(--bg-pale); border: 2px solid var(--navy); border-radius: 10px;
                   color: var(--navy); }
  .fund-comparison .selected-chip .chip-info .chip-name { font-weight: 600; font-size: 0.88rem; line-height: 1.3; }
  .fund-comparison .selected-chip .chip-info .chip-fee { font-size: 0.72rem; color: var(--g400); margin-top: 2px; }
  .fund-comparison .selected-chip .chip-remove { display: inline-flex; align-items: center; justify-content: center;
                           width: 22px; height: 22px; border-radius: 50%; background: var(--navy);
                           color: #fff; font-size: 12px; cursor: pointer; line-height: 1;
                           transition: background 0.15s; flex-shrink: 0; }
  .fund-comparison .selected-chip .chip-remove:hover { background: var(--red); }
  .fund-comparison .selected-chip.placeholder { border: 2px dashed var(--g200); background: none; color: var(--g400);
                                pointer-events: none; }
  .fund-comparison .selected-chip.placeholder .chip-name { font-weight: 600; font-size: 0.88rem; color: var(--g400); }
  .fund-comparison .venn-diagram { text-align:center; margin-bottom:1.5rem; padding:1rem 0; }
  .fund-comparison .venn-diagram svg { max-width:480px; width:100%; height:auto; }
  .fund-comparison .venn-fee-compare { text-align:center; margin-bottom:2rem; }
  .fund-comparison .venn-fee-row { display:flex; justify-content:center; align-items:baseline; gap:2rem; flex-wrap:wrap; margin-bottom:0.5rem; }
  .fund-comparison .venn-fee-item { font-size:0.95rem; color:var(--g600); }
  .fund-comparison .venn-fee-item .fee-val { font-family:Georgia,'Times New Roman',serif; font-weight:700; font-size:1.3rem; }
  .fund-comparison .venn-fee-item .fee-val.cheap { color:var(--blue); }
  .fund-comparison .venn-fee-item .fee-val.expensive { color:var(--g600); }
  .fund-comparison .venn-fee-ratio { font-size:1.1rem; font-weight:700; color:var(--navy); margin-top:0.25rem; }
  @media (max-width: 640px) {
    .fund-comparison h1 { font-size: 1.5rem; }
    .fund-comparison .fund-grid { grid-template-columns: 1fr 1fr; }
    .fund-comparison .info-icon .info-tip { width: 240px; left: 0; transform: none; }
    .fund-comparison .summary-card { padding: 1.25rem 1.25rem; }
    .fund-comparison .venn-diagram svg { max-width:360px; }
  }
  .fund-comparison .fc-tooltip { position:fixed; pointer-events:none; background:var(--navy); color:#fff;
    padding:4px 10px; border-radius:6px; font-size:0.82rem; font-weight:600;
    white-space:nowrap; z-index:9999; display:none; }
</style>

<div class="fund-comparison">
<h1>Fondide r&ouml;ntgen</h1>
<p class="subtitle">Paljude pensionifondide portfellid on &uuml;sna sarnased. V&otilde;rdle oma fondi sisu ja vaata, mille eest sa maksad.</p>
<div id="dataFreshness" style="font-size:0.85rem;color:var(--g400);margin-bottom:1.5rem;"></div>
<div class="picker-label">Soovi korral vali 2 fondi</div>
<div class="provider-tabs" id="providerTabs"></div>
<div class="fund-grid" id="fundGrid"></div>
<div class="selected-chips-wrap" id="selectedChipsWrap">
  <div class="selected-chips-title">Valitud fondid</div>
  <div class="selected-chips" id="selectedChips"></div>
</div>
<p class="hint" id="hint">Vali v&auml;hemalt 1 fond.</p>
<p style="font-size:0.8rem;color:var(--g400);margin-top:-1rem;margin-bottom:2rem;">Ei tea oma fondi? <a href="https://pension.tuleva.ee/login" style="color:var(--g400);text-decoration:underline;">Vaata siit &rarr;</a></p>

<div class="fc-section" id="sectionNarrative"><div class="narrative" id="narrativeText"></div></div>

<div class="fc-section" id="sectionOverlap">
  <div class="section-title" style="font-size:1.35rem;">Kas fondid investeerivad samasse kohta?</div>
  <div class="venn-diagram" id="vennDiagram"></div>
  <div class="venn-fee-compare" id="vennFeeCompare"></div>
  <div id="overlapDisclaimer" style="font-size:0.78rem;color:var(--g400);text-align:center;margin-bottom:1.5rem;max-width:640px;margin-left:auto;margin-right:auto;"></div>
</div>

<div class="fc-section" id="sectionAssets">
  <div class="section-title">Varaklass</div>
  <div class="section-sub">Kuhu fond investeerib: aktsiad, v&otilde;lakirjad, erakapital, kinnisvara?</div>
  <div id="assetBars"></div>
</div>

<div class="fc-section" id="sectionHoldingsCompare">
  <div id="holdingsCompareContent"></div>
</div>

<div class="fc-section" id="sectionHoldings">
  <div class="section-title">Suurimad investeeringud</div>
  <div class="section-sub">Enamik fonde ei osta aktsiaid otse, vaid l&auml;bi b&ouml;rsil kaubeldavate fondide (ETF). Vaatame ETF-ide sisse ja n&auml;itame tegelikke aktsiaid. Kui m&otilde;ne ETFi andmed pole k&auml;ttesaadavad, kasutame sarnase fondi (proxy) andmeid. Seet&otilde;ttu ei pruugi andmed olla t&auml;psed.</div>
  <div id="holdingsArea"></div>
</div>

<div class="fc-section" id="sectionNav">
  <div class="section-title">Kas fondide tootlused on sarnased?</div>
  <div class="section-sub" id="navSub">Normaliseeritud 100-le valitud perioodi alguses</div>
  <div id="corrBarContainer"></div>
  <div class="nav-period-btns" id="navPeriodBtns">
    <button class="nav-period-btn" data-years="10">10a</button>
    <button class="nav-period-btn active" data-years="5">5a</button>
    <button class="nav-period-btn" data-years="2">2a</button>
  </div>
  <div class="chart-container" id="chartNav"></div>
  <div id="navReturnTable"></div>
</div>

<div class="fc-section" id="sectionCTA"></div>

<div class="fc-section" id="sectionShare"></div>

<div class="fc-section" id="sectionDetails">
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
    <div id="detailAllHoldings" style="margin-bottom:2rem;">
      <div class="section-title">K&otilde;ik investeeringud</div>
      <div class="section-sub" id="allHoldingsSub" style="max-width:700px;">Enamik fonde ei osta aktsiaid otse, vaid l&auml;bi b&ouml;rsil kaubeldavate fondide (ETF). Vaatame ETF-ide sisse ja n&auml;itame tegelikke aktsiaid. Kui m&otilde;ne ETFi andmed pole k&auml;ttesaadavad, kasutame sarnase fondi (proxy) andmeid. Seet&otilde;ttu ei pruugi andmed olla t&auml;psed.</div>
      <div style="margin-bottom:0.5rem;"><button id="csvDownloadBtn" style="padding:6px 14px;border:1px solid var(--g200);border-radius:6px;background:#fff;cursor:pointer;font-size:0.82rem;font-weight:600;color:var(--navy);">Laadi alla CSV</button></div>
      <div id="allHoldingsList" style="max-height:400px;overflow-y:auto;border:1px solid var(--g200);border-radius:8px;padding:0.5rem;"></div>
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
    <div id="detailESMA" style="margin-bottom:2rem;">
      <div class="section-title">Kapiindeksi anal&uuml;&uuml;s (ESMA metoodika)</div>
      <div class="section-sub" style="max-width:700px;">Euroopa V&auml;&auml;rtpaberituruj&auml;relevalve (ESMA) t&ouml;&ouml;tas 2016. aastal v&auml;lja meetodi, kuidas tuvastada fonde, mis n&auml;evad v&auml;lja erinevad, aga liiguvad tegelikult sarnaselt. Kui fond k&uuml;sib k&otilde;rgemat tasu, aga ei paku tegelikku erinevust, on tegemist nn kapiindeksiga. ESMA m&otilde;&otilde;dab seda kahe n&auml;itajaga:</div>
      <div id="esmaContent"></div>
    </div>
  </div>
</div>

<div class="fc-footer">
  <div>Andmed: investeerimisaruanded, pensionikeskus.ee, iShares, Swedbank Robur.</div>
  <div style="margin-top:0.5rem;"><a href="<?php echo home_url('/fondide-vordlus-allikad/'); ?>" style="color:var(--blue);font-weight:600;">Kuidas need numbrid on leitud? Andmeallikad ja metoodika &rarr;</a></div>
  <div style="margin-top:1rem;padding:0.8rem 1rem;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;font-size:0.85rem;line-height:1.6;">
    <strong>&#9888; Hoiatus.</strong> See leht on loodud informatiivsel eesm&auml;rgil. Andmed v&otilde;ivad sisaldada ebat&auml;psusi, kontrolli fakte fondivalitseja ametlikest allikatest. See ei ole investeerimisn&otilde;uanne.
    Osade fondide puhul kasutatakse sarnaste ETFide andmeid l&auml;hendusena (proxy), mist&otilde;ttu v&otilde;ivad kattuvusn&auml;itajad olla ligikaudsed.
    Erinevused &plusmn;2&ndash;3 protsendipunkti kattuvuses ei ole statistiliselt olulised.
    Andmed p&otilde;hinevad fondide investeerimisaruannetel ning v&otilde;ivad olla mitu kuud vanad &mdash; portfelli koosseis muutub pidevalt.
  </div>
</div>
<div class="fc-tooltip" id="fcTooltip"></div>
</div>

<script>
(function() {
var DATA_BASE = '<?php echo get_template_directory_uri(); ?>/fund-comparison/data/';
let DATA = null, NAV = null, RET_CORR = null, OVERLAP = null, ETF_META = null;
const selected = [];
const MAX_SELECT = 2;

const COLORS = {
  'Tuleva': '#013063', 'Tuleva Võlakirjad': '#4b9cd3',
  'Luminor 16-50': '#D63B84', 'Luminor Indeks': '#ec4899',
  'Luminor 50-56': '#f472b6', 'Luminor 56+': '#fb7185', 'Luminor 61-65': '#fda4af',
  'SEB Indeks': '#51c26c', 'SEB 18+': '#f97316', 'SEB 55+': '#ea580c',
  'SEB 60+': '#fb923c', 'SEB 65+': '#fdba74',
  'Swedbank K1960': '#e8a317', 'Swedbank K1970': '#e8a317',
  'Swedbank K1980': '#F7A600', 'Swedbank K1990': '#d4900e',
  'Swedbank Indeks': '#ca8a04', 'Swedbank 2000-09': '#eab308', 'Swedbank Konservatiivne': '#fde047',
  'LHV Ettev\u00f5tlik': '#7c3aed', 'LHV Julge': '#9333ea',
  'LHV Rahulik': '#a78bfa', 'LHV Indeks': '#2563eb', 'LHV Tasakaalukas': '#8b5cf6',
};
const LABELS = {
  'Tuleva': 'Tuleva Maailma Aktsiad', 'Tuleva Võlakirjad': 'Tuleva Võlakirjad',
  'Luminor 16-50': 'Luminor 16\u201350', 'Luminor Indeks': 'Luminor Indeks',
  'Luminor 50-56': 'Luminor 50\u201356', 'Luminor 56+': 'Luminor 56+', 'Luminor 61-65': 'Luminor 61\u201365',
  'SEB Indeks': 'SEB Indeks', 'SEB 18+': 'SEB 18+', 'SEB 55+': 'SEB 55+',
  'SEB 60+': 'SEB 60+', 'SEB 65+': 'SEB 65+',
  'Swedbank K1960': 'Swedbank 1960\u201369', 'Swedbank K1970': 'Swedbank 1970\u201379',
  'Swedbank K1980': 'Swedbank 1980\u201389', 'Swedbank K1990': 'Swedbank 1990\u201399',
  'Swedbank Indeks': 'Swedbank Indeks', 'Swedbank 2000-09': 'Swedbank 2000\u201309',
  'Swedbank Konservatiivne': 'Swedbank Kons.',
  'LHV Ettev\u00f5tlik': 'LHV Ettev\u00f5tlik', 'LHV Julge': 'LHV Julge',
  'LHV Rahulik': 'LHV Rahulik', 'LHV Indeks': 'LHV Indeks', 'LHV Tasakaalukas': 'LHV Tasakaalukas',
};
const SHORT = {
  'Tuleva': 'Tuleva', 'Tuleva Võlakirjad': 'Tuleva VK',
  'Luminor 16-50': 'Luminor 16\u201350', 'Luminor Indeks': 'Lum. Ind.',
  'Luminor 50-56': 'Lum. 50\u201356', 'Luminor 56+': 'Lum. 56+', 'Luminor 61-65': 'Lum. 61\u201365',
  'SEB Indeks': 'SEB Indeks', 'SEB 18+': 'SEB 18+', 'SEB 55+': 'SEB 55+',
  'SEB 60+': 'SEB 60+', 'SEB 65+': 'SEB 65+',
  'Swedbank K1960': 'Swedbank 60\u201369', 'Swedbank K1970': 'Swedbank 70\u201379',
  'Swedbank K1980': 'Swedbank 80\u201389', 'Swedbank K1990': 'Swedbank 90\u201399',
  'Swedbank Indeks': 'SWB Ind.', 'Swedbank 2000-09': 'SWB 00\u201309', 'Swedbank Konservatiivne': 'SWB Kons.',
  'LHV Ettev\u00f5tlik': 'LHV Ettev.', 'LHV Julge': 'LHV Julge',
  'LHV Rahulik': 'LHV Rah.', 'LHV Indeks': 'LHV Ind.', 'LHV Tasakaalukas': 'LHV Tasak.',
};
// Fallback fees — overridden by DATA.fees from fund_data.json when available
let FEES = {
  'Tuleva': 0.28, 'Tuleva Võlakirjad': 0.28,
  'Luminor 16-50': 1.08, 'Luminor Indeks': 0.27,
  'Luminor 50-56': 1.13, 'Luminor 56+': 1.14, 'Luminor 61-65': 0.88,
  'SEB Indeks': 0.28, 'SEB 18+': 0.95, 'SEB 55+': 0.99,
  'SEB 60+': 0.90, 'SEB 65+': 0.50,
  'Swedbank K1960': 0.74, 'Swedbank K1970': 0.74,
  'Swedbank K1980': 0.72, 'Swedbank K1990': 0.31,
  'Swedbank Indeks': 0.27, 'Swedbank 2000-09': 0.75, 'Swedbank Konservatiivne': 0.47,
  'LHV Ettev\u00f5tlik': 1.57, 'LHV Julge': 1.21,
  'LHV Rahulik': 0.53, 'LHV Indeks': 0.27, 'LHV Tasakaalukas': 1.13,
};
const AC_COLORS = { stocks:'#0081EE', etfs:'#60a5fa', bonds:'#6b7280',
                    pe:'#7c3aed', re:'#059669', deposits:'#d1d5db', derivatives:'#f59e0b', gold:'#d4a017', unknown:'#fca5a5' };
const AC_LABELS = { stocks:'Aktsiad', etfs:'Fondid (l\u00e4bipaistmatud)', bonds:'V\u00f5lakirjad',
                    pe:'Erakapital', re:'Kinnisvara', deposits:'Hoiused', derivatives:'Tuletisinstr.', gold:'Kuld', unknown:'Andmed puuduvad' };
const PL = {
  font: { family: '-apple-system, BlinkMacSystemFont, sans-serif', size: 12, color: '#013063' },
  paper_bgcolor: 'rgba(0,0,0,0)', plot_bgcolor: 'rgba(0,0,0,0)',
  margin: { t: 30, r: 20, b: 50, l: 50 },
};

const COUNTRY_ET = {
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
  'Eesti (PE/RE/v\u00f5lak.)':'Eesti (PE/RE/v\u00f5lak.)',
};
const SECTOR_ET = {
  'Information Technology':'IT', 'Financials':'Finants', 'Industrials':'T\u00f6\u00f6stus',
  'Consumer Discretionary':'Tarbijakaup', 'Health Care':'Tervishoid',
  'Communication':'Kommunikatsioon', 'Consumer Staples':'P\u00f5hitarve',
  'Materials':'Materjalid', 'Energy':'Energia', 'Utilities':'Kommunaalteenused',
  'Real Estate':'Kinnisvara', 'Other':'Muu', 'Direct Investment':'Otse',
  'Erakapital':'Erakapital', 'Kinnisvara':'Kinnisvara', 'V\u00f5lakirjad':'V\u00f5lakirjad',
  'ETF-id':'ETF-id',
};
function trCountry(c) { return COUNTRY_ET[c] || c; }
function trSector(s) { return SECTOR_ET[s] || s; }

let navPeriodYears = 5;

// Fund switch deadlines: March 31 and November 30 each year
const CTA_CONFIG = (() => {
  const now = new Date();
  const y = now.getFullYear();
  const mar31 = new Date(y, 2, 31);
  const nov30 = new Date(y, 10, 30);
  let deadline;
  if (now <= mar31) deadline = `31. m\u00e4rts ${y}`;
  else if (now <= nov30) deadline = `30. november ${y}`;
  else deadline = `31. m\u00e4rts ${y + 1}`;
  return {
    text: `Fondivahetus on tasuta ja v\u00f5tab paar minutit. J\u00e4rgmine t\u00e4htaeg: ${deadline}.`,
    buttonText: 'Vaata l\u00e4hemalt \u2192',
    buttonUrl: 'https://pension.tuleva.ee',
  };
})();

// Toggles & URL params
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('detailsToggle').addEventListener('click', function() {
    const body = document.getElementById('detailsBody');
    body.classList.toggle('show');
    this.textContent = body.classList.contains('show') ? 'Peida detailid' : 'Vaata detailsemalt';
  });
  // Custom tooltip for data-tip elements
  const tip = document.getElementById('fcTooltip');
  document.addEventListener('mousemove', (e) => {
    const el = e.target.closest('[data-tip]');
    if (el) {
      tip.textContent = el.dataset.tip;
      tip.style.display = 'block';
      tip.style.left = (e.clientX + 10) + 'px';
      tip.style.top = (e.clientY - 30) + 'px';
    } else {
      tip.style.display = 'none';
    }
  });
  document.addEventListener('mouseleave', () => { tip.style.display = 'none'; }, true);
  document.getElementById('navPeriodBtns').addEventListener('click', (e) => {
    const btn = e.target.closest('.nav-period-btn');
    if (!btn) return;
    navPeriodYears = parseInt(btn.dataset.years);
    document.querySelectorAll('.nav-period-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    if (selected.length > 0) renderNav([...selected]);
  });
});

Promise.all([
  fetch(DATA_BASE + 'fund_data.json').then(r => r.json()),
  fetch(DATA_BASE + 'nav_data.json').then(r => r.json()),
  fetch(DATA_BASE + 'return_correlations.json').then(r => r.json()),
  fetch(DATA_BASE + 'overlap_stats.json').then(r => r.json()),
]).then(([data, nav, retCorr, overlap]) => {
  DATA = data; NAV = nav; RET_CORR = retCorr; OVERLAP = overlap;
  // Load ETF metadata for coverage badges (non-blocking)
  fetch(DATA_BASE + 'etf_metadata.json').then(r=>r.json()).then(d=>{ETF_META=d;
    // Re-render fund cards if already built
    const grid=document.getElementById('fundGrid');
    if(grid&&grid.children.length>0){const active=document.querySelector('.provider-tab.active');
    if(active)showProvider(active.textContent);}
  }).catch(()=>{});
  // Use fees from pipeline data if available (single source of truth)
  if (DATA.fees) { FEES = DATA.fees; }
  // Show data freshness notice
  const dataMonth = DATA.data_month || '';
  if (dataMonth) {
    const MONTHS_ET = {
      '01':'jaanuar','02':'veebruar','03':'m\u00e4rts','04':'aprill','05':'mai','06':'juuni',
      '07':'juuli','08':'august','09':'september','10':'oktoober','11':'november','12':'detsember'
    };
    const [yr, mo] = dataMonth.split('-');
    const moName = MONTHS_ET[mo] || mo;
    document.getElementById('dataFreshness').textContent = `Andmed seisuga: ${moName} ${yr}`;
  }
  buildGrid();
  renderSelectedChips();
  // Shareable URL: ?v=Fund1,Fund2
  const urlParams = new URLSearchParams(window.location.search);
  const vParam = urlParams.get('v');
  if (vParam) {
    const names = vParam.split(',').map(s => s.trim());
    names.forEach(name => {
      const key = DATA.fund_order.find(k => k === name || (LABELS[k]||'') === name);
      if (key && !selected.includes(key) && selected.length < MAX_SELECT) {
        selected.push(key);
      }
    });
    if (selected.length > 0) {
      // Switch to the first selected fund's provider tab
      const firstProvider = DATA.funds[selected[0]].provider || PROVIDER_ORDER[0];
      document.querySelectorAll('.provider-tab').forEach(t => {
        t.classList.toggle('active', t.textContent === firstProvider);
      });
      showProvider(firstProvider);
      updateTabBadges();
      renderSelectedChips();
      document.getElementById('hint').textContent = `${selected.length}/2 valitud.${selected.length<MAX_SELECT?' V\u00f5id lisada veel.':''}`;
      render();
    }
  }
});

let fundGroups = {};
const PROVIDER_ORDER = ['Tuleva','Swedbank','LHV','SEB','Luminor'];

function buildGrid() {
  DATA.fund_order.forEach(key => {
    const p = DATA.funds[key].provider || 'Muu';
    if (!fundGroups[p]) fundGroups[p] = [];
    fundGroups[p].push(key);
  });
  // Build provider tabs
  const tabs = document.getElementById('providerTabs');
  PROVIDER_ORDER.forEach((p, i) => {
    if (!fundGroups[p]) return;
    const tab = document.createElement('button');
    tab.className = 'provider-tab' + (i === 0 ? ' active' : '');
    tab.textContent = p;
    tab.addEventListener('click', () => {
      tabs.querySelectorAll('.provider-tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      showProvider(p);
      updateTabBadges();
    });
    tabs.appendChild(tab);
  });
  showProvider(PROVIDER_ORDER[0]);
}

function showProvider(provider) {
  const grid = document.getElementById('fundGrid');
  grid.innerHTML = '';
  (fundGroups[provider] || []).forEach(key => {
    const fee = FEES[key];
    const feeText = fee ? 'Tasu ' + fee.toFixed(2) + '%' : '';
    const bc = fee && fee <= 0.5 ? 'fc-badge-low' : 'fc-badge-high';
    const card = document.createElement('div');
    card.className = 'fund-card' + (selected.includes(key) ? ' selected' : '');
    card.dataset.fund = key;
    card.innerHTML = `<div class="fn">${LABELS[key]||key}</div>
      <div class="fc-badge ${bc}">${feeText}</div><div class="ck">\u2713</div>`;
    card.addEventListener('click', () => toggle(key));
    grid.appendChild(card);
  });
}

function updateTabBadges() {
  document.querySelectorAll('.provider-tab').forEach(tab => {
    const provider = tab.textContent;
    const hasSel = selected.some(k => (DATA.funds[k]||{}).provider === provider);
    tab.classList.toggle('has-selected', hasSel && !tab.classList.contains('active'));
  });
}

function renderSelectedChips() {
  const container = document.getElementById('selectedChips');
  container.innerHTML = '';
  for (let i = 0; i < MAX_SELECT; i++) {
    const key = selected[i];
    const chip = document.createElement('div');
    if (key) {
      const fee = FEES[key];
      const feeText = fee ? 'Tasu ' + fee.toFixed(2) + '%' : '';
      chip.className = 'selected-chip';
      const info = document.createElement('div');
      info.className = 'chip-info';
      info.innerHTML = `<div class="chip-name">${LABELS[key]||key}</div><div class="chip-fee">${feeText}</div>`;
      const removeButton = document.createElement('span');
      removeButton.className = 'chip-remove';
      removeButton.textContent = '\u2715';
      removeButton.addEventListener('click', () => toggle(key));
      chip.appendChild(info);
      chip.appendChild(removeButton);
    } else {
      chip.className = 'selected-chip placeholder';
      chip.innerHTML = `<div class="chip-info"><div class="chip-name">${i === 0 ? 'Vali fond' : 'Lisa teine fond'}</div></div>`;
    }
    container.appendChild(chip);
  }
}

function toggle(key) {
  const idx = selected.indexOf(key);
  if (idx >= 0) selected.splice(idx, 1);
  else if (selected.length < MAX_SELECT) {
    selected.push(key);
    if (typeof gtag === 'function') gtag('event', 'fund_selected', { fund_name: key });
  } else return;
  document.querySelectorAll('.fund-comparison .fund-card').forEach(c =>
    c.classList.toggle('selected', selected.includes(c.dataset.fund)));
  renderSelectedChips();
  document.getElementById('hint').textContent = selected.length === 0
    ? 'Vali v\u00e4hemalt 1 fond.'
    : `${selected.length}/2 valitud.${selected.length<MAX_SELECT?' V\u00f5id lisada veel.':''}`;
  updateTabBadges();
  // Update URL
  if (selected.length > 0) {
    const url = new URL(window.location);
    url.searchParams.set('v', selected.join(','));
    history.replaceState(null, '', url);
  } else {
    const url = new URL(window.location);
    url.searchParams.delete('v');
    history.replaceState(null, '', url);
  }
  render();
}

function calcMWO(f1, f2) {
  const w1 = DATA.funds[f1].weights || {};
  const w2 = DATA.funds[f2].weights || {};
  const allKeys = new Set([...Object.keys(w1), ...Object.keys(w2)]);
  let mwo = 0;
  allKeys.forEach(k => mwo += Math.min(w1[k]||0, w2[k]||0));
  return mwo;
}

function firstLevelHoldings(f) {
  const fd = DATA.funds[f];
  const items = {};
  if (fd.etf_breakdown) {
    fd.etf_breakdown.forEach(e => { items[e.name || e.etf] = e.fund_weight || 0; });
    (fd.direct_stock_holdings || []).forEach(h => { items[h.name] = (items[h.name] || 0) + h.weight; });
    (fd.etf_holdings || []).forEach(h => { items[h.name] = (items[h.name] || 0) + h.weight; });
    (fd.pe_holdings || []).forEach(h => { items[h.name] = (items[h.name] || 0) + h.weight; });
    (fd.re_holdings || []).forEach(h => { items[h.name] = (items[h.name] || 0) + h.weight; });
    (fd.bond_holdings || []).forEach(h => { items[h.name] = (items[h.name] || 0) + h.weight; });
    (fd.gold_holdings || []).forEach(h => { items[h.name] = (items[h.name] || 0) + h.weight; });
  } else {
    (fd.top_holdings || []).forEach(h => { items[h.name] = (items[h.name] || 0) + h.weight; });
    (fd.etf_holdings || []).forEach(h => { items[h.name] = (items[h.name] || 0) + h.weight; });
    (fd.pe_holdings || []).forEach(h => { items[h.name] = (items[h.name] || 0) + h.weight; });
    (fd.re_holdings || []).forEach(h => { items[h.name] = (items[h.name] || 0) + h.weight; });
    (fd.bond_holdings || []).forEach(h => { items[h.name] = (items[h.name] || 0) + h.weight; });
    (fd.gold_holdings || []).forEach(h => { items[h.name] = (items[h.name] || 0) + h.weight; });
  }
  return items;
}

function buildComparisonTable(rows, f1, f2) {
  let html = `<div style="max-height:500px;overflow-y:auto;border:1px solid var(--g200);border-radius:8px;">
    <table style="width:100%;border-collapse:collapse;font-size:0.82rem;">
    <thead style="position:sticky;top:0;background:#fff;z-index:1;">
      <tr style="border-bottom:2px solid var(--g200);">
        <th style="padding:6px 8px;text-align:left;font-size:0.75rem;font-weight:600;color:var(--g400);text-transform:uppercase;letter-spacing:0.03em;">Investeering</th>
        <th style="padding:6px 8px;text-align:right;font-size:0.75rem;font-weight:600;color:${COLORS[f1]};text-transform:uppercase;letter-spacing:0.03em;">${SHORT[f1]||f1}</th>
        <th style="padding:6px 8px;text-align:right;font-size:0.75rem;font-weight:600;color:${COLORS[f2]};text-transform:uppercase;letter-spacing:0.03em;">${SHORT[f2]||f2}</th>
      </tr>
    </thead><tbody>`;
  rows.forEach(r => {
    const exclusive = (r.a === 0 || r.b === 0);
    const bg = exclusive ? 'background:var(--g50);' : '';
    html += `<tr style="border-bottom:1px solid var(--g100);${bg}">
      <td style="padding:4px 8px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:200px;" title="${r.name}">${r.name}</td>
      <td style="padding:4px 8px;text-align:right;font-weight:600;font-variant-numeric:tabular-nums;">${r.a > 0 ? r.a.toFixed(2) + '%' : '\u2013'}</td>
      <td style="padding:4px 8px;text-align:right;font-weight:600;font-variant-numeric:tabular-nums;">${r.b > 0 ? r.b.toFixed(2) + '%' : '\u2013'}</td>
    </tr>`;
  });
  html += '</tbody></table></div>';
  return html;
}

function mergedHoldingsTable(f1, f2, level) {
  let w1, w2;
  if (level === 'etf') {
    w1 = firstLevelHoldings(f1);
    w2 = firstLevelHoldings(f2);
  } else {
    w1 = DATA.funds[f1].weights || {};
    w2 = DATA.funds[f2].weights || {};
  }
  const allKeys = new Set([...Object.keys(w1), ...Object.keys(w2)]);
  const rows = [];
  allKeys.forEach(k => {
    const a = w1[k] || 0, b = w2[k] || 0;
    rows.push({ name: k, a, b, max: Math.max(a, b) });
  });
  rows.sort((x, y) => y.max - x.max);
  return buildComparisonTable(rows, f1, f2);
}

const TULEVA_KEY = 'Tuleva';
function render() {
  let f = [...selected];
  const has = f.length > 0;
  document.querySelectorAll('.fund-comparison .fc-section').forEach(s => s.classList.toggle('visible', has));
  if (!has) return;
  renderNarrative(f);
  renderOverlap(f);
  renderAssets(f);
  renderHoldings(f);
  renderNav(f);
  renderCTA(f);
  renderShareLink(f);
  if (f.length === 2 && typeof gtag === 'function') {
    gtag('event', 'fund_comparison', { fund_1: f[0], fund_2: f[1] });
  }
  // Details (expandable) — always start hidden
  const detailsToggle = document.getElementById('detailsToggle');
  detailsToggle.textContent = 'Vaata detailsemalt';
  renderESMA(f); renderScatter(f); renderSunburst(f); renderAllHoldings(f); renderSectors(f); renderCountries(f);
}

function renderNarrative(funds) {
  const el = document.getElementById('narrativeText');
  el.innerHTML = '';
}

function renderShareLink(funds) {
  const el = document.getElementById('sectionShare');
  if (funds.length < 2) { el.classList.remove('visible'); return; }
  el.classList.add('visible');
  const shareUrl = new URL(window.location);
  shareUrl.searchParams.set('v', funds.join(','));
  el.innerHTML = `<div style="text-align:center;">
    <button class="copy-link-btn" id="copyLinkBtn">Kopeeri v\u00f5rdluse link</button>
  </div>`;
  document.getElementById('copyLinkBtn').addEventListener('click', function() {
    const btn = this;
    navigator.clipboard.writeText(shareUrl.toString()).then(() => {
      btn.textContent = '\u2713 Kopeeritud!';
      setTimeout(() => { btn.textContent = 'Kopeeri v\u00f5rdluse link'; }, 2000);
      if (typeof gtag === 'function') gtag('event', 'share_link_copied', { funds: funds.join(',') });
    });
  });
}

function renderCTA(funds) {
  const el = document.getElementById('sectionCTA');
  el.classList.add('visible');

  const hasTuleva = funds.includes(TULEVA_KEY);
  const otherFund = funds.find(f => f !== TULEVA_KEY);
  const feeOther = otherFund ? (FEES[otherFund] || 0) : 0;
  const feeTuleva = FEES[TULEVA_KEY] || 0;
  const cheaper = feeTuleva < feeOther;

  const mwo = (hasTuleva && otherFund) ? Math.round(calcMWO(TULEVA_KEY, otherFund)) : 0;
  let parts = [];

  if (hasTuleva && otherFund && cheaper) {
    const diff = feeOther - feeTuleva;
    const eurosPer10k = Math.round(diff / 100 * 10000);
    if (eurosPer10k >= 10) {
      parts.push(`Tuleva tasu on ${diff.toFixed(2)} protsendipunkti madalam \u2014 see on ${eurosPer10k}\u20ac aastas iga 10\u2009000\u20ac kohta.`);
    }
  }

  let ctaText;
  if (parts.length > 0) {
    ctaText = parts.join(' ') + ' ' + CTA_CONFIG.text;
  } else {
    ctaText = CTA_CONFIG.text;
  }

  el.innerHTML = `<div class="cta-box">
    <p>${ctaText}</p>
    <a href="${CTA_CONFIG.buttonUrl}" class="cta-btn" target="_blank">${CTA_CONFIG.buttonText}</a>
  </div>`;
}

function renderAssets(funds) {
  const el = document.getElementById('assetBars');
  el.innerHTML = '';
  const order = ['stocks','etfs','pe','re','bonds','gold','deposits','derivatives'];
  funds.forEach(f => {
    const ac = DATA.funds[f].asset_classes || {stocks:100};
    el.innerHTML += `<div class="asset-bar-label">${LABELS[f]}</div>`;
    let bar = '<div class="asset-bar">';
    const AC_SHORT = { stocks:'Akt.', etfs:'Fondid', bonds:'V\u00f5lak.', pe:'PE', re:'Kinn.', deposits:'Hoi.', derivatives:'Tul.', gold:'Kuld' };
    const total = order.reduce((s,k) => s + (ac[k]||0), 0) || 100;
    order.forEach(k => {
      const pct = ac[k]||0;
      if (pct < 0.3) return;
      const w = pct / total * 100;
      let label = '';
      if (pct >= 15) label = AC_LABELS[k]+' '+pct.toFixed(0)+'%';
      else if (pct >= 10) label = AC_SHORT[k]+' '+pct.toFixed(0)+'%';
      bar += `<div class="seg" style="width:${w}%;background:${AC_COLORS[k]}" data-tip="${AC_LABELS[k]} ${pct.toFixed(1)}%">${label}</div>`;
    });
    bar += '</div>';
    el.innerHTML += bar;
  });
  let legend = '<div class="asset-legend">';
  order.forEach(k => { legend += `<span><span class="dot" style="background:${AC_COLORS[k]}"></span>${AC_LABELS[k]}</span>`; });
  el.innerHTML += legend + '</div>';
}

function findNearestDate(dates, target, maxDays) {
  if (maxDays == null) maxDays = 7;
  const targetMs = new Date(target).getTime();
  let bestIdx = -1, bestDiff = Infinity;
  for (let i = 0; i < dates.length; i++) {
    const diff = Math.abs(new Date(dates[i]).getTime() - targetMs);
    if (diff < bestDiff) { bestDiff = diff; bestIdx = i; }
    if (dates[i] > target && diff > bestDiff) break;
  }
  if (bestIdx < 0 || bestDiff > maxDays * 86400000) return null;
  return dates[bestIdx];
}

function renderNav(funds) {
  const section = document.getElementById('sectionNav');
  const hasNav = funds.some(f => NAV && NAV[f]);
  section.classList.toggle('visible', hasNav);
  if (!hasNav) return;

  const cutoffDate = new Date();
  cutoffDate.setFullYear(cutoffDate.getFullYear() - navPeriodYears);
  const cutoffStr = cutoffDate.toISOString().slice(0, 10);

  let commonStart = cutoffStr;
  funds.forEach(f => {
    const nd = NAV[f]; if (!nd) return;
    const firstAfter = nd.dates.find(d => d >= cutoffStr);
    if (firstAfter && firstAfter > commonStart) commonStart = firstAfter;
  });

  const lastDate = funds.reduce((latest, f) => {
    const nd = NAV[f]; if (!nd) return latest;
    const d = nd.dates[nd.dates.length - 1];
    return d > latest ? d : latest;
  }, '');
  const actualYears = lastDate ? ((new Date(lastDate) - new Date(commonStart)) / (365.25 * 86400000)) : navPeriodYears;
  const navSub = document.getElementById('navSub');
  if (Math.abs(actualYears - navPeriodYears) > 0.3) {
    navSub.textContent = `Normaliseeritud 100-le. Tegelik periood: ${actualYears.toFixed(1)} aastat (andmed alates ${commonStart})`;
  } else {
    navSub.textContent = 'Normaliseeritud 100-le valitud perioodi alguses';
  }

  const traces = funds.map(f => {
    const nd = NAV[f]; if (!nd) return null;
    let startIdx = 0;
    for (let i = 0; i < nd.dates.length; i++) {
      if (nd.dates[i] >= commonStart) { startIdx = i; break; }
    }
    const dates = nd.dates.slice(startIdx);
    const rawValues = nd.values.slice(startIdx);
    const baseVal = rawValues[0] || 1;
    const normValues = rawValues.map(v => v / baseVal * 100);
    return { x: dates, y: normValues, mode: 'lines', name: LABELS[f],
             line: { color: COLORS[f], width: 2 },
             hovertemplate: LABELS[f] + '<br>%{x}: %{y:.1f}<extra></extra>' };
  }).filter(Boolean);

  // Add MSCI ACWI dashed reference line if available
  if (NAV && NAV['MSCI ACWI']) {
    const acwi = NAV['MSCI ACWI'];
    let acwiStart = 0;
    for (let i = 0; i < acwi.dates.length; i++) {
      if (acwi.dates[i] >= commonStart) { acwiStart = i; break; }
    }
    const acwiDates = acwi.dates.slice(acwiStart);
    const acwiRaw = acwi.values.slice(acwiStart);
    const acwiBase = acwiRaw[0] || 1;
    const acwiNorm = acwiRaw.map(v => v / acwiBase * 100);
    traces.push({
      x: acwiDates, y: acwiNorm, mode: 'lines', name: 'Maailmaturu indeks',
      line: { color: '#999', width: 1.5, dash: 'dash' },
      hovertemplate: 'Maailmaturu indeks (MSCI ACWI)<br>%{x}: %{y:.1f}<extra></extra>'
    });
  }

  Plotly.newPlot('chartNav', traces, {
    ...PL, yaxis: { title: 'NAV (normaliseeritud = 100)' },
    xaxis: { title: '' }, legend: { orientation: 'h', y: 1.1 }, height: 380,
  }, { responsive: true, displayModeBar: false });

  const retTable = document.getElementById('navReturnTable');
  const periods = [2, 3, 5];

  function calcAnnReturn(f, yrs) {
    const nd = NAV[f]; if (!nd) return null;
    const cutoff = new Date();
    cutoff.setFullYear(cutoff.getFullYear() - yrs);
    const cutStr = cutoff.toISOString().slice(0, 10);
    const nearestDateStr = findNearestDate(nd.dates, cutStr);
    if (!nearestDateStr) return null;
    let si = nd.dates.indexOf(nearestDateStr);
    if (si < 0) return null;
    const startVal = nd.values[si], endVal = nd.values[nd.values.length - 1];
    const startDate = nd.dates[si], endDate = nd.dates[nd.dates.length - 1];
    const actualYrs = (new Date(endDate) - new Date(startDate)) / (365.25 * 86400000);
    if (actualYrs < yrs * 0.7 || !startVal) return null;
    return (Math.pow(endVal / startVal, 1 / actualYrs) - 1) * 100;
  }

  const fundRows = funds.filter(f => f !== 'MSCI ACWI' && f !== 'Maailmaturu indeks' && NAV[f]);

  if (fundRows.length > 0) {
    let html = `<table class="return-table"><thead><tr>
      <th>Fond</th><th>2a</th><th>3a</th><th>5a</th><th>Jooksev tasu</th>
    </tr></thead><tbody>`;
    fundRows.forEach(f => {
      const fee = FEES[f] || 0;
      html += `<tr><td class="fund-name" style="color:${COLORS[f]}">${LABELS[f]}</td>`;
      periods.forEach(p => {
        const ret = calcAnnReturn(f, p);
        if (ret !== null) {
          html += `<td style="font-weight:700">${ret >= 0 ? '+' : ''}${ret.toFixed(2)}%</td>`;
        } else {
          html += `<td style="color:var(--g400);">\u2013</td>`;
        }
      });
      html += `<td>${fee.toFixed(2)}%</td></tr>`;
    });
    if (fundRows.length === 2) {
      const fee0 = FEES[fundRows[0]] || 0, fee1 = FEES[fundRows[1]] || 0;
      const diff = Math.abs(fee0 - fee1);
      if (diff >= 0.05) {
        const expensive = fee0 > fee1 ? fundRows[0] : fundRows[1];
        const eurosPer10k = (diff / 100 * 10000).toFixed(0);
        html += `<tr><td colspan="5" class="fee-diff" style="border-top:2px solid var(--g200);padding-top:10px;">
          ${LABELS[expensive]} tasu on <span class="num-accent">${diff.toFixed(2)}</span> protsendipunkti k\u00f5rgem \u2014
          see on <span class="num-accent">${eurosPer10k}\u20ac</span> aastas iga 10\u2009000\u20ac kohta.
        </td></tr>`;
      }
    }
    html += '</tbody></table>';
    html += `<div style="font-size:0.75rem;color:var(--g400);margin-top:0.5rem;">Graafik n\u00e4itab, kuidas fondide v\u00e4\u00e4rtus on muutunud. Isegi sarnase portfelliga fondid v\u00f5ivad ajalooliselt erineda, sest varasematel aastatel v\u00f5is nende koosseis olla teistsugune. Mineviku tulemused ei garanteeri tulevikku.</div>`;
    retTable.innerHTML = html;
  } else {
    retTable.innerHTML = '';
  }
}

function renderOverlap(funds) {
  const section = document.getElementById('sectionOverlap');
  const vennEl = document.getElementById('vennDiagram');
  const feeEl = document.getElementById('vennFeeCompare');
  const disclaimerEl = document.getElementById('overlapDisclaimer');
  const corrContainer = document.getElementById('corrBarContainer');
  const holdingsCompare = document.getElementById('holdingsCompareContent');
  const holdingsCompareSection = document.getElementById('sectionHoldingsCompare');
  if (funds.length < 2) {
    section.classList.remove('visible');
    holdingsCompareSection.classList.remove('visible');
    vennEl.innerHTML = ''; feeEl.innerHTML = ''; disclaimerEl.innerHTML = '';
    corrContainer.innerHTML = ''; holdingsCompare.innerHTML = '';
    return;
  }
  section.classList.add('visible');

  const [f1, f2] = funds;
  const mwo = Math.round(calcMWO(f1, f2));
  const diffPct = 100 - mwo;
  const feeA = FEES[f1] || 0, feeB = FEES[f2] || 0;
  const feeDiff = Math.abs(feeA - feeB);
  const eurosPer10k = (feeDiff / 100 * 10000).toFixed(0);

  const expensive = feeA >= feeB ? f1 : f2;
  const cheap = feeA >= feeB ? f2 : f1;
  const expFee = Math.max(feeA, feeB);
  const cheapFee = Math.min(feeA, feeB);
  const expLabel = LABELS[expensive] || expensive;
  const cheapLabel = LABELS[cheap] || cheap;
  const feeRatioRaw = cheapFee > 0 ? expFee / cheapFee : null;
  const feeRatio = feeRatioRaw !== null ? (feeRatioRaw % 1 === 0 ? feeRatioRaw.toFixed(0) : feeRatioRaw.toFixed(1)) : null;

  // === Venn diagram SVG ===
  {
    const r = 120;
    const midX = 200;
    const cy = r + 10;
    function overlapGap(r, pct) {
      const target = pct / 100;
      if (target >= 1) return 0;
      if (target <= 0) return 2 * r;
      let lo = 0, hi = 2 * r;
      for (let i = 0; i < 30; i++) {
        const d = (lo + hi) / 2;
        const t = d / (2 * r);
        const area = (2 / Math.PI) * (Math.acos(t) - t * Math.sqrt(1 - t * t));
        if (area > target) lo = d; else hi = d;
      }
      return (lo + hi) / 2;
    }
    const gap = overlapGap(r, mwo);
    const cx1 = midX - gap / 2;
    const cx2 = midX + gap / 2;

    const feeRatioSvg = feeRatio && feeRatio !== '1'
      ? `<text x="${midX}" y="${cy + r + 45}" text-anchor="middle" font-family="-apple-system,BlinkMacSystemFont,sans-serif" font-size="18" font-weight="700" fill="#002F63">${feeRatio}\u00d7 erinev tasu</text>`
      : '';

    const pctY = mwo < 30 ? cy + r + (feeRatioSvg ? 90 : 30) : cy + 8;
    const subY = pctY + 22;
    const svgH = Math.max(cy + r + 15, subY + 15, feeRatioSvg ? cy + r + 70 : 0, mwo < 30 && feeRatioSvg ? subY + 40 : 0);

    vennEl.innerHTML = `
      <div style="display:flex;justify-content:center;gap:2rem;margin-bottom:0.5rem;font-size:0.9rem;font-weight:600;">
        <span style="display:flex;align-items:center;gap:6px;color:#6b7280;"><span style="width:12px;height:12px;border-radius:50%;background:#d1d5db;flex-shrink:0;"></span>${expLabel}</span>
        <span style="display:flex;align-items:center;gap:6px;color:#0081EE;"><span style="width:12px;height:12px;border-radius:50%;background:#0081EE;flex-shrink:0;"></span>${cheapLabel}</span>
      </div>
      <svg viewBox="0 0 400 ${svgH}" xmlns="http://www.w3.org/2000/svg">
        <circle cx="${cx1}" cy="${cy}" r="${r}" fill="#e5e7eb" stroke="#d1d5db" stroke-width="2" opacity="0.85"/>
        <circle cx="${cx2}" cy="${cy}" r="${r}" fill="#0081EE" stroke="#0081EE" stroke-width="2" opacity="0.3"/>
        <text x="${midX}" y="${pctY}" text-anchor="middle" font-family="Georgia,'Times New Roman',serif" font-size="48" font-weight="700" fill="#002F63">${mwo}%</text>
        <text x="${midX}" y="${subY}" text-anchor="middle" font-family="-apple-system,BlinkMacSystemFont,sans-serif" font-size="14" fill="#002F63">sama portfell</text>
        ${feeRatioSvg}
      </svg>`;

    if (feeRatio && feeRatio !== '1.0') {
      feeEl.innerHTML = `<div class="venn-fee-row">
        <div class="venn-fee-item"><span class="fee-val expensive">${expFee.toFixed(2)}%</span> ${expLabel}</div>
        <div class="venn-fee-item"><span class="fee-val cheap">${cheapFee.toFixed(2)}%</span> ${cheapLabel}</div>
      </div>`;
    } else {
      feeEl.innerHTML = '';
    }
  }

  disclaimerEl.innerHTML = 'Enamik fonde ei osta aktsiaid otse, vaid l\u00e4bi b\u00f6rsil kaubeldavate fondide (ETF). Vaatame ETF-ide sisse ja v\u00f5rdleme tegelikke aktsiaid. Kui m\u00f5ne ETFi andmed pole k\u00e4ttesaadavad, kasutame sarnase fondi (proxy) andmeid. Seet\u00f5ttu ei pruugi andmed olla t\u00e4psed.';

  const k1 = `${f1}|${f2}`, k2 = `${f2}|${f1}`;
  const navCorr = RET_CORR?.correlations ? (RET_CORR.correlations[k1] ?? RET_CORR.correlations[k2] ?? null) : null;
  const r2 = RET_CORR?.r_squared ? (RET_CORR.r_squared[k1] ?? RET_CORR.r_squared[k2] ?? null) : null;
  const te = RET_CORR?.tracking_error_pct ? (RET_CORR.tracking_error_pct[k1] ?? RET_CORR.tracking_error_pct[k2] ?? null) : null;
  const navCorrPct = navCorr !== null ? Math.round(navCorr * 100) : null;
  const r2Pct = r2 !== null ? Math.round(r2 * 100) : null;

  if (r2Pct !== null) {
    corrContainer.innerHTML = `
      <div style="margin-top:1.5rem;">
        <div style="font-size:0.8rem;font-weight:600;color:var(--g600);margin-bottom:2px;">Kui sarnaselt fondid liiguvad</div>
        <div style="position:relative;height:28px;border-radius:8px;overflow:hidden;background:var(--g200);margin-bottom:0.4rem;">
          <div style="height:100%;width:${Math.max(r2Pct,2)}%;background:var(--blue);border-radius:8px 0 0 8px;display:flex;align-items:center;${r2Pct >= 20 ? 'justify-content:center;' : 'justify-content:flex-end;padding-right:6px;'}color:#fff;font-size:0.9rem;font-weight:800;">${r2Pct}%</div>
        </div>
        <div style="font-size:0.78rem;color:var(--g400);">
          ${feeA !== feeB ? `Odavam fond (${LABELS[feeA <= feeB ? f1 : f2]}) selgitab ${r2Pct}% kallima fondi (${LABELS[feeA > feeB ? f1 : f2]}) hinnamuutustest.` : `Fondide hinnamuutused on ${r2Pct}% ulatuses koos seletatavad.`}
        </div>
      </div>`;
  } else {
    corrContainer.innerHTML = '';
  }

  const holdingsTableHtml = mergedHoldingsTable(f1, f2, 'stock');
  holdingsCompareSection.classList.add('visible');
  holdingsCompare.innerHTML = `
    <div style="margin-top:0.5rem;">
      <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.5rem;flex-wrap:wrap;">
        <div style="font-weight:700;font-size:0.8rem;color:var(--g600);text-transform:uppercase;letter-spacing:0.03em;">Investeeringud v\u00f5rrelduna</div>
        <div class="nav-period-btns" id="holdingsLevelBtns" style="margin-bottom:0;">
          <button class="nav-period-btn active" data-level="stock">L\u00e4bivaade</button>
          <button class="nav-period-btn" data-level="etf">Otse omatud</button>
        </div>
      </div>
      <div id="holdingsTableContainer">${holdingsTableHtml}</div>
    </div>`;

  document.getElementById('holdingsLevelBtns').addEventListener('click', (e) => {
    const btn = e.target.closest('.nav-period-btn');
    if (!btn) return;
    document.querySelectorAll('#holdingsLevelBtns .nav-period-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('holdingsTableContainer').innerHTML = mergedHoldingsTable(f1, f2, btn.dataset.level);
  });
}

function renderHoldingsTable(items) {
  let html = `<div style="max-height:500px;overflow-y:auto;border:1px solid var(--g200);border-radius:8px;">
    <table style="width:100%;border-collapse:collapse;font-size:0.82rem;">
    <thead style="position:sticky;top:0;background:#fff;z-index:1;">
      <tr style="border-bottom:2px solid var(--g200);">
        <th style="padding:6px 8px;text-align:left;font-size:0.75rem;font-weight:600;color:var(--g400);text-transform:uppercase;letter-spacing:0.03em;">#</th>
        <th style="padding:6px 8px;text-align:left;font-size:0.75rem;font-weight:600;color:var(--g400);text-transform:uppercase;letter-spacing:0.03em;">Investeering</th>
        <th style="padding:6px 8px;text-align:right;font-size:0.75rem;font-weight:600;color:var(--g400);text-transform:uppercase;letter-spacing:0.03em;">Osakaal</th>
      </tr>
    </thead><tbody>`;
  items.forEach((item, i) => {
    html += `<tr style="border-bottom:1px solid var(--g100);">
      <td style="padding:4px 8px;color:var(--g400);">${i + 1}</td>
      <td style="padding:4px 8px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:250px;" title="${item.name}">${item.name}</td>
      <td style="padding:4px 8px;text-align:right;font-weight:600;font-variant-numeric:tabular-nums;">${item.weight.toFixed(2)}%</td>
    </tr>`;
  });
  html += '</tbody></table></div>';
  return html;
}

function singleFundHoldingsItems(f, level) {
  const fd = DATA.funds[f];
  if (level === 'etf') {
    const map = firstLevelHoldings(f);
    return Object.entries(map).map(([name, weight]) => ({ name, weight, sector: '', country: '' }))
      .sort((a, b) => b.weight - a.weight);
  }
  const items = [];
  (fd.top_holdings || []).forEach(h => items.push({ name: h.name, weight: h.weight, sector: h.sector || '', country: h.country || '' }));
  (fd.pe_holdings || []).forEach(h => items.push({ name: h.name, weight: h.weight, sector: '', country: '' }));
  (fd.re_holdings || []).forEach(h => items.push({ name: h.name, weight: h.weight, sector: '', country: '' }));
  (fd.bond_holdings || []).forEach(h => items.push({ name: h.name, weight: h.weight, sector: '', country: '' }));
  (fd.gold_holdings || []).forEach(h => items.push({ name: h.name, weight: h.weight, sector: 'Kuld', country: '' }));
  items.sort((a, b) => b.weight - a.weight);
  return items;
}

function renderHoldings(funds) {
  const el = document.getElementById('holdingsArea');
  const section = document.getElementById('sectionHoldings');
  if (funds.length >= 2) { section.classList.remove('visible'); return; }
  section.classList.add('visible');
  el.innerHTML = '';

  const f = funds[0];
  const fd = DATA.funds[f];
  const hasToggle = !!fd.etf_breakdown;

  let toggleHtml = '';
  if (hasToggle) {
    toggleHtml = `<div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.5rem;flex-wrap:wrap;">
      <div class="nav-period-btns" id="holdingsSingleLevelBtns" style="margin-bottom:0;">
        <button class="nav-period-btn active" data-level="stock">L\u00e4bivaade</button>
        <button class="nav-period-btn" data-level="etf">Otse omatud</button>
      </div>
    </div>`;
  }

  const items = singleFundHoldingsItems(f, 'stock');
  el.innerHTML = toggleHtml + `<div id="holdingsSingleTableContainer">${renderHoldingsTable(items)}</div>`;

  if (hasToggle) {
    document.getElementById('holdingsSingleLevelBtns').addEventListener('click', (e) => {
      const btn = e.target.closest('.nav-period-btn');
      if (!btn) return;
      document.querySelectorAll('#holdingsSingleLevelBtns .nav-period-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById('holdingsSingleTableContainer').innerHTML = renderHoldingsTable(singleFundHoldingsItems(f, btn.dataset.level));
    });
  }
}

function renderESMA(funds) {
  const el = document.getElementById('detailESMA');
  const content = document.getElementById('esmaContent');
  if (funds.length < 2) { el.style.display = 'none'; return; }

  const [f1, f2] = funds;
  const prov1 = DATA.funds[f1].provider, prov2 = DATA.funds[f2].provider;
  if (prov1 !== prov2) { el.style.display = 'none'; return; }
  const k1 = `${f1}|${f2}`, k2 = `${f2}|${f1}`;

  const esmaSource = RET_CORR?.last_1y || RET_CORR;
  const te = esmaSource?.tracking_error_pct ? (esmaSource.tracking_error_pct[k1] ?? esmaSource.tracking_error_pct[k2] ?? null) : null;

  const mwo = Math.round(calcMWO(f1, f2));
  const activeShare = 100 - mwo;
  const teFmt = te !== null ? te.toFixed(1) : '\u2013';

  if (te === null && activeShare === 0) { el.style.display = 'none'; return; }
  el.style.display = 'block';

  const flagAS = activeShare < 60;
  const flagTE = te !== null && te < 4;
  const isSame = flagAS && flagTE;

  function metricRow(label, value, desc, flag) {
    const flagIcon = flag ? ' \u26a0\ufe0f' : '';
    const flagStyle = flag ? 'color:var(--red);' : '';
    return `<div style="display:flex;align-items:baseline;gap:1rem;padding:0.6rem 0;border-bottom:1px solid var(--g100);">
      <div style="min-width:180px;">
        <div style="font-weight:700;font-size:0.9rem;${flagStyle}">${label}${flagIcon}</div>
        <div style="font-size:0.75rem;color:var(--g400);">${desc}</div>
      </div>
      <div style="font-size:1.1rem;font-weight:800;font-variant-numeric:tabular-nums;${flagStyle}">${value}</div>
    </div>`;
  }

  let html = metricRow('Aktiivne osakaal', `${activeShare}%`,
    `Kui suur osa portfellist erineb. ESMA l\u00e4vend: < 60%`, flagAS);
  html += metricRow('J\u00e4lgimisviga (Tracking Error)', `${teFmt}% aastas`,
    `Fondide tootluste erinevuse volatiilsus. ESMA l\u00e4vend: < 4%`, flagTE);

  let interpBg, interpBorder, interpText;
  if (isSame) {
    interpBg = '#fef2f2'; interpBorder = 'var(--red)';
    interpText = `<strong>V\u00f5imalik peidetud indeksij\u00e4lgimine.</strong> Aktiivne osakaal < 60% ja j\u00e4lgimisviga < 4%. ESMA metoodika j\u00e4rgi viitab see closet indexing\u2019ile \u2014 fond n\u00e4eb v\u00e4lja erinev, aga liigub sarnaselt.`;
  } else {
    interpBg = '#f0fdf4'; interpBorder = 'var(--green)';
    interpText = `<strong>Fondid on erinevad.</strong> ESMA closet indexing kriteeriumid pole t\u00e4idetud. Fondide koosseis v\u00f5i tootlused erinevad piisavalt.`;
  }

  html += `<div style="margin-top:1rem;padding:0.8rem 1rem;background:${interpBg};border-radius:8px;border-left:3px solid ${interpBorder};font-size:0.85rem;line-height:1.6;">
    ${interpText}
  </div>`;

  const esmaPeriod = esmaSource?.period || RET_CORR?.period || '\u2013';
  html += `<div style="margin-top:1rem;font-size:0.75rem;color:var(--g400);line-height:1.6;">
    Periood: ${esmaPeriod}. N\u00e4dalased logaritmilised tootlused.
    ESMA l\u00e4vendid p\u00e4rinevad dokumendist <a href="https://www.esma.europa.eu/sites/default/files/library/2016-165_public_statement_-_supervisory_work_on_potential_closet_index_tracking.pdf" style="color:var(--g400);">ESMA/2016/165</a> (para. 15).
  </div>`;

  content.innerHTML = html;
}

function renderScatter(funds) {
  const detailScatter = document.getElementById('detailScatter');
  if (funds.length < 2) {
    detailScatter.style.display = 'none';
    return;
  }
  detailScatter.style.display = 'block';
  const [f1, f2] = funds;
  const w1 = DATA.funds[f1].weights || {};
  const w2 = DATA.funds[f2].weights || {};
  const allKeys = new Set([...Object.keys(w1), ...Object.keys(w2)]);
  const sharedX = [], sharedY = [], sharedN = [];
  const onlyF1X = [], onlyF1Y = [], onlyF1N = [];
  const onlyF2X = [], onlyF2Y = [], onlyF2N = [];
  const FLOOR = 0.005;
  allKeys.forEach(k => {
    const a = w1[k]||0, b = w2[k]||0;
    const label = k.replace(/^(PE|RE|BOND|ETF)\|/, '');
    if (a > 0.02 && b > 0.02) { sharedX.push(a); sharedY.push(b); sharedN.push(label); }
    else if (a > 0.02 && b <= 0.02) { onlyF1X.push(a); onlyF1Y.push(FLOOR); onlyF1N.push(label); }
    else if (b > 0.02 && a <= 0.02) { onlyF2X.push(FLOOR); onlyF2Y.push(b); onlyF2N.push(label); }
  });
  const nOnly1 = onlyF1X.length, nOnly2 = onlyF2X.length;
  document.getElementById('scatterSub').textContent =
    `${LABELS[f1]} vs ${LABELS[f2]}. Iga t\u00e4pp = \u00fcks investeering. Diagonaalil = identne kaal.`;
  const allXs = [...sharedX, ...onlyF1X, ...onlyF2X].filter(v=>v>0);
  const allYs = [...sharedY, ...onlyF1Y, ...onlyF2Y].filter(v=>v>0);
  const mn = Math.min(...allXs, ...allYs) * 0.8;
  const mx = Math.max(...allXs, ...allYs) * 1.3;
  const r = DATA.correlation_matrix[`${f1}|${f2}`] || 0;
  const traces = [
    { x: sharedX, y: sharedY, mode:'markers', name: 'M\u00f5lemas fondis',
      marker:{size:8,color:'#0081EE',opacity:0.45}, text:sharedN,
      hovertemplate:'%{text}<br>'+LABELS[f1]+': %{x:.2f}%<br>'+LABELS[f2]+': %{y:.2f}%<extra></extra>' },
    { x:[mn,mx], y:[mn,mx], mode:'lines', line:{dash:'dash',color:'#ccc'}, showlegend:false },
  ];
  if (nOnly1 > 0) {
    traces.push({ x: onlyF1X, y: onlyF1Y, mode:'markers',
      name: `Ainult ${(LABELS[f1]||f1).split(' ').slice(0,2).join(' ')} (${nOnly1})`,
      marker:{size:7,color:'#dc2626',opacity:0.5,symbol:'diamond'}, text:onlyF1N,
      hovertemplate:'%{text}<br>'+LABELS[f1]+': %{x:.2f}%<br>'+LABELS[f2]+': 0%<extra>Ainult '+LABELS[f1]+'</extra>' });
  }
  if (nOnly2 > 0) {
    traces.push({ x: onlyF2X, y: onlyF2Y, mode:'markers',
      name: `Ainult ${(LABELS[f2]||f2).split(' ').slice(0,2).join(' ')} (${nOnly2})`,
      marker:{size:7,color:'#16a34a',opacity:0.5,symbol:'diamond'}, text:onlyF2N,
      hovertemplate:'%{text}<br>'+LABELS[f1]+': 0%<br>'+LABELS[f2]+': %{y:.2f}%<extra>Ainult '+LABELS[f2]+'</extra>' });
  }
  Plotly.newPlot('chartScatter', traces, { ...PL,
    xaxis:{title:{text:LABELS[f1]+' (%)', font:{size:14}}, type:'log', range:[Math.log10(mn), Math.log10(mx)], tickfont:{size:12}},
    yaxis:{title:{text:LABELS[f2]+' (%)', font:{size:14}}, type:'log', range:[Math.log10(mn), Math.log10(mx)], tickfont:{size:12}},
    height:450, legend:{orientation:'h',y:-0.15,x:0.5,xanchor:'center',font:{size:11}},
    annotations:[{ x:0.95, y:0.05, xref:'paper', yref:'paper',
      text:`r = ${r.toFixed(3)}`, showarrow:false,
      font:{size:16,color:'#333'}, bgcolor:'rgba(255,255,255,0.8)' }],
  }, {responsive:true, displayModeBar:false});
}

function renderSunburst(funds) {
  const area = document.getElementById('sunburstArea');
  area.innerHTML = '';
  const sbWidth = funds.length === 2 ? 'calc(50% - 0.5rem)' : '100%';

  funds.forEach(f => {
    const fd = DATA.funds[f];
    const nodes = [];
    const rootId = f + '_root';
    const ac = fd.asset_classes || { stocks: 100 };
    const pal = ['#0081EE','#16a34a','#dc2626','#F7A600','#7c3aed','#D63B84','#0d9488','#ea580c','#6b7280'];

    if (fd.etf_breakdown) {
      const acBaseColors = { stocks:'#0081EE', etfs:'#60a5fa', bonds:'#6b7280', deposits:'#d1d5db' };

      const stocksPct = (ac.stocks || 0) + (ac.etfs || 0);
      const bondsPct = ac.bonds || 0;
      const depositsPct = ac.deposits || 0;
      const etfsPct = ac.etfs || 0;

      const stocksId = f + '_ac_stocks';
      if (stocksPct > 0.5) nodes.push({id: stocksId, label: `Aktsiad ${(ac.stocks||0).toFixed(0)}%`, parentId: rootId, value: 0, color: acBaseColors.stocks});
      if (etfsPct > 0.5) {
        const etfsId = f + '_ac_etfs';
        nodes.push({id: etfsId, label: `ETF-id ${etfsPct.toFixed(0)}%`, parentId: rootId, value: etfsPct, color: acBaseColors.etfs});
      }
      if (bondsPct > 0.5) nodes.push({id: f+'_ac_bonds', label: `V\u00f5lakirjad ${bondsPct.toFixed(0)}%`, parentId: rootId, value: bondsPct, color: acBaseColors.bonds});
      const rePct = ac.re || 0;
      const pePct = ac.pe || 0;
      if (rePct > 0.5) nodes.push({id: f+'_ac_re', label: `Kinnisvara ${rePct.toFixed(0)}%`, parentId: rootId, value: rePct, color: '#059669'});
      if (pePct > 0.5) nodes.push({id: f+'_ac_pe', label: `Erakapital ${pePct.toFixed(0)}%`, parentId: rootId, value: pePct, color: '#7c3aed'});
      if (depositsPct > 0.5) nodes.push({id: f+'_ac_deposits', label: `Hoiused ${depositsPct.toFixed(0)}%`, parentId: rootId, value: depositsPct, color: '#d1d5db'});

      if (rePct > 0.5 && fd.re_holdings) {
        fd.re_holdings.forEach((h, i) => {
          nodes.push({id: f+'_re'+i, label: h.name.length>25?h.name.substring(0,25)+'\u2026':h.name, parentId: f+'_ac_re', value: h.weight, color: '#34d399'});
        });
      }
      if (pePct > 0.5 && fd.pe_holdings) {
        fd.pe_holdings.forEach((h, i) => {
          nodes.push({id: f+'_pe'+i, label: h.name.length>25?h.name.substring(0,25)+'\u2026':h.name, parentId: f+'_ac_pe', value: h.weight, color: '#a78bfa'});
        });
      }

      const topStocks = (fd.top_holdings || []).slice(0, 40);
      let assignedStockWeight = 0;
      topStocks.forEach((h, si) => {
        const sn = h.name.split(/\s/).slice(0, 2).join(' ');
        const hue = 215 + (si % 8) * 3;
        const light = 50 + (si % 6) * 4;
        const col = `hsl(${hue}, 70%, ${light}%)`;
        nodes.push({id: f+'_s'+si, label: sn, parentId: stocksId, value: h.weight, color: col});
        assignedStockWeight += h.weight;
      });
      const restStocks = (ac.stocks || 0) - assignedStockWeight;
      if (restStocks > 0.5) {
        nodes.push({id: f+'_srest', label: 'Muud aktsiad', parentId: stocksId, value: restStocks, color: '#93c5fd'});
      }

    } else if (fd.pe_holdings && fd.pe_holdings.length > 0) {
      const stockId = f + '_stocks';
      fd.top_holdings.slice(0, 8).forEach((h, i) => {
        nodes.push({id: f+'_s'+i, label: h.name.split(/\s/).slice(0,2).join(' '), parentId: stockId, value: h.weight, color: '#0081EE'});
      });
      const sr = fd.top_holdings.slice(8).reduce((s,h) => s+h.weight, 0);
      if (sr > 0) nodes.push({id: f+'_srest', label: 'Muud aktsiad', parentId: stockId, value: sr, color: '#93c5fd'});

      if (fd.etf_holdings && fd.etf_holdings.length > 0) {
        const etfId = f + '_etfs';
        fd.etf_holdings.forEach((h, i) => {
          nodes.push({id: f+'_e'+i, label: h.name.length>20?h.name.substring(0,20)+'\u2026':h.name, parentId: etfId, value: h.weight, color: '#93c5fd'});
        });
        nodes.push({id: etfId, label: 'ETF-id', parentId: rootId, value: 0, color: '#60a5fa'});
      }

      const peId = f + '_pe';
      fd.pe_holdings.slice(0, 8).forEach((h, i) => {
        nodes.push({id: f+'_p'+i, label: h.name.length>20?h.name.substring(0,20)+'\u2026':h.name, parentId: peId, value: h.weight, color: '#a78bfa'});
      });
      const pr = fd.pe_holdings.slice(8).reduce((s,h) => s+h.weight, 0);
      if (pr > 0) nodes.push({id: f+'_prest', label: 'Muu PE', parentId: peId, value: pr, color: '#c4b5fd'});

      if (fd.re_holdings && fd.re_holdings.length > 0) {
        const reId = f + '_re';
        fd.re_holdings.forEach((h, i) => {
          nodes.push({id: f+'_r'+i, label: h.name.length>20?h.name.substring(0,20)+'\u2026':h.name, parentId: reId, value: h.weight, color: '#34d399'});
        });
        nodes.push({id: reId, label: 'Kinnisvara', parentId: rootId, value: 0, color: '#059669'});
      }

      if (fd.bond_holdings && fd.bond_holdings.length > 0) {
        const bondId = f + '_bonds';
        fd.bond_holdings.slice(0, 6).forEach((h, i) => {
          nodes.push({id: f+'_b'+i, label: h.name.length>20?h.name.substring(0,20)+'\u2026':h.name, parentId: bondId, value: h.weight, color: '#9ca3af'});
        });
        const br = fd.bond_holdings.slice(6).reduce((s,h) => s+h.weight, 0);
        if (br > 0) nodes.push({id: f+'_brest', label: 'Muud v\u00f5lakirjad', parentId: bondId, value: br, color: '#d1d5db'});
        nodes.push({id: bondId, label: 'V\u00f5lakirjad', parentId: rootId, value: 0, color: '#6b7280'});
      }

      if ((ac.deposits||0) > 0.5) nodes.push({id: f+'_dep', label: 'Hoiused', parentId: rootId, value: ac.deposits, color: '#d1d5db'});

      nodes.push({id: stockId, label: 'Aktsiad', parentId: rootId, value: 0, color: '#0081EE'});
      nodes.push({id: peId, label: 'Erakapital', parentId: rootId, value: 0, color: '#7c3aed'});

    } else {
      const holdings = fd.top_holdings.slice(0, 15);
      const topWeight = holdings.reduce((s,h) => s+h.weight, 0);
      const totalW = ac.stocks || fd.total_weight || 100;
      const restWeight = Math.max(0, totalW - topWeight);

      const bySector = {};
      holdings.forEach(h => { const s = h.sector||'Muu'; if (!bySector[s]) bySector[s]=[]; bySector[s].push(h); });
      if (restWeight > 1) {
        if (!bySector['Muu']) bySector['Muu'] = [];
        bySector['Muu'].push({ name: `Muud (${fd.n_stocks - holdings.length})`, weight: restWeight, _isRest: true });
      }

      const cl = Object.entries(bySector).map(([s,hs]) => ({s,hs,total:hs.reduce((sum,h)=>sum+h.weight,0)})).sort((a,b)=>b.total-a.total);
      cl.forEach(({s,hs},ci) => {
        const col = pal[ci%pal.length], sId = f+'_sec'+ci;
        hs.forEach((h,hi) => {
          const sn = h._isRest ? h.name : h.name.split(/\s/).slice(0,2).join(' ');
          nodes.push({id: sId+'_h'+hi, label: sn, parentId: sId, value: h.weight, color: h._isRest ? '#d1d5db' : col+'88'});
        });
        nodes.push({id: sId, label: s, parentId: rootId, value: 0, color: col});
      });
    }

    const nodeMap = {};
    nodes.forEach(n => nodeMap[n.id] = n);
    nodes.forEach(n => {
      if (n.value > 0 && n.parentId && nodeMap[n.parentId]) {
        nodeMap[n.parentId].value += n.value;
      }
    });
    let rootSum = 0;
    nodes.forEach(n => { if (n.parentId === rootId) rootSum += n.value; });

    const ids = [rootId], labels = [SHORT[f]||f], parents = [''], vals = [rootSum], cols = ['#e5e7eb'], cdata = [rootSum];
    nodes.forEach(n => {
      ids.push(n.id); labels.push(n.label); parents.push(n.parentId);
      vals.push(n.value); cols.push(n.color);
      cdata.push(n._hoverWeight != null ? n._hoverWeight : n.value);
    });

    const div = document.createElement('div');
    div.style.cssText = `width:${sbWidth};min-width:0;flex-shrink:0;`;
    const divId = 'sunburst_' + f.replace(/\s/g, '_');
    div.id = divId;
    area.appendChild(div);

    Plotly.newPlot(divId, [{
      type: 'sunburst', ids, labels, parents, values: vals, customdata: cdata,
      branchvalues: 'total',
      hovertemplate: '<b>%{label}</b><br>%{customdata:.2f}%<extra></extra>',
      textinfo: 'label',
      insidetextorientation: 'radial',
      marker: { colors: cols, line: { width: 1, color: '#fff' } },
    }], {
      ...PL, height: 420, margin: { t: 40, r: 5, b: 5, l: 5 },
      title: { text: LABELS[f] || f, font: { size: 14, color: COLORS[f] } },
    }, { responsive: true, displayModeBar: false });

    if (fd.etf_breakdown) {
      const etfCount = Object.keys(fd.etf_breakdown).length;
      const note = document.createElement('div');
      note.style.cssText = 'font-size:0.78rem;color:var(--g400);text-align:center;margin-top:-0.5rem;margin-bottom:0.5rem;';
      note.textContent = `Investeerib l\u00e4bi ${etfCount} ETF-i. Siin n\u00e4idatud l\u00f5plikud aktsiad.`;
      div.parentNode.insertBefore(note, div.nextSibling);
    }
  });
}

function renderAllHoldings(funds) {
  const section = document.getElementById('detailAllHoldings');
  const container = document.getElementById('allHoldingsList');
  const csvBtn = document.getElementById('csvDownloadBtn');

  if (funds.length !== 1) { section.style.display = 'none'; return; }
  section.style.display = 'block';
  container.innerHTML = '';

  const f = funds[0];
  const fd = DATA.funds[f];
  const items = [];
  (fd.top_holdings || []).forEach(h => items.push({...h, cat: 'stock'}));
  (fd.pe_holdings || []).forEach(h => items.push({...h, cat: 'pe'}));
  (fd.re_holdings || []).forEach(h => items.push({...h, cat: 're'}));
  (fd.bond_holdings || []).forEach(h => items.push({...h, cat: 'bond'}));
  (fd.gold_holdings || []).forEach(h => items.push({...h, cat: 'gold'}));
  items.sort((a, b) => (b.weight || 0) - (a.weight || 0));

  const dataMonth = DATA.data_month || '';
  const MONTHS_ET = {'01':'jaanuar','02':'veebruar','03':'m\u00e4rts','04':'aprill','05':'mai','06':'juuni',
    '07':'juuli','08':'august','09':'september','10':'oktoober','11':'november','12':'detsember'};
  let dateStr = '';
  if (dataMonth) {
    const [yr, mo] = dataMonth.split('-');
    dateStr = `${MONTHS_ET[mo] || mo} ${yr}`;
  }

  const sub = document.getElementById('allHoldingsSub');
  sub.textContent = `Enamik fonde ei osta aktsiaid otse, vaid l\u00e4bi b\u00f6rsil kaubeldavate fondide (ETF). Vaatame ETF-ide sisse ja n\u00e4itame tegelikke aktsiaid. Kui m\u00f5ne ETFi andmed pole k\u00e4ttesaadavad, kasutame sarnase fondi (proxy) andmeid. Seet\u00f5ttu ei pruugi andmed olla t\u00e4psed.${dateStr ? ` Andmed seisuga ${dateStr}.` : ''}`;

  const allRows = items.map((item, i) => ({rank: i+1, fund: LABELS[f], name: item.name, weight: item.weight, sector: item.sector || '', country: item.country || '', cat: item.cat}));

  let html = `<table style="width:100%;font-size:0.82rem;border-collapse:collapse;">
    <thead><tr style="text-align:left;border-bottom:2px solid var(--g200);">
      <th style="padding:4px;">#</th>
      <th style="padding:4px;">Nimi</th><th style="padding:4px;">Osakaal</th>
      <th style="padding:4px;">Sektor</th><th style="padding:4px;">Riik</th>
    </tr></thead><tbody>`;
  allRows.forEach(r => {
    html += `<tr style="border-bottom:1px solid var(--g100);">
      <td style="padding:3px 4px;color:var(--g400);">${r.rank}</td>
      <td style="padding:3px 4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:200px;" title="${r.name}">${r.name}</td>
      <td style="padding:3px 4px;font-weight:600;font-variant-numeric:tabular-nums;">${r.weight != null ? r.weight.toFixed(2) + '%' : '\u2013'}</td>
      <td style="padding:3px 4px;">${trSector(r.sector)}</td>
      <td style="padding:3px 4px;">${trCountry(r.country)}</td>
    </tr>`;
  });
  html += '</tbody></table>';
  container.innerHTML = html;

  csvBtn.onclick = () => {
    const disclaimer = `# Enamik fonde ei osta aktsiaid otse, vaid labi borsil kaubeldavate fondide (ETF). Vaatame ETF-ide sisse ja naitame tegelikke aktsiaid. Kui mone ETFi andmed pole kattesaadavad, kasutame sarnase fondi (proxy) andmeid. Seetottu ei pruugi andmed olla tapsed.${dateStr ? ` Andmed seisuga ${dateStr}.` : ''}\n`;
    const header = 'Rank,Fond,Nimi,Osakaal,Sektor,Riik\n';
    const csv = disclaimer + header + allRows.map(r =>
      `${r.rank},"${r.fund}","${r.name}",${r.weight != null ? r.weight.toFixed(4) : ''},"${trSector(r.sector)}","${trCountry(r.country)}"`
    ).join('\n');
    const blob = new Blob([csv], {type: 'text/csv;charset=utf-8;'});
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `fondide_investeeringud_${LABELS[f].replace(/\s/g,'_')}.csv`;
    a.click();
    URL.revokeObjectURL(url);
  };
}

function renderRankedList(container, funds, dataKey, translateFn) {
  container.innerHTML = '';
  const gridCols = funds.length === 2 ? '1fr 1fr' : '1fr';
  const grid = document.createElement('div');
  grid.style.cssText = `display:grid;grid-template-columns:${gridCols};gap:2rem;`;

  funds.forEach(f => {
    const col = document.createElement('div');
    if (funds.length === 2) {
      const header = document.createElement('div');
      header.style.cssText = `font-weight:700;font-size:0.85rem;color:${COLORS[f]};margin-bottom:0.5rem;`;
      header.textContent = LABELS[f];
      col.appendChild(header);
    }
    const items = Object.entries(DATA.funds[f][dataKey] || {})
      .sort((a,b) => b[1] - a[1]).slice(0, 12);
    items.forEach(([name, weight], i) => {
      const row = document.createElement('div');
      row.style.cssText = 'display:flex;align-items:baseline;padding:3px 0;font-size:0.84rem;border-bottom:1px solid var(--g100);gap:0.5rem;';
      row.innerHTML = `<span style="color:var(--g400);min-width:1.5rem;font-variant-numeric:tabular-nums;">${i+1}.</span>
        <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${translateFn(name)}</span>
        <span style="font-weight:600;font-variant-numeric:tabular-nums;flex-shrink:0;">${weight.toFixed(1)}%</span>`;
      col.appendChild(row);
    });
    grid.appendChild(col);
  });
  container.appendChild(grid);
}

function renderSectors(funds) {
  renderRankedList(document.getElementById('chartSectors'), funds, 'sectors', trSector);
}

function renderCountries(funds) {
  renderRankedList(document.getElementById('chartCountries'), funds, 'countries', trCountry);
}

})();
</script>
</main>
