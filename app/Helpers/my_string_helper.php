<?php
if (!function_exists('containsAll')) {
    function containsAll($needle, $haystack)
    {
        $count = 0;
        $array = explode(' ', $needle);
        foreach ($array as $value) {
            if (false !== stripos($haystack, $value)) {
                ++$count;
            };
        }
        return $count == count($array);
    }
}