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

    // Adiciona um evento de digitação a todos os elementos com a classe "money-input"
    $(document).on('input', '.money-dolar', function () {
        $(this).mask('000000000000000.00', {
            reverse: true
        });
    });


    // $(document).on('keyup', '.markup,.money-dolar,.money,.percent,.percent-dot', function (k) {
    //     let sender = $(this);
    //     if (sender.val().startsWith(".", 0)) {
    //         let val = sender.val();
    //         sender.val('0' + val);
    //     }
    // });



    //Função para formatar o valor como dinheiro
    function formatMoney(value) {
        // Remove todos os caracteres, exceto números e pontos
        value = value.replace(/,/g, '.');
        value = value.replace(/[^\d.]/g, '');

        // Substitui a vírgula pelo ponto (caso o usuário use vírgula como separador decimal)

        // Formata o valor com duas casas decimais
        var parts = value.split('.');
        if (parts.length > 1) {
            value = parts[0] + '.' + parts[1].substring(0, 2);
        }

        return value;
    }



    // function formatMoney(value) {
    //     // Remove todos os caracteres, exceto números e pontos
    //     value = value.replace(/,/g, '.');
    //     value = value.replace(/[^\d.]/g, '');

    //     // Substitui a vírgula pelo ponto (caso o usuário use vírgula como separador decimal)

    //     // Formata o valor com duas casas decimais
    //     var parts = value.split('.');
    //     if (parts.length > 1) {
    //         //se existe o ponto
    //         // value = parts[0] + '.' + parts[1].substring(0, 2);
    //         value = parts[0] + '.' + parts[1].replace('.', '');
    //         if (parts[1].length > 2) {
    //             value = value.replace('.', '');
    //             var leftValue = value.slice(0, -2);
    //             var rightValue = value.slice(-2);
    //             value = leftValue + '.' + rightValue;
    //         }
    //     }

    //     return value;
    // }




    // // Adiciona um evento de digitação a todos os elementos com a classe "money-input"
    // $(document).on('input', '.money-dolar', function () {
    //     $(this).val(formatMoney($(this).val()));
    // });


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
        $(this).fadeOut(15000);
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

    // Função para ativar as abas armazenadas no localStorage
    function activateStoredTabs() {
        var activeTabIds = JSON.parse(localStorage.getItem('activeTabIds')) || {};
        // Iterar sobre as chaves (id da ul) da lista de activeTabIds
        Object.keys(activeTabIds).forEach(function (ulId) {
            var activeTabId = activeTabIds[ulId];

            // Verificar se a aba existe na página e ativá-la
            if ($('ul#' + ulId + ' a[data-bs-toggle="tab"][href="#' + activeTabId + '"]').length) {
                $('ul#' + ulId + ' a[data-bs-toggle="tab"][href="#' + activeTabId + '"]').tab('show');
            }
        });
    }

    // Função para armazenar a aba ativa no localStorage ao clicar ou selecionar
    $(document).on('shown.bs.tab', 'a[data-bs-toggle="tab"]', function (e) {
        var ulId = $(this).closest('ul').attr('id');
        var activeTabId = $(e.target).attr('href').substr(1);

        var activeTabIds = JSON.parse(localStorage.getItem('activeTabIds')) || {};
        activeTabIds[ulId] = activeTabId;

        localStorage.setItem('activeTabIds', JSON.stringify(activeTabIds));
    });
    $(document).on('click', '.custom-pointer.site-selector', function (e) {
        const sender = $(this);
        const checkbox = sender.find('input:checkbox');
        checkbox.prop('checked', !checkbox.prop('checked')); // Inverte o estado do checkbox
        checkbox.trigger('change'); // Dispara o evento de mudança manualmente
    });

    $(document).on('change', '.custom-pointer.site-selector input:checkbox', function (e) {
        const sender = $(this);
        const cardId = sender.attr('id').replace('site-', '');
        const card = $('.custom-pointer.site-selector[data-id="' + cardId + '"]');

        if (sender.prop('checked')) {
            card.addClass('border-success');
        } else {
            card.removeClass('border-success');
        }
    });

    $(document).on('submit', '#form-sites-selecionados', function (e) {
        e.preventDefault();
        const form = $(this);
        const data = form.serialize();
        $.ajax({
            method: 'post',
            url: '/site/salvaSitesPessoa',
            data,
            dataType: 'json',
            beforeSend: () => waitingDialog.show('Aguarde'),
            success: function (response) {
                if (response.success) {
                    successAlert(response.message, () => location.reload());
                } else {
                    errorAlert(response.message);
                }
            }
        })
            .fail(() => errorAlert('Falha na requisição.'))
            .always(() => waitingDialog.hide());
    });

    // Ativar as abas armazenadas no localStorage ao carregar a página
    activateStoredTabs();
    const targetSiteSelection = $('.container-site-selection');
    if (targetSiteSelection.length)
        loadSiteSelection(targetSiteSelection);

});

function loadSiteSelection(target) {
    $.ajax({
        url: '/site/lazyLoadSiteSelection',
        dataType: 'json',
        success: (response) => {
            if (response.success) {
                target.html(response.html);
            } else {
                errorAlert(response.message);
            }
        }
    })
        .fail(() => target.html('Não foi possível recuperar a lista de sites.'))
}