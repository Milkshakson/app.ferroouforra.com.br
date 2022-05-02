<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?= $this->include('header'); ?>
</head>

<body>
    <!-- ======= Header ======= -->
    <?= $this->include('contentHeader'); ?>
    <!-- End Header -->
    <!-- ======= Sidebar ======= -->
    <?= $this->include('sidebarleft'); ?>
    <!-- End Sidebar-->
    <main id="main" class="main">
    <?= $this->include('topContentMain'); ?>
        <?= $this->renderSection('content') ?>
        </div>
        <?= $this->include('footer'); ?>
</body>

</html>