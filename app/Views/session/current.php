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

            $variaveis = [];
            if (is_array($openedSession) && !empty($openedSession)) {
                //Seta as variáveis
                $variaveis['buyInList'] = $openedSession['buyInList'];
                $sitesJogados = [];
                foreach ($variaveis['buyInList'] as $bi) {
                    $sitesJogados[] = $bi['pokerSiteName'];
                }
                $sitesJogados = array_unique($sitesJogados);
                $distinctSites = count($sitesJogados);
                $variaveis['sitesJogados'] = $sitesJogados;
                $textSitesJogados = ($distinctSites > 1) ? "$distinctSites sites" : "$distinctSites site";
                $sumary = $openedSession['sumary'];
                $variaveis['textSitesJogados'] = $textSitesJogados;
                $variaveis['sumary'] = $sumary;
                $variaveis['startDate'] = Time::parse($openedSession['startDate']);
                //2021-12-20 17:43:00
                if ($sumary['profit'] > 0)
                    $classTextProfit = 'text-success';
                elseif ($openedSession['sumary']['profit'] == 0)
                    $classTextProfit = 'text-primary';
                else $classTextProfit = 'text-danger';
                extract($variaveis);
            ?>
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
                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Ações</h6>
                                    </li>
                                    <li><a class="dropdown-item text-primary " href="/session/salvaBuyIn"><i class='bi bi-plus-circle'></i>Adicionar</a></li>
                                </ul>
                            </div>
                            <?= view('session/buyins/list-cards.php', ['buyInList' => $buyInList]) ?>
                        </div>
                    </div>
                </div>
            <?php } else {
                echo view('session/form-abertura.php', $variaveis);
            } ?>
        </div>
        <div class='col-lg-6'>
            <div class='row'>
                <?= view('session/cards-sumary.php', $variaveis) ?>
            </div>
            <div class='row'>
                <?= $this->include('common/faq.php'); ?>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>