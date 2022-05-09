<?php

use App\Libraries\APPException;
use App\Providers\PokerSessionProvider;
use CodeIgniter\I18n\Time;

extract($variaveis);
/*
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
                    */
$tipoBuyIn = isset($bi) ? $bi['tipoBuyIn'] : '';
$pokerSiteId = isset($bi) ? $bi['pokerSiteId'] : '';
$gameName = isset($bi) ? $bi['gameName'] : '';
$startDate = isset($bi) ? $bi['startDate'] : '';
$buyinValue = isset($bi) ? $bi['buyinValue'] : '';
$sessionPokerid = isset($bi) ? $bi['sessionPokerid'] : $openedSession['id'];
if (!empty($startDate)) {
    pre($startDate, 1);
    $startDate = Time($startDate);
    $startTime = '';
} else {
    $startTime = '';
}
?>
<div class='col-lg-6'>
    <div class="card">

        <div class="card-body">
            <h5 class="card-title">Dados da abertura</h5>

            <!-- Multi Columns Form -->
            <form method="post" class="row g-3">
                <input type='hidden' name="sessionPokerid" value="<?= set_value('sessionPokerid', $sessionPokerid) ?>" />
                <div class="col-md-3">
                    <label for="startDate" class="form-label">Data Início</label>
                    <input type="date" name="startDate" class="form-control" id="startDate" value="<?= set_value('startDate', $startDate) ?>">
                </div>

                <div class="col-md-3">
                    <label for="startTime" class="form-label">Hora Início</label>
                    <input type="time" name="startTime" class="form-control" id="startTime" value="<?= set_value('startTime', $startTime) ?>">
                </div>

                <div class="col-md-6">
                    <label for="tipoBuyIn" class="form-label">Tipo do buy in</label>
                    <select class="form-select" name="tipoBuyIn" id="tipoBuyIn">
                        <option></option>
                        <?php foreach ($tiposBuyIn as $optionTipoBuyIn) { ?>
                            <option value='<?= $optionTipoBuyIn['id'] ?>' <?= set_value('tipoBuyIn', $tipoBuyIn) == $optionTipoBuyIn['id'] ? 'selected' : '' ?>>
                                <?= $optionTipoBuyIn['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="pokerSiteId" class="form-label">Site</label>
                    <select class="form-select" name="pokerSiteId" id="pokerSiteId">
                        <option></option>
                        <?php foreach ($pokerSites as $optionPokerSiteId) { ?>
                            <option value='<?= $optionPokerSiteId['id'] ?>' <?= set_value('pokerSiteId', $pokerSiteId) == $optionPokerSiteId['id'] ? 'selected' : '' ?>>
                                <?= $optionPokerSiteId['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-7">
                    <label for="gameName" class="form-label">Qual é o jogo?</label>
                    <input type="text" name="gameName" class="form-control" id="gameName" value="<?= set_value('gameName', $gameName) ?>">
                </div>
                <div class="col-md-4">
                    <label for="buyinValue" class="form-label">Qual é o valor do buy in?</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text" id="IGPBuyinValue"><i class="bi bi-currency-dollar"></i></span>
                        <input type="text" name="buyinValue" class="form-control money-dolar" id="buyinValue" value="<?= set_value('buyinValue', $buyinValue) ?>">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="isClosed" id="isClosed" <?= set_value('isClosed') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="isClosed">
                            Este evento já acabou
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
</div>