<div class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <div class="container">
        <div class="row row-spacing-bottom">
            <div class="col-md-12">
                <h1 class="h1-sm text-center">Tuleva Fondid AS on Eesti esimene ja ainus inimeste endi pensionifondi valitseja. Tee meie edulugu enda omaks!</h1>
            </div>
        </div>
        <div class="row row-spacing text-boxes">
            <div class="col-md-6">
                <div class="text-box">
                    <h2 class="text-box__title">Mida me sulle lubame?</h2>
                    <ul>
                        <li>Madalad kulud – suurem tulu sulle</li>
                        <li>Selged investeerimisreeglid</li>
                        <li>Tegutseme alati pensionikogujate, mitte pankade huvides</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-box">
                    <h2 class="text-box__title">Mida me sulle ei luba?</h2>
                    <p>Muinasjutulist tootlust. Maailmamajanduse käekäik pole meie teha. Meie teha on, et suur osa sinu säästudest ei kuluks ekstravagantsetele reklaamikampaaniatele ja muudele tegevustele, mis sinule väärtust ei loo.</p>
                </div>
            </div>
        </div>
        <div class="row row-spacing">
            <div class="col-md-12">
                <div class="media">
                    <div class="media-body">
                        <h2 class="media-heading">Tuleva Maailma Aktsiate Pensionifond</h2>
                        <ul>
                            <li>73% fondi paigutatud rahast investeeritakse maailma aktsiaturu indeksit (MSCI AWCI) järgivatesse fondidesse ja 27% maailma võlakirjaturu indeksit (Barclays Global Aggregate Bond Index) järgivatesse fondidesse</li>
                            <li>Fondi strateegia on agressiivne</li>
                            <li>Fondi riskitase on keskmine</li>
                            <li>Fondi NAVi muutusete graafiku leiad siit: (graafik).</li>
                            <li>Fondi kogukulumäär on XXX. See koosneb haldustasust xx% ja tehingu ning allfondide kulust YY%</li>
                        </ul>
                    </div>
                    <div class="media-right">
                        <img class="media-object" src="<?php echo get_template_directory_uri() ?>/img/aktsiafond-pie.svg" alt="">
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-spacing">
            <div class="col-md-12">
                <div class="media">
                    <div class="media-left">
                        <img class="media-object" src="<?php echo get_template_directory_uri() ?>/img/volakirjad-pie.svg" alt="">
                    </div>
                    <div class="media-body">
                        <h2 class="media-heading">Tuleva Maailma Võlakirjade Pensionifond</h2>
                        <ul>
                            <li>100% fondi paigutatud rahast investeeritakse maailma võlakirjaturu indeksit (Barclays Global Aggregate Bond Index) järgivatesse fondidesse</li>
                            <li>Fondi strateegia on konservatiivne</li>
                            <li>Fondi riskitase on madal</li>
                            <li>Fondi NAVi muutusete graafiku leiad siit: (graafik).</li>
                            <li>Fondi kogukulumäär on XXX. See koosneb haldustasust xx% ja tehingu ning allfondide kulust YY%</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-alt">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="statistics-box">
                        <div class="statistics-box__row">Tuleva fondidega on liitunud <strong class="statistics-box__number">3398</strong> inimest.</div>
                        <div class="statistics-box__row">Tuleva maailma aktsiaturu pensionifondi maht on <strong class="statistics-box__number">35 654 848</strong> eurot.</div>
                        <div class="statistics-box__row">Tuleva maailma võlakirjade pensionifondi maht on <strong class="statistics-box__number">6 548 121</strong> eurot.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>
