<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?= $this->include('base/header'); ?>
</head>

<body>
    <main> 
            <?= $this->include('base/topContentMain'); ?>
            <?= $this->renderSection('content') ?>
    </main>
    <?= $this->include('base/scriptsFooter'); ?>
</body>
</html>