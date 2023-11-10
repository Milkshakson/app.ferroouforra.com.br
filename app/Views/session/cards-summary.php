<?php if(isset($variaveis))extract($variaveis);?>
<?php 
$buyInList  = $buyInList  ?? [] ;
$textSitesJogados  = $textSitesJogados  ??'' ;
?>
<div class='row'>
    <!-- Sales Card -->
    <div class="col-xxl-4 col-md-6">
        <div class="card info-card sales-card">
            <div class="card-body">
                <h5 class="card-title">Torneios <span>|Nesta sessão</span></h5>

                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ri ri-grid-fill"></i>
                    </div>
                    <div class="ps-3">
                        <h6><?= count($buyInList) ?></h6>
                        <span class="text-muted small pt-2 ps-1"><?= $textSitesJogados ?></span>

                    </div>
                </div>
            </div>

        </div>
    </div><!-- End Sales Card -->
    <!-- Revenue Card -->
    <div class="col-xxl-4 col-md-6">
        <div class="card info-card revenue-card">
            <div class="card-body">
                <h5 class="card-title">Lucro <span>|Nesta sessão</span></h5>

                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center   <?= $classTextProfit ?>">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="ps-3">
                        <h6 class="  <?= $classTextProfit ?>"><?= dolarFormat($summary['profit']) ?></h6>
                    </div>
                </div>
            </div>

        </div>
    </div><!-- End Revenue Card -->

    <!-- ROI Card -->
    <div class="col-xxl-4 col-xl-12">

        <div class="card info-card customers-card">
            <div class="card-body">
                <h5 class="card-title">ROI <span>|Nesta sessão</span></h5>

                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi <?= $summary['percentageROI'] > 0 ? 'bi-box-arrow-up-right' : 'bi-box-arrow-down-left' ?>"></i>
                    </div>
                    <div class="ps-3">
                        <h6><?= percent($summary['percentageROI']) ?></h6>
                    </div>
                </div>

            </div>
        </div>

    </div><!-- End ROI Card -->
</div>