<!-- Default Accordion -->
<div class="accordion" id="accordionBuyIns">

    <?php foreach ($buyInList as $bi) { ?>
        <!-- Accordion BuyIns -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading<?= $bi['gameId'] ?>">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $bi['gameId'] ?>" aria-expanded="true" aria-controls="collapse<?= $bi['gameId'] ?>">
                    <span><?= $bi['gameName'] ?>|<?= $bi['pokerSiteName'] ?></span>
                </button>
            </h2>
            <div id="collapse<?= $bi['gameId'] ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $bi['gameId'] ?>" data-bs-parent="#accordionBuyIns">
                <div class="accordion-body">
                    <div class='row'>
                        <?= $bi['gameName'] ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div><!-- End Accordion BuyIns -->