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
            <div class="col-md-8 col-md-offset-2 text-center text-large">
                <p>Tuleva veebirakendus arvutab välja, kui palju kulutad sina teenustasudele tänases pensionifondis ja kui palju sa Tulevaga võidaksid. Siis saad ise otsustada.</p>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>
