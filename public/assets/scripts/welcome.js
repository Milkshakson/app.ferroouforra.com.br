
function cronometro(exp) {
    setTimeout(function () {
        moment.locale('pt-br');
        let now = moment();
        let expira = moment(exp, 'DD/MM/YYYY HH:mm:ss');
        let restam = expira.diff(now, 'minutes');
        const formatedReturn = moment().startOf('day').minutes(restam).format('HH:mm') + 'h';
        let msg = '';
        let classText = '';
        let classBg = '';
        if (restam <= 0) {
            restam = 0;
            classText = 'text-danger';
            msg = '<div class=\"text-danger\">Sua sessão expirou em ' + expira.format('DD/MM/YYYY HH:mm:ss') + '</div>';
        } else if (restam > 10) {
            classText = 'text-success';
            msg = '<div class=\"text-success\">Sua sessão expira em ' + formatedReturn + '</div>';
        } else if (restam > 1) {
            classText = 'text-info';
            msg = '<div class=\"text-info\">Sua sessão expira em ' + formatedReturn + '</div>';
        } else {
            classText = 'text-warning';
            msg = '<div class=\"text-warning\">Sua sessão expira em ' + formatedReturn + '</div>';
        }
        $('.bi-time-left-token').removeClass('text-danger, text-info, text-success, text-warning').addClass(classText);
        $('.badge-time-left-token').text(formatedReturn).removeClass('text-danger, text-info, text-success, text-warning').addClass(classText);
        $('.badge-time-left-token-expanded').html(msg);

        cronometro();
    }, 1000);
}

$(document).ready(function () {
    cronometro(usuarioToken.expDate);
});