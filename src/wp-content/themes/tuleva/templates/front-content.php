<div class="page-container">
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    <div class="container">
        <div class="row row-spacing-half">
            <div class="col-md-12">
                <h1 class="text-center">Eesti pensionifondid on kallid ja kehva tootlusega. Tuleva loodi, et seda muuta.</h1>
            </div>
        </div>
        <div class="row row-spacing-half">
            <div class="col-md-12 text-center">
                <a href="#" class="btn btn-primary btn-xl">Sisene Tuleva äppi</a>
            </div>
        </div>
        <div class="row row-spacing-half">
            <div class="col-md-8 col-md-offset-2 text-center text-lg">
                <p class="no-margin">Tuleva veebirakendus arvutab välja, kui palju kulutad sina teenustasudele tänases pensionifondis ja kui palju sa Tulevaga võidaksid. Siis saad ise otsustada.</p>
            </div>
        </div>
        <div class="row row-spacing-half text-navy">
            <div class="col-md-6">
                <div class="text-box text-box--rounder">
                    <h2 class="text-box__title text-box__title--border-lightblue">Miks koguda pensionit Tuleva fondidesse?</h2>
                    <ul class="list-style-checkmark text-lg">
                        <li>madalad kulud — rohkem jääb sulle</li>
                        <li>selged investeerimisreeglid</li>
                        <li>kogud raha endale, mitte pangale</li>
                    </ul>
                    <em>Tulevas pensioni kogumiseks <span class="text-highlight">ei pea olema ühistu liige.</span></em>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-box text-box--rounder">
                    <h2 class="text-box__title text-box__title--border-blue">Miks astuda Tuleva ühistu liikmeks?</h2>
                    <ul class="list-style-plussign text-lg">
                        <li>oled oma pensionifondi kaasomanik</li>
                        <li>saad otsustada Tuleva tuleviku üle</li>
                        <li>kogud igal aastal <span class="text-highlight">0,05% liikmeboonust</span></li>
                    </ul>
                    <em>Pensioni kogumine on tänu liikmeboonusele veel soodsam.</em>
                </div>
            </div>
        </div>
        <div class="row row-spacing-half">
            <div class="col-md-12">
                <div class="statistics-box no-padding">
                    <div class="statistics-box__row">Tuleva fondidega on liitunud <strong class="text-highlight">3398</strong> inimest.</div>
                    <div class="statistics-box__row">Tuleva maailma aktsiaturu pensionifondi maht on <strong class="text-highlight">35 654 848</strong> eurot.</div>
                    <div class="statistics-box__row">Tuleva maailma võlakirjade pensionifondi maht on <strong class="text-highlight">6 548 121</strong> eurot.</div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-alt">
        <div class="container">
            <div class="row">
                <div class="quotes-box">
                    <div class="col-md-4 quotes-box__item">
                        <div class="quotes-box__content">
                            <div class="quotes-box__text">Nüüd on pall kõigi meie käes. Tuleva liikmed üheskoos suudavad süsteemi muuta.</div>
                            <div class="quotes-box__author">
                                <img class="quotes-box__author__image" src="<?php echo get_template_directory_uri() ?>/img/jaak-roosaar.jpg" alt="Jaak Roosaar">
                                <span class="quotes-box__author__info"><a href="#">Jaak Roosaar, asutaja</a></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 quotes-box__item">
                        <div class="quotes-box__content">
                            <div class="quotes-box__text">Olen Tuleva asutajate seas, sest advokaadina näen siin põnevat uuenduslikkust ja kodanikuna laiemat kasu Eestile.</div>
                            <div class="quotes-box__author">
                                <img class="quotes-box__author__image" src="<?php echo get_template_directory_uri() ?>/img/kirsti-pent.jpg" alt="Kirsti Pent">
                                <span class="quotes-box__author__info"><a href="#">Kirsti Pent, asutaja</a></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 quotes-box__item">
                        <div class="quotes-box__content">
                            <div class="quotes-box__text">Tuleva eripära on lihtsalt, et sinu raha eest ei osta omale luksust keegi teine.</div>
                            <div class="quotes-box__author">
                                <img class="quotes-box__author__image" src="<?php echo get_template_directory_uri() ?>/img/mart-madrus.jpg" alt="Mart Madrus">
                                <span class="quotes-box__author__info"><a href="#">Mart Madrus, pensionikoguja</a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row row-spacing-half">
            <div class="col-md-12 text-center">
                <h2 class="h1-xs heading-bottom-spacing">Asutajaliikmed</h2>
            </div>
            <div class="col-md-8 col-md-offset-2 text-center text-lg">
                <p>
                    Tuleva Tulundusühistu asutasid 22 Eesti ettevõtjat, rahanduseksperti ja ühiskonnategelast, kes leidsid, et Eesti inimesed väärivad paremat tulevikku.
                </p>
            </div>
        </div>
        <div class="row row-spacing-half">

        </div>
    </div>
    <?php endwhile; ?>
</div>