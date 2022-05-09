<?= $this->extend('baseLayout') ?>
<?= $this->section('content') ?>
<div class="pagetitle">
    <h1>Registro de buy in</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Cadastro</a></li>
        </ol>
    </nav>
</div><!-- End Page Title -->
<section class="section dashboard">
    <div class="row">
        <?= view('session/buyins/form-cadastro.php',['variaveis'=>[]]); ?>
    </div>
</section>
<?= $this->endSection() ?>