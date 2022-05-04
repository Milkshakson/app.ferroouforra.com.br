<?= $this->extend('baseLayout') ?>
<?= $this->section('content') ?>
<div class="pagetitle">
    <h1>Sessão</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Sessão atual</a></li>
        </ol>
    </nav>
</div><!-- End Page Title -->
<section class="section dashboard">
    <div class="row">
        <div class='col-lg-6'>
            <?php

            use CodeIgniter\I18n\Time;

            if (is_array($openedSession) && !empty($openedSession)) {
                //Seta as variáveis
                $buyInList = $openedSession['buyInList'];
                $sitesJogados = [];
                foreach ($buyInList as $bi) {
                    $sitesJogados[] = $bi['pokerSiteName'];
                }
                $sitesJogados = array_unique($sitesJogados);
                $distinctSites = count($sitesJogados);
                $textSitesJogados = ($distinctSites > 1) ? "$distinctSites sites" : "$distinctSites site";
                $sumary = $openedSession['sumary'];
                $startDate = Time::parse($openedSession['startDate']);
                //2021-12-20 17:43:00
                if ($sumary['profit'] > 0)
                    $classTextProfit = 'text-success';
                elseif ($openedSession['sumary']['profit'] == 0)
                    $classTextProfit = 'text-primary';
                else $classTextProfit = 'text-danger';
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
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-currency-dollar"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>$3,264</h6>
                                        <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span>

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
                                        <i class="bi bi-box-arrow-down-left"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>1244</h6>
                                        <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div><!-- End ROI Card -->
                </div>
                <!--
                Array
                (
                    [buyinValue] => 0.00
                    [buyinId] => 3723
                    [currencyName] => dolar
                    [gameId] => 1113
                    [sessionPokerid] => 429
                    [stakingSelling] => 
                    [stakingSold] => 
                    [stakingReturn] => 0.0000000000
                    [profit] => 7.6000000000
                    [markup] => 
                    [prizeIn] => 7.60
                    [prizeReentry] => 
                    [reentryBuyIn] => 
                    [totalBuyIn] => 0.00
                    [totalPrize] => 7.60
                    [totalPrizeStakers] => 0.00000000
                    [endDate] => 2021-12-02 23:41:00
                    [startDate] => 2021-12-02 21:00:00
                    [startWeekDay] => Quinta
                    [fieldSize] => 
                    [gameName] => Freeroll RodriguesSF
                    [pokerSiteName] => Party Poker
                    [pokerSiteShortName] => Party
                    [pokerSiteId] => 3
                    [tipoBuyInName] => Torneio MTT
                    [tipoBuyIn] => 1
                    [isClosed] => 1
                )
                                    -->
                <div class='row'>



                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Lista de Buy-ins</h5>

                            <!-- Default Accordion -->
                            <div class="accordion" id="accordionBuyIns">

                                <?php foreach ($buyInList as $bi) { ?>
                                    <!-- Accordion BuyIns -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading<?=$bi['gameId']?>">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?=$bi['gameId']?>" 
                                            aria-expanded="true" aria-controls="collapse<?=$bi['gameId']?>">
                                                <span><?=$bi['gameName']?>|<?=$bi['pokerSiteName']?></span>
                                            </button>
                                        </h2>
                                        <div id="collapse<?=$bi['gameId']?>" class="accordion-collapse collapse" aria-labelledby="heading<?=$bi['gameId']?>" 
                                        data-bs-parent="#accordionBuyIns">
                                            <div class="accordion-body">
                                                <div class='row'>
                                                <?=$bi['gameName']?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div><!-- End Accordion BuyIns -->

                        </div>
                    </div>
                </div>
            <?php } else { ?>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Dados da abertura</h5>

                        <!-- Multi Columns Form -->
                        <form method="post" class="row g-3">
                            <div class="col-md-12">
                                <label for="descricao" class="form-label">Descrição da sessão</label>
                                <input type="text" name="descricao" class="form-control" id="descricao" value="<?= set_value('descricao') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="data" class="form-label">Data de abertura</label>
                                <input type="date" name="data" class="form-control" id="data" value="<?= set_value('data') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="hora" class="form-label">Hora</label>
                                <input type="time" name="hora" class="form-control" id="time" value="<?= set_value('hora') ?>">
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="estudo" id="estudo" <?= set_value('estudo') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="gridCheck">
                                        Estudei antes da sessão
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="preparacaofisica" id="preparacaofisica" <?= set_value('preparacaofisica') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="preparacaofisica">
                                        Fiz preparação física antes da sessão
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="preparacaomental" id="preparacaomental" <?= set_value('preparacaomental') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="preparacaomental">
                                        fiz preparação mental antes da sessão
                                    </label>
                                </div>
                                <hr class='divider' />
                                <fieldset class="row mb-3">
                                    <legend class="col-form-label col-sm-2 pt-0">Resultados</legend>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="runando" id="gridRadios1" value="downswing" <?= (set_value('runando') == 'downswing') ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="gridRadios1">
                                                Estou em downswing
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="runando" id="gridRadios2" value="upswing" <?= (set_value('runando') == 'upswing') ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="gridRadios2">
                                                Estou em upswing
                                            </label>
                                        </div>
                                        <div class="form-check disabled">
                                            <input class="form-check-input" type="radio" name="runando" id="gridRadios3" value="breakeven" <?= (set_value('runando') == 'breakeven') ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="gridRadios3">
                                                Não estou downswing nem em upswing
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Salvar</button>
                                <button type="reset" class="btn btn-secondary">Limpar</button>
                            </div>
                        </form><!-- End Multi Columns Form -->

                    </div>
                </div>
            <?php } ?>
        </div>
        <div class='col-lg-6'>
            <?= $this->include('common/faq.php'); ?>
        </div>
    </div>
</section>
<?= $this->endSection() ?>