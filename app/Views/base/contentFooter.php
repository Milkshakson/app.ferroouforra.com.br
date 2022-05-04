<?php

use App\Libraries\Html;

$html = new Html();
$html->addTag(
    'footer',
    [
        $html->getTag(
            'div',
            config('App')->appSigla . ' - ' . config('App')->appName,
            'class="copyright"'
        ),
        $html->getTag(
            'div',
            "&copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved",
            ' class="copyright text-light"'
        ),
        $html->getTag(
            'div',
            [
                ' <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->',
                'Designed by <a class="text-light" href="https://bootstrapmade.com/">BootstrapMade</a>'
            ],
            ' class="credits text-light"'
        ),
    ],
    'class="footer"'
);
$html->display();
