<?php

use Carbon\Carbon;
/**
 * Payment expired date calculation from plan duration time and number
 *
 * @param $duration
 * @param $duration_count
 * @return string
 */
if (!function_exists('calculatePaymentExpireDate')) {
    function calculatePaymentExpireDate($start_date, $duration, $duration_count): string
    {
        $now_date = Carbon::create($start_date);
        switch ($duration) {
            case 'day':
                $expire_date = $now_date->addDays($duration_count);
                break;
            case 'week':
                $expire_date = $now_date->addWeeks($duration_count);
                break;
            case 'month':
                $expire_date = $now_date->addMonths($duration_count);
                break;
            case 'quarter':
                $expire_date = $now_date->addMonths(3);
                break;
            case 'semiannual':
                $expire_date = $now_date->addMonths(6);
                break;
            case 'year':
                $expire_date = $now_date->addYears($duration_count);
                break;
            default:
                $expire_date = $now_date;
        }
        return $expire_date;
    }
}
