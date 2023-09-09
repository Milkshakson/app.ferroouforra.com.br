
function loadBankrollAbertura(target) {
    $.ajax({
        url: '/currentSession/lazyLoadBankroll',
        dataType: 'json',
        success: (json) => {
            target.html(json.html);

            // Calcular totais iniciais
            calcularTotais();
        }
    })
        .fail(() => target.html('Não foi possível recuperar o bankroll.'))
}
function loadBankrollEncerramento(target) {
    $.ajax({
        url: '/currentSession/lazyLoadBankrollEncerramento',
        dataType: 'json',
        success: (json) => {
            target.html(json.html);

            // Calcular totais iniciais
            calcularTotais();
        }
    })
        .fail(() => target.html('Não foi possível recuperar o bankroll.'))
}

function calcularTotais() {
    var totalSaldoInicial = 0;
    var totalSaldoFinal = 0;

    $('.saldo-inicial').each(function () {
        var valor = parseFloat($(this).val().replace('$', '').replace(',', ''));
        if (!isNaN(valor)) {
            totalSaldoInicial += valor;
        }
    });

    $('.saldo-final').each(function () {
        var valor = parseFloat($(this).val().replace('$', '').replace(',', ''));
        if (!isNaN(valor)) {
            totalSaldoFinal += valor;
        }
    });

    $('#total-saldo-inicial').text('$' + totalSaldoInicial.toFixed(2));
    $('#total-saldo-final').text('$' + totalSaldoFinal.toFixed(2));

    // Verificar se há Lucro ou Prejuízo
    var lucroPrejuizo = totalSaldoFinal - totalSaldoInicial;
    var lucroPrejuizoElement = $('#lucro-prejuizo');
    var valorLucroPrejuizoElement = $('#valor-lucro-prejuizo');

    if (lucroPrejuizo > 0) {
        lucroPrejuizoElement.text('Lucro').removeClass('text-danger').addClass('text-success');
        valorLucroPrejuizoElement.text('$' + lucroPrejuizo.toFixed(2)).removeClass('text-danger').addClass('text-success');
    } else if (lucroPrejuizo < 0) {
        lucroPrejuizoElement.text('Prejuízo').removeClass('text-success').addClass('text-danger');
        valorLucroPrejuizoElement.text('$' + Math.abs(lucroPrejuizo).toFixed(2)).removeClass('text-success').addClass('text-danger');
    } else {
        lucroPrejuizoElement.text('Nenhum').removeClass('text-success text-danger');
        valorLucroPrejuizoElement.text('$0.00').removeClass('text-success text-danger');
    }
}




function importLastClosedBankroll() {
    $.ajax({
        url: '/currentSession/lastClosedBankroll',
        dataType: 'json',
        beforeSend: () => waitingDialog.show('Aguarde'),
        success: (response) => {
            if (response.success) {
                response.bankroll.forEach((site) => {
                    // Selecione os elementos com a classe saldo-inicial e o data-site correspondente
                    var $saldoInicialElement = $(`.saldo-inicial[data-site="${site.poker_site_id}"]`);

                    // Defina o valor de site.saldo_abertura nesses elementos
                    $saldoInicialElement.val(site.saldo_encerramento);
                });
            } else {

            }
        }
    }).always(() => waitingDialog.hide());
}


function salvaBankroll() {
    var dadosBankroll = [];
    $('.saldo-inicial').each(function (e) {
        var index = $('.saldo-inicial').index(this);
        // Verifique se os valores são válidos antes de salvar
        var siteId = $(this).data('site');
        var saldoInicial = $(this).val();
        var saldoFinal = $('.saldo-final').eq(index).val();
        dadosBankroll.push({
            poker_site_id: siteId,
            saldo_abertura: saldoInicial,
            saldo_encerramento: saldoFinal
        });
    });
    $.ajax({
        url: '/currentSession/updateBankrollSession',
        dataType: 'json',
        method: 'post',
        beforeSend: () => waitingDialog.show(),
        data: JSON.stringify(dadosBankroll),
        success: (response) => {
            if (response.success) {
                $('#table-icon .bi-exclamation-circle').hide();
                $('#table-icon .bi-check-circle').show();
            } else {
                $('#table-icon .bi-check-circle').hide();
                $('#table-icon .bi-exclamation-circle').show();
            }
        }
    }).always(() => waitingDialog.hide())
}
$(document).ready(function () {
    $(document).on('click', '.btn-import-last-bankroll', function (e) {
        importLastClosedBankroll();
    });
    $(document).on('keydown', '.table-bankrolls .money-dolar', function (e) {
        if (e.keyCode == 13) { // Verifica se a tecla pressionada é "Enter" (código 13)
            e.preventDefault();
            var $this = $(this);
            var $td = $this.closest('td');
            var $nextTd = $td.next('td');

            if ($nextTd.length > 0) {
                // Se existe um próximo campo na mesma linha, dê foco a ele
                $nextTd.find('.money-dolar').focus();
            } else {
                // Se for o último campo da linha, encontre a próxima linha e dê foco ao primeiro campo dela
                var $tr = $td.closest('tr');
                var $nextTr = $tr.next('tr');

                if ($nextTr.length > 0) {
                    $nextTr.find('.money-dolar').first().focus();
                }
            }
        }
    });

    $(document).on('keydown', '.table-bankroll .money-dolar', function (e) {
        if (e.keyCode == 13) { // Verifica se a tecla pressionada é "Enter" (código 13)
            e.preventDefault();
            var $this = $(this);
            var $td = $this.closest('td');
            var $nextTd = $td.next('td');

            if ($nextTd.length > 0) {
                // Se existe um próximo campo na mesma linha, dê foco a ele se estiver visível e habilitado
                var $nextInput = $nextTd.find('.money-dolar:visible:enabled');
                if ($nextInput.length > 0) {
                    $nextInput.focus().select();
                }
            } else {
                // Se for o último campo da linha, encontre a próxima linha e dê foco ao primeiro campo dela se estiver visível e habilitado
                var $tr = $td.closest('tr');
                var $nextTr = $tr.next('tr');

                if ($nextTr.length > 0) {
                    var $firstInput = $nextTr.find('.money-dolar:visible:enabled').first();
                    if ($firstInput.length > 0) {
                        $firstInput.focus().select();
                    }
                }
            }
        }
    });





    // Chame a função de cálculo quando um campo de entrada for alterado
    $(document).on('input', '.money-dolar', function () {
        $('#table-icon .bi-check-circle').hide();
        $('#table-icon .bi-exclamation-circle').show();
        calcularTotais();
    });

    $(document).on('click', '#salvar-btn', function () {
        salvaBankroll();
    });
    $(document).on('blur change', '.saldo-inicial', function () {
        var index = $(this).closest('tr').index(); // Obtém o índice da linha atual
        var saldoFinalInput = $('.saldo-final').eq(index); // Obtém o campo de saldo_encerramento correspondente

        // Verifica se o valor do saldo_encerramento está vazio
        if (saldoFinalInput.val() === '') {
            saldoFinalInput.val($(this).val()); // Define o valor do saldo_encerramento como o valor do saldo_abertura
        }

        calcularTotais(); // Recalcula os totais após a modificação
    });


    const targetBankrollAbertura = $('.container-bankroll-abertura');
    const targetBankrollEncerramento = $('.container-bankroll-encerramento');
    if (targetBankrollAbertura.length)
        loadBankrollAbertura(targetBankrollAbertura);
    if (targetBankrollEncerramento.length)
        loadBankrollEncerramento(targetBankrollEncerramento);
});