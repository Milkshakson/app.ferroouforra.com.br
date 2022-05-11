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
$buyinId = isset($bi) ? $bi['buyinId'] : null;
$tipoBuyIn = isset($bi) ? $bi['tipoBuyIn'] : '';
$pokerSiteId = isset($bi) ? $bi['pokerSiteId'] : '';
$gameName = isset($bi) ? $bi['gameName'] : '';
$startDate = isset($bi) ? $bi['startDate'] : '';
$endDate = isset($bi) ? $bi['endDate'] : '';
$buyinValue = isset($bi) ? $bi['buyinValue'] : '';
$prizeIn = isset($bi) ? $bi['prizeIn']  : '';
$prizeReentry = isset($bi) ? $bi['prizeReentry']  : '';
$reentryBuyIn = isset($bi) ? $bi['reentryBuyIn']  : '';
$stakingSelling = isset($bi) ? $bi['stakingSelling'] : '';
$stakingSold = isset($bi) ? $bi['stakingSold'] : '';
$markup = isset($bi) ? $bi['markup'] : '';
$isClosed = isset($bi) ? $bi['isClosed'] : false;
$fieldSize = isset($bi) ? $bi['fieldSize'] : '';
$position = isset($bi) ? $bi['position'] : '';
$finalTable = isset($bi) ? $bi['finalTable'] : '';
$sessionPokerid = isset($bi) ? $bi['sessionPokerid'] : $openedSession['id'];
if (!empty($startDate)) {
    $objStartDate = Time::createFromFormat('Y-m-d H:i:s', $startDate);
    $startDate = $objStartDate->format('Y-m-d');
    $startTime = $objStartDate->format('H:i');
} else {
    $startTime = '';
}
if (!empty($endDate)) {
    $objEndDate = Time::createFromFormat('Y-m-d H:i:s', $endDate);
    $endDate = $objEndDate->format('Y-m-d');
    $endTime = $objEndDate->format('H:i');
} else {
    $endTime = '';
}
?>
    <div class="card">

        <div class="card-body">
            <h5 class="card-title"><?= ($buyinId) ? 'Edição' : 'Cadastro' ?> de buy in</h5>

            <!-- Multi Columns Form -->
            <form method="post" class="row g-3">
                <input autocomplete="off" type='hidden' name="sessionPokerid" value="<?= set_value('sessionPokerid', $sessionPokerid) ?>" />
                <input autocomplete="off" type='hidden' name="buyinId" value="<?= set_value('buyinId', $buyinId) ?>" />
                <div class="col-6 col-sm-6 col-md-3 col-lg-3">
                    <label for="startDate" class="form-label">Data Início</label>
                    <input autocomplete="off" type="date" name="startDate" class="form-control" id="startDate" value="<?= set_value('startDate', $startDate) ?>" required>
                </div>

                <div class="col-6 col-sm-6 col-md-3">
                    <label for="startTime" class="form-label">Hora Início</label>
                    <input autocomplete="off" type="time" name="startTime" class="form-control" id="startTime" value="<?= set_value('startTime', $startTime) ?>" required>
                </div>

                <div class="col-md-3 col-lg-3">
                    <label for="tipoBuyIn" class="form-label">Tipo do buy in</label>
                    <select class="form-select" name="tipoBuyIn" id="tipoBuyIn" required>
                        <option></option>
                        <?php foreach ($tiposBuyIn as $optionTipoBuyIn) { ?>
                            <option value='<?= $optionTipoBuyIn['id'] ?>' <?= set_value('tipoBuyIn', $tipoBuyIn) == $optionTipoBuyIn['id'] ? 'selected' : '' ?>>
                                <?= $optionTipoBuyIn['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="pokerSiteId" class="form-label">Site</label>
                    <select class="form-select" name="pokerSiteId" id="pokerSiteId" required>
                        <option></option>
                        <?php foreach ($pokerSites as $optionPokerSiteId) { ?>
                            <option value='<?= $optionPokerSiteId['id'] ?>' <?= set_value('pokerSiteId', $pokerSiteId) == $optionPokerSiteId['id'] ? 'selected' : '' ?>>
                                <?= $optionPokerSiteId['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-12 col-12 col-lg-12 col-sm-12 ">
                    <label for="gameName" class="form-label">Nome do jogo</label>
                    <input autocomplete="off" type="text" name="gameName" class="form-control" id="gameName" value="<?= set_value('gameName', $gameName) ?>" required>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                    <label for="buyinValue" class="form-label">Valor do buy in</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text" id="IGPBuyinValue"><i data-bs-toggle="tooltip" title="Valor em dólar americano" class="bi bi-currency-dollar"></i></span>
                        <input autocomplete="off" type="text" inputmode="numeric" name="buyinValue" class="form-control money-dolar" id="buyinValue" value="<?= set_value('buyinValue', $buyinValue) ?>" required>
                    </div>
                </div>


                <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                    <label for="reentryBuyIn" class="form-label">Reentrada <i data-bs-toggle="tooltip" title="Inclusive rebuy, addon e outros extras" class='bi bi-info-circle-fill'></i></label>
                    <div class="input-group has-validation">
                        <span class="input-group-text" id="IGPReentryBuyIn"><i data-bs-toggle="tooltip" title="Valor em dólar americano" class="bi bi-currency-dollar"></i></span>
                        <input autocomplete="off" type="text" inputmode="numeric" name="reentryBuyIn" class="form-control money-dolar" id="reentryBuyIn" value="<?= set_value('reentryBuyIn', $reentryBuyIn) ?>">
                    </div>
                </div>

                <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                    <label for="prizeIn" class="form-label">
                        <span class='text-success'>Prêmiação</span> na 1ª entrada <i data-bs-toggle="tooltip" title="Valor da premiação na 1ª entrada" class='bi bi-info-circle-fill'></i></label>
                    <div class="input-group has-validation">
                        <span class="input-group-text" id="IGPPrizeIn"><i data-bs-toggle="tooltip" title="Valor em dólar americano" class="bi bi-currency-dollar"></i></span>
                        <input autocomplete="off" type="text" inputmode="numeric" name="prizeIn" class="form-control money-dolar" id="prizeIn" value="<?= set_value('prizeIn', $prizeIn) ?>">
                    </div>
                </div>

                <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                    <label for="prizeReentry" class="form-label" >
                        <span class='text-success'>Premiação</span> na reentrada <i data-bs-toggle="tooltip" title="Valor da premiação na reentrada não entra na conta dos patrocinadores" class='bi bi-info-circle-fill'></i></label>
                    <div class="input-group has-validation">
                        <span class="input-group-text" id="IGPPrizeReentry"><i data-bs-toggle="tooltip" title="Valor em dólar americano" class="bi bi-currency-dollar"></i></span>
                        <input autocomplete="off" type="text" inputmode="numeric" name="prizeReentry" class="form-control money-dolar" id="prizeReentry" value="<?= set_value('prizeReentry', $prizeReentry) ?>">
                    </div>
                </div>
                <hr class='divider' />
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input autocomplete="off" class="form-check-input" type="checkbox" name="stakingSellingCheck" id="stakingSellingCheck" value="1" <?= set_value('stakingSellingCheck', $stakingSelling) ? 'checked="true"' : '' ?>>
                        <label class="form-check-label" for="stakingSellingCheck">
                            Estou vendendo cotas
                        </label>
                    </div>
                </div>
                <div class="row campos-cota">
                    <div class="col-6 col-sm-6 col-md-4">
                        <label for="stakingSelling" class="form-label">% à venda</label>
                        <div class="input-group has-validation">
                            <input autocomplete="off" type="text" inputmode="numeric" name="stakingSelling" class="form-control percent-dot text-end" id="stakingSelling" value="<?= set_value('stakingSelling', $stakingSelling) ?>">
                            <span class="input-group-text" id="IGPStakingSelling"><i data-bs-toggle="tooltip" title="Valor percentual" class="bi bi-percent"></i></span>
                        </div>
                    </div>


                    <div class="col-6 col-sm-6 col-md-4">
                        <label for="markup" class="form-label">Markup</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="IGPMarkup"><i data-bs-toggle="tooltip" title="Cafézinho" class="bx bx-coffee"></i></span>
                            <input autocomplete="off" type="text" inputmode="numeric" pattern="[0-9]+([\.][0-9]{0,2})?" name="markup" class="form-control markup" id="markup" value="<?= set_value('markup', $markup) ?>">
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-4">
                        <label for="stakingSold" class="form-label">% vendido</label>
                        <div class="input-group has-validation">
                            <input autocomplete="off" type="text" inputmode="numeric" name="stakingSold" class="form-control percent-dot text-end" id="stakingSold" value="<?= set_value('stakingSold', $stakingSold) ?>">
                            <span class="input-group-text" id="IGPStakingSold"><i data-bs-toggle="tooltip" title="Valor percentual" class="bi bi-percent"></i></span>
                        </div>
                    </div>

                </div>
                <hr class='divider' />
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input autocomplete="off" class="form-check-input" type="checkbox" name="isClosed" id="isClosed" <?= set_value('isClosed', $isClosed) ? 'checked="true"' : '' ?>>
                        <label class="form-check-label" for="isClosed">
                            Este evento já acabou
                        </label>
                    </div>
                </div>
                <div class='row campos-finaliza <?= !set_value('isClosed', $isClosed) ? 'd-none' : ''  ?>'>
                    <div class="col-6 col-sm-6 col-md-3">
                        <label for="endDate" class="form-label">Data Fim</label>
                        <input autocomplete="off" type="date" name="endDate" class="form-control" id="endDate" value="<?= set_value('endDate', $endDate) ?>" <?= (!set_value('isClosed', $isClosed)) ? 'disabled="true"' : ''  ?>>
                    </div>

                    <div class="col-6 col-sm-6 col-md-3">
                        <label for="endTime" class="form-label">Hora Fim</label>
                        <input autocomplete="off" type="time" name="endTime" class="form-control" id="endTime" value="<?= set_value('endTime', $endTime) ?>" <?= (!set_value('isClosed', $isClosed)) ? 'disabled="true"' : ''  ?>>
                    </div>

                    <div class="col-6 col-sm-6 col-md-3">
                        <label for="fieldSize" class="form-label">Tamanho do field</label>
                        <div class="input-group mb-3">
                            <input autocomplete="off" type="text" inputmode="numeric" name="fieldSize" class="form-control" id="fieldSize" value="<?= set_value('fieldSize', $fieldSize) ?>" <?= (!set_value('isClosed', $isClosed)) ? 'disabled="true"' : ''  ?>>
                            <span class="input-group-text" id="basicAddonFieldSize"><i title="Players" data-bs-toggle="tooltip" class="text-primary bi bi-people"></i></span>
                        </div>
                    </div>

                    <div class="col-6 col-sm-6 col-md-3">
                        <label for="position" class="form-label">Posição</label>
                        <div class="input-group mb-3">
                            <input autocomplete="off" type="text" inputmode="numeric" name="position" class="form-control" id="position" value="<?= set_value('position', $position) ?>" <?= (!set_value('isClosed', $isClosed)) ? 'disabled="true"' : ''  ?>>
                            <span class="input-group-text" id="basicAddonPosition"><i title="Posição final" data-bs-toggle="tooltip" class="text-primary bi bi-people"></i></span>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input autocomplete="off" class="form-check-input" type="checkbox" value="1" name="finalTable" id="isClosed" <?= set_value('finalTable', $finalTable) ? 'checked="true"' : '' ?>>
                            <label class="form-check-label" for="finalTable">
                                Alcancei a Mesa Final
                            </label>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <button type="reset" class="btn btn-secondary">Limpar</button>
                </div>
            </form><!-- End Multi Columns Form -->

        </div>
</div>