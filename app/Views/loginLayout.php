<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?= $this->include('base/header'); ?>
</head>

<body>
    <!-- ======= Header ======= -->
    <?= $this->include('base/contentHeader'); ?>
    <!-- End Header -->
    <!-- ======= Sidebar ======= -->
    <?= $this->include('base/sidebarleft'); ?>
    <!-- End Sidebar-->
    <main id="main" class="main">
        <div class="container">
            <?= $this->include('base/topContentMain'); ?>
            <?= $this->renderSection('content') ?>
        </div>
    </main>
    <?= $this->include('base/scriptsFooter'); ?>
</body>

</html>