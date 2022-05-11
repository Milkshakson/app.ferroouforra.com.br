<?= $this->extend('baseLayout') ?>
<?= $this->section('content') ?>
<div class="pagetitle">
    <?php
    $buyInList = [];
    $openedSession = session('openedSession');
    if ($openedSession) {
        $buyInList = $openedSession['buyInList'];
    }
    ?>
    <h1>Registro de buy in</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Cadastro</a></li>
        </ol>
    </nav>
</div><!-- End Page Title -->
<section class="section dashboard">
    <div class="row">
        <div class='col-lg-6'>
            <?= view('session/buyins/form-cadastro.php', ['variaveis' => []]); ?>
        </div>
        <div class='col-lg-6'>
            <?= view('session/buyins/list-cards.php', ['buyInList' => $buyInList]); ?>
        </div>
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

        $(document).on('keyup', '[name="gameName"]', function(e) {
            let sender = $(this);
            let busca = sender.val();
            var html = '';
            $('.meus-buyins').remove();
            if (busca.length >= 3) {
                $.ajax({
                    data: {
                        'busca': busca
                    },
                    method: 'post',
                    url: '/session/meusBuyIns',
                    success: function(retorno) {
                        html = retorno;
                        let meusBuyIns = $('<div class="card p-3 meus-buyins col-lg-12 col-md-12"><div class="card-body">' + html + '</div></div>');
                        sender.after(meusBuyIns);
                    }
                }).fail(function() {
                    html = 'Erro';
                    let meusBuyIns = $('<div class="card p-3 meus-buyins col-lg-12 col-md-12"><div class="card-body">' + html + '</div></div>');
                    sender.after(meusBuyIns);
                }).always(function() {});
            }
        });
        $(document).on('click', '.seleciona-buy-in', function(e) {
            e.preventDefault();
            let sender = $(this);
            $('[name="gameName"]').val(sender.data('gameName'));
            $('[name="pokerSiteId"]').val(sender.data('site'));
            $('[name="tipoBuyIn"]').val(sender.data('tipoBuyIn'));
            $('[name="buyinValue"]').val(sender.data('buyIn'));
            $('.meus-buyins').remove();
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
        $('[name="isClosed"]').trigger('change');
        $('#stakingSellingCheck').trigger('change');
    });
</script>
<?= $this->endSection(); ?>