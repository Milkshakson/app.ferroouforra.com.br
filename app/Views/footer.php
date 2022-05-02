<footer id="footer" class="footer fixed-bottom">
    <div class="copyright">
        <?= config('app_name') ?>
        -
        <?= config('app_sigla') ?>
    </div>
    <div class="copyright text-light">
        &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>
    <div class="credits text-light">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
        Designed by <a class="text-light" href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
</footer>
<!-- End Footer -->
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<!-- Vendor JS Files -->
<script src="assets/templates/niceadmin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/templates/niceadmin/assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="assets/templates/niceadmin/assets/vendor/chart.js/chart.min.js"></script>
<script src="assets/templates/niceadmin/assets/vendor/echarts/echarts.min.js"></script>
<script src="assets/templates/niceadmin/assets/vendor/quill/quill.min.js"></script>
<script src="assets/templates/niceadmin/assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="assets/templates/niceadmin/assets/vendor/tinymce/tinymce.min.js"></script>
<script src="assets/templates/niceadmin/assets/vendor/php-email-form/validate.js"></script>
<script src="assets/templates/niceadmin/assets/js/main.js"></script>
<!-- Template Main JS File -->
<script src='assets/plugins/jquery/jquery-3.6.0.min.js'></script>
<script src='assets/plugins/moment/moment-with-locales.js'></script>
<script src='assets/plugins/jquery-mask/jquery.mask.js'></script>
<?=$this->renderSection('customJavascriptFooter');?>