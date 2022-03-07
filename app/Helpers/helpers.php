<?php

use Carbon\Carbon;

/**
 * return formatted date
 */
if (!function_exists('formatted_date')) {
    function formatted_date($date): string
    {
        return Carbon::parse($date)->format('F d, Y');
    }
}

?>
