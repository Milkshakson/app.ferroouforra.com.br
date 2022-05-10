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
        <?= view('session/buyins/form-cadastro.php', ['variaveis' => []]); ?>
    </div>
</section>
<?= $this->endSection() ?>
<?= $this->section('customJavascriptFooter'); ?>
<script>
    $(document).ready(function() {

        $(document).on('change', '[name="isClosed"]', function(e) {
            let sender = $(this);
            let camposFinaliza = $('.campos-finaliza');
            if (sender.is(':checked')) {
                camposFinaliza.removeClass('d-none');
                camposFinaliza.find('input').prop("disabled", false);
            } else {
                camposFinaliza.addClass('d-none');
                camposFinaliza.find('input').prop("disabled", true);
            }
        });

        $(document).on('change', '[name="stakingSellingCheck"]', function(e) {
            let sender = $(this);
            let camposCota = $('.campos-cota');
            if (sender.is(':checked')) {
                camposCota.removeClass('d-none').show();
                camposCota.find('input').prop("disabled", false);
            } else {
                camposCota.addClass('d-none').hide();
                camposCota.find('input').prop("disabled", true);
            }
        });

        $(document).on('change','.money-dolar',  function() {

            var $replace = $(this).val().toString().replace(/,/g, '.');

            $(this).val($replace);

        });
        $('[name="isClosed"]').trigger('change');
        $('#stakingSellingCheck').trigger('change');
    });
</script>
<?= $this->endSection(); ?>