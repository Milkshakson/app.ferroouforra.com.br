<div class="card" style="width: 18rem;">
  <ul class="list-group list-group-flush">
  <?php foreach ($buyInList as $bi) { ?>
    <li class="list-group-item"><h5 class='card-title'><span><?= $bi['gameName'] ?>|<?= $bi['pokerSiteName'] ?></span></h5></li>
   <?php }?>
  </ul>
</div>