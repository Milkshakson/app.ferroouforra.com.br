<?php if (!empty($breadcrumbTitle)) { ?>
    <div class="pagetitle">
        <h1><?= $breadcrumbTitle->textTop ?></h1>
        <?php if (count($breadcrumbTitle->items) > 0) { ?>
            <nav>
                <ol class="breadcrumb">
                    <?php foreach ($breadcrumbTitle->items as $item) { ?>
                        <li class="breadcrumb-item"><?php if (is_array($item)) { ?><a href="<?= $item ?>[1]"><?= $item[0] ?></a><?php } else { ?><?= $item ?> <?php } ?></li>
                    <?php } ?>
                </ol>
            </nav>
        <?php } ?>
    </div><!-- End Page Title -->
<?php } ?>
<?php if (!empty($erros)) { ?>
    <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        <?php pre($erros)?>
    </div>
<?php } ?>
<?php if (!empty($erro)) { ?>
    <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        <?php pre($erro)?>
    </div>
<?php } ?>
<?php if (!empty($sucessos)) { ?>
    <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        <?= $sucessos ?>
    </div>
<?php } ?>

<?php if (!empty($avisos)) { ?>
    <div class="alert alert-warning bg-warning text-light border-0 alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        <?= $avisos ?>
    </div>
<?php } ?>

<?php if (!empty($validationErros)) { ?>
    <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
        <?= $validationErros ?>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php } ?>