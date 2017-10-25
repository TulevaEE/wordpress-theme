<section id="main-header">
    <div class="bg-hero-main d-flex flex-column">
      <div class="container my-auto">
        <div class="row align-items-center py-5">
          <div class="col-lg-6 text-center text-lg-left px-sm-5 px-lg-0 pr-lg-5 pr-lg-6">
            <h1>Kogu pensionit endale, mitte pangale.</h1>
            <p class="lead text-navy">Tuleva pensionifondid kuuluvad inimestele endile ja on kuni 4x soodsama valitsemistasuga.</p>
            <a href="#fondid" class="btn btn-primary btn-lg mb-3 d-none d-md-block">Hakka tasudelt säästma</a>
            <p class="small text-navy mb-md-5 mb-lg-0 d-none d-md-block">Fondivahetus on TASUTA ja võtab 5 minutit.</p>
          </div>
          <div class="col-lg-6">
            <div class="card calculator shadow-md">
              <div class="card-body p-3 text-center">
                <form class="d-flex flex-row justify-content-center pb-3">
                  <div class="d-inline-block align-items-center text-medium mr-2">
                    <span>Kui oled</span>
                    <input class="form-control d-inline-block mx-2" id="age" type="text" value="29">     
                    <span>ja teenid kuus</span>
                    <input class="form-control d-inline-block mx-2" id="wage" type="text" value="1800">
                    <span>eurot neto, <br class="d-none d-lg-inline">kogud <a id="calculator" href="#calculatorModal">eelduslikult</a> pensioniks</span>
                  </div>
                </form>
                <div class="d-flex p-3 rounded-top bg-blueish-gray flex-row justify-content-between align-items-center">
                  <select class="form-control" name="pensionFunds" id="comparisonFund">
                    <option value="">Eesti keskmises pensionifondis</option>
                    <option value="">LHV XS Pensionifond</option>
                    <option value="">Eesti keskmine pensionifond</option>
                    <option value="">Eesti keskmine pensionifond</option>
                    <option value="">Eesti keskmine pensionifond</option>
                    <option value="">Eesti keskmine pensionifond</option>
                    <option value="">Eesti keskmine pensionifond</option>
                    <option value="">Eesti keskmine pensionifond</option>
                  </select>
                  <div class="d-flex flex-column align-items-end ml-4">
                    <h5 class="mb-0">485 144€</h5>
                    <small class="text-secondary text-nowrap">Tasud aastas 1,34%</small>
                  </div>
                </div>
                <div class="d-flex p-3 rounded-bottom bg-blueish-gray flex-row justify-content-between align-items-center">
                  <h6>Madala tasuga fondis</h6>
                  <div class="d-flex flex-column align-items-end">
                    <h5 class="mb-0">545 654€</h5>
                    <small class="text-secondary">Tasud aastas 0,34%</small>
                  </div>
                </div>
                <div class="d-flex mt-3 justify-content-end align-items-center">
                    <h6 class="mb-0 mr-3 text-uppercase text-bold text-navy">Kõrgete tasude kulu</h6>
                    <h1 class="mb-0 mr-3 text-sans text-danger">70 826€</h1>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Small screen subscribe section -->
        <div class="row d-md-none mb-5 text-center">
          <div class="col input-group-lg">
            <h5 class="mb-4">Soovid teada, kuidas säästmisega alustada?</h5>
            <input class="form-control d-block mb-3" type="email" name="email" placeholder="E-posti aadress" id="">
            <a href="#" class="btn btn-primary btn-lg btn-block">Saada mulle infot</a>
          </div>
        </div>
      </div>
      <!-- Credentials -->
      <div class="container-fluid bg-blueish-gray d-none d-sm-block py-4">
        <div class="container">
          <div class="row">
            <div class="col col-md-6 d-flex">
              <img src="<?php echo get_template_directory_uri() ?>/img/icon-lock.svg" alt="Tuleva on turvaline valik" class="mr-4">
              <div class="d-flex flex-column justify-content-center">
                <p class="mb-2 text-navy">Sama turvaline kui panga pensionifond.</p>
                <a id="security" href="#securityModal" class="text-uppercase text-medium">Uuri lähemalt</a>
              </div>
            </div>
            <div class="col-md-6 d-none d-md-flex align-items-center">
              <span class="membercount mr-4">5076</span>
              <p class="mb-0 text-navy">Eesti inimest kogub pensionit Tuleva pensionifondides.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>