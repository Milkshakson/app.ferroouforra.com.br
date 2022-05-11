<?php

use CodeIgniter\I18n\Time;
if (!function_exists('ci_time')){
    function ci_time($timeString='now'){
        $time = new Time($timeString);
        return $time;
    }
}
?>