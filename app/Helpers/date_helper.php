<?php

use CodeIgniter\I18n\Time;

if (!function_exists('ci_time')) {
    function ci_time($timeString, $format = null)
    {
        if (empty($timeString)) {
            return false;
        }

        if (!is_null($format)) {
            $time = Time::createFromFormat($format, $timeString);
        } else {
            $time = new Time($timeString);
        }
        return $time;
    }
}
