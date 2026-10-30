<main id="main" class="page-container second-pillar-246-page">

  <section class="hero">
    <div class="hero-in">
      <div class="hero-text">
        <span class="deadline-kicker">Tähtaeg 30. november</span>
        <h1><?php echo esc_html( get_field( 'hero_title' ) ?: 'Tõsta II samba sissemakset' ); ?></h1>
        <p class="dek">Nüüd saad ise valida, kas kogud 2, 4 või 6% brutopalgast. Suurem protsent tähendab rohkem maksuvõitu: iga tulevikuks kõrvale pandud euro maksab sulle <span class="hl">vaid 78 senti</span>.</p>
      </div>
      <div class="hero-card">
        <section class="calc calc-tax" id="calc" aria-label="II samba maksuvõidu kalkulaator">
          <div class="trow trow-input">
            <label for="gross">Brutopalk</label>
            <div class="salary-row"><input type="number" id="gross" value="3000" min="0" step="any"> <span class="unit">€</span></div>
          </div>
          <div class="trow trow-input">
            <span class="trow-label" id="rateLbl">Sinu panus brutopalgast</span>
            <div class="seg" id="segRate" role="group" aria-labelledby="rateLbl"><button type="button" data-r="2" aria-pressed="false">2%</button><button type="button" data-r="4" aria-pressed="false">4%</button><button type="button" data-r="6" aria-pressed="true">6%</button></div>
          </div>
          <hr class="tdiv">
          <div role="status" aria-live="polite">
          <div class="trow"><span class="tlbl">Netopalgast paned kõrvale</span><span class="tval"><span id="cNet">140 €</span> <span class="per">/ kuus</span></span></div>
          <div class="trow"><span class="tlbl">Sinu II sambasse jõuab</span><span class="tval"><span id="cPil">180 €</span> <span class="per">/ kuus</span></span></div>
          <div class="trow trow-sub"><span class="tlbl">… sotsiaalmaksust lisaks</span><span class="tval"><span id="cState">120 €</span> <span class="per">/ kuus</span></span></div>
          <div class="trow trow-sum"><span class="tlbl">Kokku II sambasse</span><span class="tval"><span id="cInflow">300 €</span> <span class="per">/ kuus</span></span></div>
          <div class="trow trow-hero"><span class="tlbl">Sinu tulumaksuvõit aastas</span><span class="tval tval-win" id="cTax">480 €</span></div>
          </div>
          <a class="cta" href="<?php echo get_app_url('/2nd-pillar-payment-rate'); ?>">Tõstan sissemakset</a>
          <p class="tfoot">Tähtaeg <b class="red-date">30. november</b>. Valikut saad igal aastal muuta.</p>
        </section>
      </div>
    </div>
  </section>

  <div class="article">

    <?php echo get_field( 'intro_1' ) ?: '<p>See ei ole ühekordne, vaid läbi aastate püsiv ja kasvav maksuvõit.</p>'; ?>

    <?php echo get_field( 'intro_2' ) ?: '<p>II pensionisammas ei ole muud kui üks investeerimiskonto, kuhu sa kogud elu jooksul endale vara. See vara on sinu oma ja edasi pärandatav.</p>'; ?>

    <section class="calc" id="calc2" aria-label="II samba vara kasvu kalkulaator">

      <div class="midinput">
        <p class="mi-lead">Turgude tootlus kõigub, aga <b>kuni 3× maksuvõit</b> annab sulle kindla edumaa.</p>
        <div class="field mi-salary">
          <label for="gross2">Sinu brutopalk</label>
          <div class="salary-row"><input type="number" id="gross2" value="3000" min="0" step="any"> <span class="unit">€</span></div>
        </div>
        <div class="adv">
          <div class="field"><label for="age">Sinu vanus — <span class="rngval" id="ageVal">35</span></label>
            <input type="range" id="age" min="18" max="62" value="35"><div class="hint">Arvutame siin kuni 65. eluaastani. Kui töötad edasi, saad kogumist jätkata ka hiljem. Ja tegelikult ei ole sul tõenäoliselt vaja raha ühe korraga – saad seda kasutada vähehaaval, samal ajal kui ülejäänu turgudel edasi tootlust teenib.</div></div>
          <div class="field"><label for="ret">Oodatav tootlus aastas — <span class="rngval" id="retVal">0%</span></label>
            <div class="ret-slider"><input type="range" id="ret" min="0" max="9" step="0.5" value="0"><span class="ret-mark" aria-hidden="true"><span class="ret-mark-arrow">↑</span><span class="ret-mark-lbl">aktsiate ajalooline tootlus 7%</span></span></div>
            <div class="hint">Maailma aktsiaturud on viimasel 30 aastal toonud <span class="mark7">keskmiselt 7% aastas</span> tulu, aga see ei ole tulevikutootluse garantii. Mis on kindel: pannes rohkem kõrvale, säästad rohkem maksudelt ja kogud rohkem.</div></div>
        </div>
      </div>

      <div class="wealth" id="wealth">
        <h4>Kui palju vara juurde kogud?</h4>
        <div class="wealth-viz">
          <div class="vbars" id="vbars" role="group" aria-label="Kogunenud vara pensionieaks 2%, 4% või 6% määraga">
            <div class="vbar dim" data-r="2"><span class="vval" id="vv2"></span><span class="vtrack"><span class="vcol" id="vc2"></span></span><span class="vlbl">2%</span></div>
            <div class="vbar dim" data-r="4"><span class="vval" id="vv4"></span><span class="vtrack"><span class="vcol" id="vc4"></span></span><span class="vlbl">4%</span></div>
            <div class="vbar" data-r="6"><span class="vval" id="vv6"></span><span class="vtrack"><span class="vcol" id="vc6"></span></span><span class="vlbl">6%</span></div>
          </div>
          <div class="chart-wrap"><div id="chart"></div><p class="chart-cap">Liittulu teeb aastatega oma töö · 2% / 4% / 6%</p></div>
        </div>
        <p class="note-proj">Arvutus algab uuest aastast ja näitab, kui sa valitud sissemaksega II sambasse tulevikus <strong>juurde</strong> kogud — lisaks sellele, mis juba siiani kogunud oled.</p>
      </div>

      <details id="dec">
        <summary>Kust see vara kasv tuleb? <span class="chev" aria-hidden="true">›</span></summary>
        <div class="dbody">
          <div class="stack"><span class="s-net" id="sNet"></span><span class="s-state" id="sState"></span><span class="s-tax" id="sTax"></span><span class="s-grow" id="sGrow"></span></div>
          <div class="legend">
            <div><span class="dot d-net"></span> Oma netopalgast kõrvale pandud <span class="v" id="lNet"></span></div>
            <div><span class="dot d-state"></span> Riigi panus sotsiaalmaksust <span class="v" id="lState"></span></div>
            <div><span class="dot d-tax"></span> Riigi panus tulumaksust (sinu maksuvõit) <span class="v" id="lTax"></span></div>
            <div><span class="dot d-grow"></span> Näite eeldustes arvestatud tootlus <span class="v" id="lGrow"></span></div>
          </div>
        </div>
      </details>

      <details class="assume">
        <summary>Kuidas me seda arvutame? <span class="chev" aria-hidden="true">›</span></summary>
        <div class="dbody"><ul>
          <li>Tulumaks 22%, maksuvaba tulu 700 €/kuus, töötuskindlustusmakse 1,6%</li>
          <li>Riik suunab lisaks +4% brutopalga suurusest, võttes selle sotsiaalmaksust. See mõjutab I samba pensionit — <a class="inline" href="https://tuleva.ee/sotsmaks">tuleva.ee/sotsmaks</a></li>
          <li>Arvutustes oleme eeldanud, et sinu palk võiks kasvada 3% aastas.</li>
          <li>Vara keskmine näitlik tootlus aastas on sinu valitud liuguri väärtus. Mineviku tootlus ei ütle, milliseks kujuneb tootlus tulevikus. Selle näite mõte pole ennustada su vara kasvu, vaid teha arusaadavaks, kuidas mõjutab tulevikuvara see, kui palju sa täna kõrvale paned.</li>
          <li>Tulumaksuvabastus kehtib sissemaksete puhul – vara kasutusel tuleb maksta maksu sõltuvalt sellest, millal ja kuidas vara kasutusele võtad. Eelpensionieast alates saad vara kasutada soodustingimustel – tasud vaid 10% tulumaksu. Kui valid fondipensioni (võtad raha välja vähehaaval, jättes ülejäänu kasvama), ei maksa tulumaksu üldse.</li>
          <li>Arvutused on näitlikud ja üldharivad, mitte personaalne investeerimissoovitus. Pikaajalised summad on näitlikud prognoosid, mitte lubadus – investeeringu väärtus võib aja jooksul nii kasvada kui kahaneda. Pane tähele, et maksemäära tõstmine vähendab sinu igakuist netopalka.</li>
        </ul></div>
      </details>

    </section>

    <h2>Hea teada</h2>

    <details class="qa">
      <summary>Miks on kaval II sambasse rohkem koguda? <span class="chev" aria-hidden="true">›</span></summary>
      <div class="qabody">
        <p>Investeerimises kehtib põhimõte: kasuta alati kõigepealt ära maksueelisega võimalused. II samba sissemaksed on tulumaksuvabad. Tänu sellele saad iga <strong>78 sendi</strong> eest, mis sa oma netopalgast „ohverdad“, oma II sambasse <strong>terve euro</strong>. Kui investeeriksid sama raha mujale, alustaksid 22% miinusega.</p>
      </div>
    </details>

    <details class="qa">
      <summary>Mida sissemakse tõstmine tähendab? <span class="chev" aria-hidden="true">›</span></summary>
      <div class="qabody">
        <p>Su palgast liigub palgapäeval II sambasse vaikimisi <strong>2%</strong>. Riik suunab omalt poolt sinu II sambasse veel <strong>4% sinu brutopalga suurusest</strong>. (See tuleb sotsiaalmaksust – II sambasse kogumine on ainus viis suunata see enda isiklikule kontole.)</p>
        <p>Sina saad üks kord aastas sissemakse suurust tõsta (või vajadusel langetada) – valikus on 2% kõrval 4% või 6%. Mina ise olen juba suunanud oma II sambasse 6% oma palgast – nagu ka juba ligi 50 000 Eesti inimest.</p>
      </div>
    </details>

    <details class="qa">
      <summary>Aga kui mul ootamatult raha vaja on? <span class="chev" aria-hidden="true">›</span></summary>
      <div class="qabody">
        <p>Tõsta sissemakset nii palju, kui saad mugavalt tulevikuks kõrvale panna – II sambasse kogud vara pikaks ajaks. Kuid tänane valik ei seo sind pöördumatult: sissemakse suurust saad vajadusel muuta igal aastal, nii üles kui ka alla.</p>
        <p>Kogutud vara kasutamine muutub paindlikuks ja maksusoodsaks pensionieale lähenedes.</p>
      </div>
    </details>

    <details class="qa">
      <summary>Kas sissemakset tasub tõsta, kui olen juba üle 60? <span class="chev" aria-hidden="true">›</span></summary>
      <div class="qabody">
        <p>Jah. Nüüd loeb iga aasta – kogumist edasi lükata enam ei saa. Hea uudis: <strong>maksuvõit ei sõltu east.</strong> Iga 78 sendi eest saad II sambasse kohe terve euro, ükskõik kui palju aastaid pensionini jäänud on.</p>
        <p>Ja kui võtad II sambasse kogutud vara kasutusele vähehaaval, jääb ülejäänu fondi tulu teenima veel pikkadeks aastateks. <a class="inline" href="https://tuleva.ee/soovitused/retsept-60ndates-kogumiseks/">Loe lähemalt kogumisest 60ndates →</a></p>
      </div>
    </details>

  </div>

  <section class="ctaband">
    <div class="ctaband-in">
      <h2><?php echo esc_html( get_field( 'cta_title' ) ?: 'Kogu uuest aastast rohkem' ); ?></h2>
      <?php echo get_field( 'cta_intro' ) ?: '<p>Me Tulevas oleme veendunud, et kõige kavalam on suunata II sambasse 6%. Ligi pool meiega koos kogujatest on sissemakset juba tõstnud.</p>'; ?>
      <figure class="savers">
        <img src="https://tuleva.ee/wp-content/uploads/2024/11/246-faces-1.jpg" alt="Tuleva kogujad, kes on II samba makset juba tõstnud" loading="lazy">
        <figcaption>Nende hulgas Indrek Kasela, Riin Mäesalu, Marko Oolo, Kristi Saare, Priit Lepasepp ja teised.</figcaption>
      </figure>
      <?php echo get_field( 'cta_outro' ) ?: '<p>Tee avaldus enne 30. novembrit ära. Siis hakkab suurem tükike palgast sinu II sambasse liikuma juba uuest aastast. Kui tähtaeg mööda lasta, saad uue võimaluse suuremat maksuvõitu kasutada alles aasta pärast.</p><p>Avalduse tegemine võtab paar minutit. Tee see kohe ära ja siis tunne õigustatult uhkust. Oled teinud oma tuleviku heaks midagi, mis töötab edasi iseenesest, iga kuu, märkamatult.</p>'; ?>
      <a class="cta cta-2l" href="<?php echo get_app_url('/2nd-pillar-payment-rate'); ?>"><span class="cta-main">Tõstan sissemakset →</span><span class="cta-dl">Tähtaeg 30. november</span></a>
    </div>
  </section>

</main>
