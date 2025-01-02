<?php
/*
 * utils.php
 * 存放未封裝的工具函數
 */


function logPostData($exp_id) {
    /*
     * Log Post data
     */

    if ($exp_id[0] == 'I') {
        $device = 'ios';
    } else if ($exp_id[0] == 'A') {
        $device = 'android';
    } else {
        $device = 'unknown';
    }

    $dateTimeImmutable = new DateTimeImmutable();
    $logFile = 'log/' . $device . '/' . $exp_id . '/' . $dateTimeImmutable->format('Y-m-d').'_' . time().'.json';

    // 檢查log目錄是否存在
    if (!file_exists('log')) {
        mkdir('log');
    }
    // 檢查ios目錄是否存在
    if (!file_exists('log/' . $device)) {
        mkdir('log/' . $device);
    }
    // 檢查exp_id目錄是否存在
    if (!file_exists('log/' . $device . '/' . $exp_id)) {
        mkdir('log/' . $device . '/' . $exp_id);
    }
    // 檢查postdata
    if ($_POST) {
        file_put_contents($logFile, json_encode($_POST));
    }
}
