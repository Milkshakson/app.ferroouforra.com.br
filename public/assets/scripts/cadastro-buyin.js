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