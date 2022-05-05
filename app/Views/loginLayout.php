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
    <?= $this->include('base/sidebarLeft'); ?>
    <!-- End Sidebar-->
    <main>
            <?= $this->include('base/topContentMain'); ?>
            <?= $this->renderSection('content') ?>
    </main>
    <?= $this->include('base/scriptsFooter'); ?>
</body>

</html>