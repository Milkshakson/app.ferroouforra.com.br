  <?php

  use CodeIgniter\I18n\Time;
  ?>
  <div class='row'>
    <div class="card">
      <div class="card-body">
        <h5 class='card-title'>Lista de jogos<span> | Esta sessão</span></h5>
        <div class="filter">

          <a class="dropdown-item text-primary " href="/session/salvaBuyIn"><i class='bi bi-plus-circle'></i>Adicionar</a>
        </div>
      </div>
    </div>
  </div>
  <?php foreach ($buyInList as $bi) {
    $classLucro = $bi['isClosed'] ?  ($bi['profit'] > 0 ? 'text-success' : (($bi['profit'] < 0) ? 'text-danger' : '')) : '';
    $startDate = new Time($bi['startDate']);
    $endDate = new Time($bi['endDate']);
    if ($bi['isClosed']) {
      $cardTime = box_bi('Encerrado', $endDate->humanize(), "title='" . $startDate->format('d/m/Y H:i') . "' class='col-lg-3 col-md-6 col-xs-6 col-6'");
    } else {
      $cardTime = box_bi('Início', $startDate->humanize(), "title='" . $startDate->format('d/m/Y H:i') . "' class='col-lg-3 col-md-6 col-xs-6 col-6'");
    }
  ?>
    <div class="card mb-1 py-2 px-1">
      <div class="row g-0">
        <div class='row'>
          <div class="col-lg-1 col-sm-3 col-xs-3 col-3 px-1 py-0 d-flex align-content-center align-items-center">
            <!--https://seeklogo.com/images/P/pokerstars-logo-4D58E5168B-seeklogo.com.png-->
            <img src="<?= (getSiteSrcImage($bi['pokerSiteName'])) ?>" style="max-width:50px;" class="img-fluid rounded-circle" alt="<?= $bi['pokerSiteShortName'] ?>">
          </div>

          <div class="card-title py-0 col-lg-11 col-sm-9 col-xs-9 col-9">
            <h5 class='card-title  <?= $classLucro ?>'><?= $bi['gameName'] ?><span> <?= $bi['pokerSiteName'] ?></span></h5>
          </div>
        </div>
        <div class="col-lg-12 col-sm-12 col-xs-12 col-12 pt-0">
          <div class="card-body bg-light py-0">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Ações</h6>
                </li>
                <li><a class="dropdown-item text-danger btn-remove-buyin" href="/session/removeBuyIn/<?= $bi['buyinId'] ?>"><i class='bi bi-trash'></i>Remover</a></li>
                <li><a class="dropdown-item text-primary" href="/session/salvaBuyIn/<?= $bi['buyinId'] ?>"><i class='bi bi-pencil'></i>Editar</a></li>
              </ul>
            </div>
            <div class='row text-muted small '>
              <?= box_bi('Total buy in', dolarFormat($bi['totalBuyIn']), 'class="col-lg-3 col-md-6 col-sm-6  col-xs-6 col-6"') ?>
              <?= box_bi('Total prize', dolarFormat($bi['totalPrize']), 'class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-6"') ?>
              <?= box_bi('Lucro', dolarFormat($bi['profit']), "class='col-lg-3 col-md-3 col-xs-6 col-6 $classLucro'") ?>
              <?= $cardTime ?>
            </div>
            <div class="accordion accordion-flush" id="accordion<?= $bi['buyinId'] ?>">
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-heading<?= $bi['buyinId'] ?>">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?= $bi['buyinId'] ?>" aria-expanded="false" aria-controls="flush-collapseOne">
                    Mais detalhes
                  </button>
                </h2>
                <div id="flush-collapse<?= $bi['buyinId'] ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?= $bi['buyinId'] ?>" data-bs-parent="#accordion<?= $bi['buyinId'] ?>">
                  <div class="accordion-body">
                    <hr class='divider' />
                    <?php if ($bi['stakingSold'] > 0) { ?>
                      <div class='row'>
                        <?= box_bi('Cota oferecida', percent($bi['stakingSelling']), 'class="col-md-4 col-lg-4 col-xs-4 col-4"') ?>
                        <?= box_bi('Markup', ($bi['markup']), 'class="col-md-4 col-lg-4 col-xs-4 col-4"') ?>
                        <?= box_bi('Cota vendida', percent($bi['stakingSold']), 'class="col-md-4 col-lg-4 col-xs-4 col-4 "') ?>
                        <?= box_bi('Patrocinadores pagaram', dolarFormat($bi['stakingReturn']), 'class="col-md-4 col-lg-4 col-xs-6 col-4"') ?>
                        <?= box_bi('Patrocinadores receberam', dolarFormat($bi['totalPrizeStakers']), 'class="col-md-4 col-lg-4 col-xs-4 col-4"') ?>
                      </div>
                    <?php } ?>
                    <div class='row'>
                      <?= box_bi('Valor reentrada', dolarFormat($bi['reentryBuyIn']), 'class="col-md-3 col-xs-6 col-6 "') ?>
                      <?= box_bi('Prêmio reentrada', dolarFormat($bi['prizeReentry']), 'class="col-md-3 col-xs-6 col-6 "') ?>
                      <?= box_bi('Tamanho do field', ($bi['fieldSize']), 'class="col-md-3 col-xs-6 col-6 "') ?>
                      <?= box_bi('Posição final', ($bi['position'] . 'º'), 'class="col-md-3 col-xs-6 col-6 "') ?>
                    </div>
                    <hr class='divider' />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>