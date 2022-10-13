$(document).ready(function () {

    $(document).on('change', '[name="isClosed"]', function (e) {
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

    $(document).on('focusout', '[name="gameName"]', function (e) {
        setTimeout(() => $('#retorno-lista-jogos').html(''), 500);
    });
    $(document).on('keyup', '[name="gameName"]', function (e) {
        let sender = $(this);
        let busca = sender.val();
        var html = '';
        $('.meus-buyins').remove();
        if (busca.length >= 3) {
            $.ajax({
                data: {
                    'busca': busca,
                    'site': $('#pokerSiteId').val()
                },
                method: 'post',
                url: '/session/meusBuyIns',
                beforeSend: () => {
                    let meusBuyIns = $('<div class="card p-3 meus-buyins col-lg-12 col-md-12"><div class="card-body d-flex align-items-center justify-content-center">..buscando seus torneios favoritos da forra..</div></div>');
                    $('#retorno-lista-jogos').html(meusBuyIns);
                },
                success: function (retorno) {
                    html = retorno;
                    if (html) {
                        let meusBuyIns = $('<div class="card p-3 meus-buyins col-lg-12 col-md-12"><div class="card-body">' + html + '</div></div>');
                        $('#retorno-lista-jogos').html(meusBuyIns);
                    } else {
                        $('#retorno-lista-jogos').html('');
                    }
                }
            }).fail(function () {
                html = 'Erro';
                let meusBuyIns = $('<div class="card p-3 meus-buyins col-lg-12 col-md-12"><div class="card-body">' + html + '</div></div>');
                sender.after(meusBuyIns);
            }).always(function () { });
        }
    });
    $(document).on('click', '.seleciona-buy-in', function (e) {
        e.preventDefault();
        let sender = $(this);
        $('[name="gameName"]').val(sender.data('gameName'));
        $('[name="pokerSiteId"]').val(sender.data('site'));
        $('[name="tipoBuyIn"]').val(sender.data('tipoBuyIn'));
        $('[name="buyinValue"]').val(sender.data('buyIn'));
        $('.meus-buyins').remove();
    });
    $(document).on('change', '[name="stakingSellingCheck"]', function (e) {
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