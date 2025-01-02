<?php

/*
 * 臨時使用的網頁，用來讓使用者在註冊後可以直接進入更改密碼的頁面
 * 
 */

require_once 'html_head.php';
require_once 'connect_db.php';

define('CRYPT_ARGUMENT', '$5$rounds=5000$app_3rd_2_temp$');      // 密碼加密參數:使用SHA-256加密

$u_id = $_GET['u_id'];
$password = $_GET['password'];
$sth = $dbh->prepare('SELECT * FROM user WHERE `id` = :u_id');
$sth->bindParam(':u_id', $u_id);
$sth->execute();
$user = $sth->fetch(PDO::FETCH_ASSOC);

if ($user !== false) {
    $exp_id = $user['exp_id'];
    include 'user_login_webpage_change_password.php';
    exit();
} else {
    echo '<h1>您的編號不存在，請重新輸入</h1>';
    include 'user_login_webpage_index.php';
    exit();
}