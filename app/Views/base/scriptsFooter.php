<?php
use App\Libraries\Html;
$html = new Html();
$html->addRaw([
    '<!-- End Footer -->',
    '<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>',
    '<!-- Vendor JS Files -->',
    '<script src="/assets/templates/NiceAdmin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>',
    '<script src="/assets/templates/NiceAdmin/assets/vendor/apexcharts/apexcharts.min.js"></script>',
    '<script src="/assets/templates/NiceAdmin/assets/vendor/chart.js/chart.min.js"></script>',
    '<script src="/assets/templates/NiceAdmin/assets/vendor/echarts/echarts.min.js"></script>',
    '<script src="/assets/templates/NiceAdmin/assets/vendor/quill/quill.min.js"></script>',
    '<script src="/assets/templates/NiceAdmin/assets/vendor/simple-datatables/simple-datatables.js"></script>',
    '<script src="/assets/templates/NiceAdmin/assets/vendor/tinymce/tinymce.min.js"></script>',
    '<script src="/assets/templates/NiceAdmin/assets/vendor/php-email-form/validate.js"></script>',
    '<script src="/assets/plugins/jquery/jquery-3.6.0.min.js"></script>',
    '<script src="/assets/scripts/global.js"></script>',
    '<script src="/assets/scripts/funcoes.js"></script>',
    '<script src="/assets/plugins/confirmbutton.js"></script>',
    '<script src="/assets/templates/NiceAdmin/assets/js/main.js"></script>',
    '<!-- Template Main JS File -->',
    '<script src="/assets/plugins/moment/moment-with-locales.js"></script>',
    '<script src="/assets/plugins/jquery-mask/jquery.mask.js"></script>',
]);
$html->display();
?>

<?php if (session('usuarioTokenAcesso')){ ?>
<script>
    function cronometro() {
        setTimeout(function() {
            moment.locale('pt-br');
            let exp = '<?=session('usuarioTokenAcesso')->expDate?>';
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
                msg = '<div class="text-danger">Sua sessão expirou em ' + expira.format('DD/MM/YYYY HH:mm:ss') + '</div>';
            } else if (restam > 10) {
                classText = 'text-success';
                msg = '<div class="text-success">Sua sessão expira em ' + formatedReturn + '</div>';
            } else if (restam > 1) {
                classText = 'text-info';
                msg = '<div class="text-info">Sua sessão expira em ' + formatedReturn + '</div>';
            } else {
                classText = 'text-warning';
                msg = '<div class="text-warning">Sua sessão expira em ' + formatedReturn + '</div>';
            }
            $('.bi-time-left-token').removeClass('text-danger, text-info, text-success, text-warning').addClass(classText);
            $('.badge-time-left-token').text(formatedReturn).removeClass('text-danger, text-info, text-success, text-warning').addClass(classText);
            $('.badge-time-left-token-expanded').html(msg);

            cronometro();
        }, 1000);
    }

    $(document).ready(function() {
        cronometro();
    });
</script>
<?php } ?>
<?php $this->renderSection('customJavascriptFooter');?>