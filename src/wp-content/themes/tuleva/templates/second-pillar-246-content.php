<main id="main" class="page-container second-pillar-246-page">

  <section class="hero">
    <div class="hero-in">
      <div class="hero-text">
        <h1><?php echo esc_html( get_field( 'hero_title' ) ?: 'Tõsta II samba sissemakset' ); ?></h1>
        <p class="dek">Iga 100&nbsp;€ kohta, mille palgast kõrvale paned, jõuab II&nbsp;sambasse 128&nbsp;€.</p>
        <p class="dek-note">Vahe on sinu tulumaksuvõit. <strong>Tähtaeg 30.&nbsp;november.</strong></p>
      </div>
      <div class="hero-card">
        <section class="card calculator rounded-4" id="calc" aria-label="II samba maksuvõidu kalkulaator">
          <div class="card-body p-2">
            <div class="card bg-gray-2 p-3 rounded-3">
              <div class="mb-3">
                <label for="gross" class="form-label">Sinu brutopalk</label>
                <input type="text" class="form-control form-control-lg text-end" id="gross" value="3000" inputmode="numeric" autocomplete="off">
              </div>
              <div>
                <span class="form-label d-block" id="rateLbl">Sinu sissemakse brutopalgast</span>
                <div class="btn-group d-flex" id="segRate" role="group" aria-labelledby="rateLbl">
                  <input type="radio" class="btn-check" name="rate" id="rate2" value="2" autocomplete="off">
                  <label for="rate2" class="btn btn-outline-primary w-100">2%</label>
                  <input type="radio" class="btn-check" name="rate" id="rate4" value="4" autocomplete="off">
                  <label for="rate4" class="btn btn-outline-primary w-100">4%</label>
                  <input type="radio" class="btn-check" name="rate" id="rate6" value="6" autocomplete="off" checked>
                  <label for="rate6" class="btn btn-outline-primary w-100">6%</label>
                </div>
              </div>
            </div>
            <div class="px-3 pt-4 pb-3 calculator-sum" role="status" aria-live="polite">
              <div class="calculator-sum__row">
                <span class="calculator-sum__operator" aria-hidden="true"></span>
                <span class="calculator-sum__label">Netopalgast</span>
                <span class="calculator-sum__amount" id="cNet">140 €</span>
              </div>
              <div class="calculator-sum__row calculator-sum__row--tax-win">
                <span class="calculator-sum__operator" aria-hidden="true">+</span>
                <span class="calculator-sum__label">Tulumaksust</span>
                <span class="calculator-sum__amount" id="cTax">40 €</span>
              </div>
              <div class="calculator-sum__row calculator-sum__row--secondary">
                <span class="calculator-sum__operator" aria-hidden="true">+</span>
                <span class="calculator-sum__label">Sotsiaalmaksust<span class="inline-help d-inline-block" role="button" tabindex="0" aria-label="Lisainfo" data-bs-toggle="tooltip" data-bs-placement="top" title="Riik lisab sotsiaalmaksu arvelt 4% sinu brutopalgast."></span></span>
                <span class="calculator-sum__amount" id="cState">120 €</span>
              </div>
              <div class="calculator-sum__row calculator-sum__row--total">
                <span class="calculator-sum__operator" aria-hidden="true">=</span>
                <span class="calculator-sum__label">II sambasse iga kuu</span>
                <span class="calculator-sum__amount" id="cInflow">300 €</span>
              </div>
            </div>
            <div class="d-grid">
              <a class="btn btn-primary btn-lg mt-2" href="<?php echo get_app_url('/2nd-pillar-payment-rate'); ?>">Tõstan sissemakset</a>
            </div>
            <p class="mt-2 mb-0 py-2 text-secondary text-center small">Logi sisse ja tee oma valik. Saad seda igal aastal muuta.</p>
          </div>
        </section>
      </div>
    </div>
  </section>

  <div class="article">

    <?php echo get_field( 'intro_1' ) ?: '<p>See ei ole ühekordne, vaid läbi aastate püsiv ja kasvav maksuvõit.</p>'; ?>

    <?php echo get_field( 'intro_2' ) ?: '<p>II pensionisammas ei ole muud kui üks investeerimiskonto, kuhu sa kogud elu jooksul endale vara. See vara on sinu oma ja edasi pärandatav.</p>'; ?>

    <section class="calc" id="calc2" aria-label="II samba vara kasvu kalkulaator">

      <div class="calc-main">
        <div class="bg-gray-2 p-3 rounded-3 calc-inputs">
          <div class="mb-3">
            <label for="gross2" class="form-label">Sinu brutopalk</label>
            <input type="text" class="form-control form-control-lg text-end" id="gross2" value="3000" inputmode="numeric" autocomplete="off">
          </div>
          <div class="mb-3">
            <label for="age" class="form-label">Sinu vanus</label>
            <input type="text" class="form-control form-control-lg text-end" id="age" value="35" inputmode="numeric" autocomplete="off">
          </div>
          <div>
            <label for="ret" class="form-label">Eeldatav tootlus aastas<span class="inline-help d-inline-block" role="button" tabindex="0" aria-label="Lisainfo" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Kogunev summa sõltub tegelikult kujunevast tootlusest ja meie ega keegi teine ei saa kindlat tootlust tagada."></span></label>
            <div class="row">
              <div class="col-12 return-rate">
                <input type="range" class="form-range" id="ret" min="-10" max="10" step="1" value="0" data-unit="%">
                <span class="custom-tooltip">0%</span>
                <button type="button" class="historic-return-rate small text-secondary border-0 bg-transparent">aktsiate ajalooline tootlus 7%</button>
              </div>
            </div>
          </div>
        </div>

        <div class="wealth" id="wealth">
          <h4>Kogud 65. eluaastaks juurde</h4>
          <div class="wealth-viz">
            <div class="vbars" id="vbars" role="group" aria-label="Kogunenud vara pensionieaks 2%, 4% või 6% määraga">
              <button type="button" class="vbar dim" data-r="2" aria-pressed="false"><span class="vtrack"><span class="vval" id="vv2"></span><span class="vcol" id="vc2"></span></span><span class="vlbl">2%</span></button>
              <button type="button" class="vbar dim" data-r="4" aria-pressed="false"><span class="vtrack"><span class="vval" id="vv4"></span><span class="vcol" id="vc4"></span></span><span class="vlbl">4%</span></button>
              <button type="button" class="vbar" data-r="6" aria-pressed="true"><span class="vtrack"><span class="vval" id="vv6"></span><span class="vcol" id="vc6"></span></span><span class="vlbl">6%</span></button>
            </div>
          </div>
        </div>
      </div>

      <div class="calc-breakdown faq-list">
        <div class="qa__question-wrapper">
          <a class="qa__question collapsed" data-bs-toggle="collapse" href="#vara-kasv" role="button" aria-expanded="false" aria-controls="vara-kasv">Kust see vara kasv tuleb?</a>
          <div id="vara-kasv" class="collapse">
          <div class="stack"><span class="s-net" id="sNet"></span><span class="s-tax" id="sTax"></span><span class="s-state" id="sState"></span><span class="s-grow" id="sGrow"></span></div>
          <div class="legend">
            <div><span class="dot d-net"></span> Netopalgast <span class="v" id="lNet"></span></div>
            <div><span class="dot d-tax"></span> Tulumaksust <span class="v" id="lTax"></span></div>
            <div><span class="dot d-state"></span> Sotsiaalmaksust <span class="v" id="lState"></span></div>
            <div><span class="dot d-grow"></span> Tootlusest <span class="v" id="lGrow"></span></div>
            <div class="legend-total">Kokku <span class="v" id="lTotal"></span></div>
          </div>
        </div>
        </div>
      </div>
      <p class="calc-links"><a class="text-reset" data-bs-toggle="collapse" href="#arvutuse-eeldused" role="button" aria-expanded="false" aria-controls="arvutuse-eeldused">Kuidas me seda arvutame?</a></p>
      <div id="arvutuse-eeldused" class="collapse"><div class="calc-detail"><ul>
          <li>Arvutus algab uuest aastast ja näitab, kui palju sa valitud sissemaksega II sambasse tulevikus juurde kogud, lisaks sellele, mis sul juba kogutud on.</li>
          <li>Tulumaks 22%, maksuvaba tulu 700 € kuus, töötuskindlustusmakse 1,6%</li>
          <li>Riik suunab lisaks 4% brutopalga suurusest, võttes selle sotsiaalmaksust. Sotsiaalmaksu osa vähendab veidi sinu <a class="text-reset" href="https://tuleva.ee/sotsmaks">I samba pensioni</a>.</li>
          <li>Arvutustes oleme eeldanud, et sinu palk võiks kasvada 3% aastas.</li>
          <li>Arvutame eeldatava tootlusega aastas, mille valid kalkulaatoris. Mineviku tootlus ei ütle, milliseks kujuneb tootlus tulevikus. Selle näite mõte pole ennustada su vara kasvu, vaid teha arusaadavaks, kuidas mõjutab tulevikuvara see, kui palju sa täna kõrvale paned.</li>
          <li>Tulumaksuvabastus kehtib sissemaksete puhul – vara kasutusel tuleb maksta maksu sõltuvalt sellest, millal ja kuidas vara kasutusele võtad. Eelpensionieast alates saad vara kasutada soodustingimustel – tasud vaid 10% tulumaksu. Kui valid fondipensioni (võtad raha välja vähehaaval, jättes ülejäänu kasvama), ei maksa tulumaksu üldse.</li>
          <li>Arvutused on näitlikud ja üldharivad, mitte personaalne investeerimissoovitus. Pikaajalised summad on näitlikud prognoosid, mitte lubadus – investeeringu väärtus võib aja jooksul nii kasvada kui kahaneda. Pane tähele, et sissemakse tõstmine vähendab sinu igakuist netopalka.</li>
        </ul></div></div>
    </section>

  </div>

  <section class="closing" id="alusta">
    <div class="emphasis-box p-4 p-md-5 text-center text-navy">
      <h2><?php echo esc_html( get_field( 'cta_title' ) ?: 'Pane oma II sammas jõudsamalt kasvama' ); ?></h2>
      <p class="lead mx-auto">Kui tõstad sissemakset <strong>30.&nbsp;novembriks</strong>, kogud juba uuest aastast rohkem ja saad suurema tulumaksuvõidu.</p>
      <p class="m-0 mt-4 pt-2"><a href="<?php echo get_app_url('/2nd-pillar-payment-rate'); ?>" class="btn btn-lg d-block d-md-inline-block m-0 btn-primary">Tõstan sissemakset</a></p>
      <p class="closing-note">Valikut saad igal aastal muuta.</p>
    </div>
  </section>

  <div class="article article-faq qa-block">
    <h2>Hea teada</h2>

    <div class="faq-list">
    <div class="qa__question-wrapper">
      <a class="qa__question collapsed" data-bs-toggle="collapse" href="#kkk-1" role="button" aria-expanded="false" aria-controls="kkk-1">Miks tasub II sambasse rohkem koguda?</a>
      <div id="kkk-1" class="collapse">
        <p>Investeerimises kehtib põhimõte: kasuta alati kõigepealt ära maksueelisega võimalused. II samba sissemaksed on tulumaksuvabad. Kui paned palgast kõrvale <strong>100&nbsp;€</strong>, jõuab sinu II sambasse <strong>128&nbsp;€</strong>. Kui investeeriksid sama raha mujale, jõuaks sinna 100&nbsp;€.</p>
        <p>Riik lisab sotsiaalmaksu arvelt 4% sinu brutopalgast, sõltumata sellest, kas kogud ise 2, 4 või 6%. (<a class="inline" href="https://tuleva.ee/sotsmaks">Vaata, kuidas see mõjutab I sammast</a>)</p>
      </div>
    </div>

    <div class="qa__question-wrapper">
      <a class="qa__question collapsed" data-bs-toggle="collapse" href="#kkk-2" role="button" aria-expanded="false" aria-controls="kkk-2">Mida sissemakse tõstmine tähendab?</a>
      <div id="kkk-2" class="collapse">
        <p>Praegu liigub sinu palgast II sambasse vaikimisi <strong>2%</strong>. Kord aastas saad seda muuta: valida 4% või 6% või minna hiljem tagasi väiksema sissemakse suuruse peale.</p>
        <p>Kui teed avalduse hiljemalt 30. novembril, kehtib uus protsent 1. jaanuarist. See arvestatakse maha juba jaanuaris laekuvast palgast, ka siis, kui see on detsembri töö eest.</p>
        <p>Meie Tulevas suuname ise II sambasse 6% palgast.</p>
      </div>
    </div>

    <div class="qa__question-wrapper">
      <a class="qa__question collapsed" data-bs-toggle="collapse" href="#kkk-3" role="button" aria-expanded="false" aria-controls="kkk-3">Aga kui mul on ootamatult raha vaja?</a>
      <div id="kkk-3" class="collapse">
        <p>Ootamatuste jaoks on kõige parem meelerahufond: raha, mis on eraldi ja kohe kättesaadav. II sambasse kogud pikaks ajaks, seega vali sissemakse, mida saad mugavalt kõrvale panna.</p>
        <p>Sinu valik ei ole lõplik: sissemakset saad igal aastal muuta. Ka II sambast saab vajadusel raha välja võtta, aga maksude mõttes ei ole see kõige mõistlikum.</p>
      </div>
    </div>

    <div class="qa__question-wrapper">
      <a class="qa__question collapsed" data-bs-toggle="collapse" href="#kkk-4" role="button" aria-expanded="false" aria-controls="kkk-4">Kas sissemakset tasub tõsta, kui olen juba üle 60?</a>
      <div id="kkk-4" class="collapse">
        <p>Jah. Nüüd loeb iga aasta – kogumist edasi lükata enam ei saa. Hea uudis: <strong>maksuvõit ei sõltu east.</strong> Iga 78 sendi eest saad II sambasse kohe terve euro, ükskõik kui palju aastaid pensionini jäänud on.</p>
        <p>Ja kui võtad II sambasse kogutud vara kasutusele vähehaaval, jääb ülejäänu fondi tulu teenima veel pikkadeks aastateks. <a class="inline" href="https://tuleva.ee/soovitused/retsept-60ndates-kogumiseks/">Loe lähemalt kogumisest 60ndates →</a></p>
      </div>
    </div>
    </div>
  </div>

</main>
