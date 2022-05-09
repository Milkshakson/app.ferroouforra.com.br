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
    $classLucro = $bi['profit'] > 0 ? 'text-success' : (($bi['profit'] < 0) ? 'text-danger' : '');
    $startDate = new Time($bi['startDate']);
    $endDate = new Time($bi['endDate']);
  ?>
    <div class="card mb-1 py-2 px-1">
      <div class="row g-0">
        <div class="col-lg-1 col-sm-2 col-xs-3 px-1 py-0 d-flex align-content-center align-items-center">
          <!--https://seeklogo.com/images/P/pokerstars-logo-4D58E5168B-seeklogo.com.png-->
          <img src="https://seeklogo.com/images/P/pokerstars-logo-4D58E5168B-seeklogo.com.png" style="max-width:50px;" class="img-fluid rounded-circle" alt="<?= $bi['pokerSiteShortName'] ?>">
        </div>
        <div class="col-lg-11 col-sm-10 col-xs-7 pt-0">
          <div class="card-body  py-0">
            <h5 class="card-title py-0">
              <h4 class='card-title'><span><?= $bi['gameName'] ?> <?= $bi['pokerSiteName'] ?></span></h4>
            </h5>
            <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>Ações</h6>
                  </li>
                  <li><a class="dropdown-item text-danger btn-remove-buyin" href="/session/removeBuyIn/<?=$bi['buyinId']?>"><i class='bi bi-trash'></i>Remover</a></li>
                  <li><a class="dropdown-item text-primary" href="#"><i class='bi bi-pencil'></i>Editar</a></li>
                </ul>
              </div>
            <div class='row text-muted small '>
              <?= boxBi('Buy-in', dolarFormat($bi['buyinValue']), 'class="col-3 col-md-3 "') ?>
              <?= boxBi('Prize', dolarFormat($bi['totalPrize']), 'class="col-lg-3 col-md-3 "') ?>
              <?= boxBi('Lucro', dolarFormat($bi['profit']), "class='col-lg-3 col-md-3 $classLucro'") ?>
              <?= boxBi('Início', $startDate->humanize(), "title='" . $startDate->format('d/m/Y H:i') . "' class='col-lg-3 col-md-3'") ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>