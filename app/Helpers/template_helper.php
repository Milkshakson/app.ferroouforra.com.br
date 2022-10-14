<?php
if (!function_exists('breadcrumb')) {
    function breadcrumb($items = [], $header = null)
    {
        $html = '';
        if (!is_null($header)) {
            $html .= "<h1>$header</h1>";
        }
        $html .= "<nav>
			<ol class='breadcrumb'>";
        foreach ($items as $item) {
            $html .= "<li class='breadcrumb-item'>$item</li>";
        }
        $html .= "</ol>
		</nav>";
        return $html;
    }
}