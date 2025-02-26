<section id="choose-fund" class="py-6">
    <div class="container text-center">
        <h1 class="m-0">Milline Tuleva pensionifond valida?</h1>

        <div class="mt-6 card-deck flex-column flex-lg-row-reverse mx-auto" style="gap: 24px;">
            <div class="card card-selected shadow-sm d-flex flex-column m-0 p-4 br-4 text-left">
                <div style="flex: 1 0 0;">
                    <span class="badge badge-pill badge-primary fs-6 fw-medium" style="position: absolute; left: 50%; transform: translate(-50%); margin-top: -37px;">95% Tuleva II sambas kogujate valik</span>
                    <h2 class="mt-2 mb-2 h4">Tuleva Maailma Aktsiate Pensionifond</h2>
                    <p class="m-0">Madala tasuga indeksfond, mis investeerib täielikult aktsiatesse. Sobib sulle, kui soovid <strong>parimat pikaajalist tootlust</strong> ja sind ei löö rivist välja turu lühiajalised kõikumised.</p>
                </div>
                <div class="my-5 d-flex flex-column flex-sm-row text-center" style="gap: 8px">
                    <div style="flex: 1 0 0;">
                        <div class="bg-gray-2 p-4 br-2">
                            <p class="m-0"><span class="d-block text-secondary">Investeerib</span> <span class="mt-1 d-block lead fw-medium">aktsiatesse</span></p>
                        </div>
                    </div>
                    <div style="flex: 1 0 0;">
                        <div class="bg-gray-2 p-4 br-2">
                            <p class="m-0"><span class="d-block text-secondary">Tasud</span> <span class="mt-1 d-block lead fw-medium">0,32% aastas</span></p>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column flex-sm-row" style="gap: 8px">
                    <a class="btn btn-lg btn-link border fw-normal" role="button" href="https://tuleva.ee/tuleva-maailma-aktsiate-pensionifond/" style="flex: 1 0 0;">Fondi info</a>
                    <a class="btn btn-lg btn-outline-primary fw-medium" role="button" href="https://pension.tuleva.ee/2nd-pillar-flow/"  style="flex: 1 0 0;">Valin aktsiafondi</a>
                </div>
            </div>
            <div class="card shadow-sm d-flex flex-column m-0 p-4 br-4 text-left">
                <div style="flex: 1 0 0;">
                    <h2 class="mt-2 mb-2 h4">Tuleva Maailma Võlakirjade Pensionifond</h2>
                    <p class="m-0">Madala tasuga indeksfond, mis investeerib täielikult võlakirjadesse. Võib sulle sobida, kui otsid stabiilsust ja kaitset turukõikumiste eest, isegi kui see tähendab väiksemat tootlust.</p>
                </div>
                <div class="my-5 d-flex flex-column flex-sm-row text-center" style="gap: 8px">
                    <div style="flex: 1 0 0;">
                        <div class="bg-gray-2 p-4 br-2">
                            <p class="m-0"><span class="d-block text-secondary">Investeerib</span> <span class="mt-1 d-block lead fw-medium">võlakirjadesse</span></p>
                        </div>
                    </div>
                    <div style="flex: 1 0 0;">
                        <div class="bg-gray-2 p-4 br-2">
                            <p class="m-0"><span class="d-block text-secondary">Tasud</span> <span class="mt-1 d-block lead fw-medium">0,32% aastas</span></p>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column flex-sm-row" style="gap: 8px">
                    <a class="btn btn-lg btn-link border fw-normal" role="button" href="https://tuleva.ee/tuleva-maailma-volakirjade-pensionifond/" style="flex: 1 0 0;">Fondi info</a>
                    <a class="btn btn-lg btn-outline-primary fw-medium" role="button" href="https://pension.tuleva.ee/2nd-pillar-flow/"  style="flex: 1 0 0;">Valin võlakirjafondi</a>
                </div>
            </div>
        </div>

        <div class="mt-6 text-secondary">
            <p class="m-0 mb-4">Tulevas tülikat kontoloomist pole – logi lihtsalt sisse oma Smart-ID, mobiil-ID või ID-kaardiga.</p>

            <p class="m-0">Loe lähemalt <a href="https://tuleva.ee/analuusid/tuleva-ei-spekuleeri/">Tuleva pensionifondide investeerimisstrateegiast</a>.</p>
            <p class="m-0">Kui tekib küsimusi, kirjuta <a href="mailto:tuleva@tuleva.ee">tuleva@tuleva.ee</a> või helista <a href="tel:+3726445100">644 5100</a>.</p>
        </div>
    </div>
</section>

<section id="choose-fund" class="py-6">
    <div class="container text-center">
        <h2 class="mb-5">
            <?php the_title() ?>
        </h2>
        <div class="row">
            <div class="col">
                <div class="card-deck">
                    <div class="card shadow-sm">
                        <?php if( have_rows('left_box') ) while ( have_rows('left_box') ) : the_row(); ?>
                            <?php get_template_part('templates/components/fund-choosing-card'); ?>
                        <?php endwhile; ?>
                    </div>
                    <div class="card shadow-sm">
                        <?php if( have_rows('right_box') ) while ( have_rows('right_box') ) : the_row(); ?>
                            <?php get_template_part('templates/components/fund-choosing-card'); ?>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>