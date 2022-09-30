$(document).ready(() => {
    $(document).on('click', '#btn-importar', function () {
        var buyInList = []
        const sessionId = $(this).data('session-id')
        $(".buyin-to-import").each(function (bi) {
            const id = $(this).data('codigo-bi')
            if (id)
                buyInList.push(id)
        }
        )
        if (buyInList.length > 0) {
            $.ajax({
                url: '/session/createBuyInBatch',
                method: "POST",
                data: { sessionId, buyInList },
                success: (retorno) => alert(retorno),
                beforeSend: () => waitingDialog.show('Aguarde')
            })
                .always(() => waitingDialog.hide())
                .fail(() => errorAlert('Falha na requisição.'))
        } else {
            warningAlert('A lista para importação está vazia.')
        }
    });
});
function removeBuyIn(buyinId) {
    $('#card_' + buyinId).remove();
    const countBuiIns = $(".buyin-to-import").length;
    $("#count-registros").text(countBuiIns);
    if (countBuiIns > 0) {
        $('#btn-importar').show();
    } else {
        $('#btn-importar').hide();
    }
}