$(function () {
    $(".back-to-top").click(function (e) {
        e.preventDefault();
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return false;
    });

    $('.date').mask('00/00/0000');
    $('.time').mask('00:00:00');
    $('.date_time').mask('00/00/0000 00:00:00');
    $('.cep').mask('00000-000');
    $('.phone').mask('0000-0000');
    $('.phone_with_ddd').mask('(00) 0000-0000');
    $('.phone_us').mask('(000) 000-0000');
    $('.mixed').mask('AAA 000-S0S');
    $('.cpf').mask('000.000.000-00', {
        reverse: true
    });
    $('.cnpj').mask('00.000.000/0000-00', {
        reverse: true
    });
    $('.money').mask('000.000.000.000.000,00', {
        reverse: true
    });
    $('.money-dolar').mask('000000000000000.00', {
        reverse: true
    });
    $('.markup').mask('?.??', {
        reverse: true,
        translation: {
            '?': {
                pattern: /[0-9]+([\.][0-9]{0,2})?/,
                optional: true
            }
        }
    });
    $('.money2').mask("#.##0,00", {
        reverse: true
    });
    $('.ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
        translation: {
            'Z': {
                pattern: /[0-9]/,
                optional: true
            }
        }
    });
    $('.ip_address').mask('099.099.099.099');
    $('.percent').mask('##0,00%', {
        reverse: true
    });
    $('.percent-dot').mask('##0.00', {
        reverse: true
    });
    $('.clear-if-not-match').mask("00/00/0000", {
        clearIfNotMatch: true
    });
    $('.placeholder').mask("00/00/0000", {
        placeholder: "__/__/____"
    });
    $('.fallback').mask("00r00r0000", {
        translation: {
            'r': {
                pattern: /[\/]/,
                fallback: '/'
            },
            placeholder: "__/__/____"
        }
    });
    $('.selectonfocus').mask("00/00/0000", {
        selectOnFocus: true
    });

    $('.alert-dismissible').each(function (e) {
        $(this).fadeOut(6000);
    });
    $(document).on('keyup', '.markup,.money-dolar,.money,.percent,.percent-dot', function (k) {
        let sender = $(this);
        if (sender.val().startsWith(".", 0)) {
            let val = sender.val();
            sender.val('0' + val);
        }
    });

    $(document).on('click', '.img-select-site', function () {
        $("[name='pokerSiteId']").val($(this).data('value'))
        $('.site-selected').removeClass('site-selected')
        $(this).addClass('site-selected')
        $('[name="gameName"]').focus()
    })

    $(document).on('focusout', '[name="gameName"]', function (e) {
        $('.container-grid-poker-sitesJogados').show();
        setTimeout(() => $('#retorno-lista-jogos').html(''), 1000);
    });

    $(document).on('focus', '[name="gameName"]', function (e) {
        $('.container-grid-poker-sitesJogados').hide();
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
                    let meusBuyIns = $('<div class="meus-buyins col-lg-12 col-md-12"><div class="d-flex align-items-center justify-content-center">..buscando seus torneios favoritos da forra..</div></div>');
                    $('#retorno-lista-jogos').html(meusBuyIns);
                },
                success: function (retorno) {
                    let meusBuyIns = $('<div class="meus-buyins col-lg-12 col-md-12">' + retorno + '</div>');
                    $('#retorno-lista-jogos').html(meusBuyIns);
                }
            }).fail(function () {
                html = 'Erro';
                let meusBuyIns = $('<div class="meus-buyins col-lg-12 col-md-12"><div class="">' + html + '</div></div>');
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

    $(document).on('focus', ".markup,.percent-dot,.money-dolar", function () {
        const val = $(this).val()
        if (val == 0) {
            $(this).val('')
        } else {
            $(this).select();
        }
    })
});