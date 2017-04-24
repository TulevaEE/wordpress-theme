<div class="page-container">
    <div class="container">
        <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
        <div class="row">
            <div class="col-md-12">
                <h1 class="h1-sm h1-bottom-margin text-center">Tuleva Fondid AS on Eesti esimene ja ainus inimeste endi pensionifondi valitseja. Tee meie edulugu enda omaks!</h1>
            </div>
        </div>
        <div class="row text-boxes">
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
        <?php endwhile; ?>
    </div>
</div>
