  <?php

  use CodeIgniter\I18n\Time;

  if (!function_exists('boxBi')) {
    function boxBi($label, $value, $properties = '')
    {
      return "<div $properties>" . //
        "<div class='row bg-light  py-0'>$label</div>" . //
        "<div class='row py-0'>$value</div>" . //
        "</div>";
    }
  }
  ?>
  <?php foreach ($buyInList as $bi) {
    $classLucro =$bi['isClosed']?  ($bi['profit'] > 0 ? 'text-success' : (($bi['profit'] < 0) ? 'text-danger' : '')):'';
    $startDate = new Time($bi['startDate']);
    $endDate = new Time($bi['endDate']);
    if($bi['isClosed']){
      $cardTime = boxBi('Encerrado', $endDate->humanize(), "title='" . $startDate->format('d/m/Y H:i') . "' class='col-lg-3 col-md-6 col-xs-6 col-6'");
    }else{
      $cardTime = boxBi('Início', $startDate->humanize(), "title='" . $startDate->format('d/m/Y H:i') . "' class='col-lg-3 col-md-6 col-xs-6 col-6'");
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
            <h4 class='card-title  <?=$classLucro?>'><span><?= $bi['gameName'] ?> <?= $bi['pokerSiteName'] ?></span></h4>
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
              <?= boxBi('Total buy in', dolarFormat($bi['totalBuyIn']), 'class="col-lg-3 col-md-6 col-sm-6  col-xs-6 col-6"') ?>
              <?= boxBi('Total prize', dolarFormat($bi['totalPrize']), 'class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-6"') ?>
              <?= boxBi('Lucro', dolarFormat($bi['profit']), "class='col-lg-3 col-md-3 col-xs-6 col-6 $classLucro'") ?>
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
                        <?= boxBi('Cota oferecida', percent($bi['stakingSelling']), 'class="col-md-6 col-xs-6 col-6"') ?>
                        <?= boxBi('Markup', ($bi['markup']), 'class="col-md-6 col-xs-6 col-6"') ?>
                        <?= boxBi('Cota vendida', percent($bi['stakingSold']), 'class="col-md-3 col-xs-6 col-6 "') ?>
                      </div>
                    <?php } ?>
                    <div class='row'>
                      <?= boxBi('Valor reentrada', dolarFormat($bi['reentryBuyIn']), 'class="col-md-3 col-xs-6 col-6 "') ?>
                      <?= boxBi('Prêmio reentrada', dolarFormat($bi['prizeReentry']), 'class="col-md-3 col-xs-6 col-6 "') ?>
                      <?= boxBi('Tamanho do field', ($bi['fieldSize']), 'class="col-md-3 col-xs-6 col-6 "') ?>
                      <?= boxBi('Posição final', ($bi['position'].'º'), 'class="col-md-3 col-xs-6 col-6 "') ?>
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