<?php
/*
 * API TEST
 * 測試用的API接口
 * 回傳測試用的JSON資料
 */

require_once 'json_header.php';
require_once 'connect_db.php';

if (!key_exists('exp_id', $_GET)) {
    $response['headers']['status'] = 'EXP_ID_UNDEFINED';
    $response['headers']['error_msg'] = 'exp_id is undefined.';
} else {
    $exp_id = $_GET['exp_id'];
    $sth = $dbh->prepare("SELECT * FROM `user` WHERE `exp_id` = :exp_id LIMIT 1");
    $sth->execute(array(
        'exp_id' => $exp_id
    )); 
    $user = $sth->fetch(PDO::FETCH_ASSOC);
    if ($user === false) {
        $response['headers']['status'] = 'EXP_ID_NOT_FOUND';
        $response['headers']['error_msg'] = 'exp_id('.$exp_id.') not found.';
    } else {
        $response['contents']['user'] = $user;
    }
}
echo json_encode($response);