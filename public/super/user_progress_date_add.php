<?php

/*
 * user_progress_date_add.php
 */
require_once './../connect_db.php';

if ($_GET['is_ios']) {
    $source_object = 'PROVIDE_BEGINNING_PROGRESS_IOS';
} else {
    $source_object = 'PROVIDE_BEGINNING_PROGRESS_ANDROID';
}

// 查詢source_object為test的valid_days資料
$sth = $dbh->prepare('SELECT * FROM `valid_days` WHERE `u_id` = :u_id AND `source_object` = :source_object;');
$sth->execute(
    array(
        'u_id' => $_GET['u_id'],
        'source_object' => $source_object
    )
);
$valid_days = $sth->fetch(PDO::FETCH_ASSOC);
if ($valid_days) {
    $new_provide_days = intval($valid_days['provide_days']) + intval($_GET['days']);
    // 更新source_object為test的valid_days資料
    $sth = $dbh->prepare('UPDATE `valid_days` SET `provide_days` = :provide_days WHERE `id` = :vd_id;');
    $sth->execute(
        array(
            'provide_days' => $new_provide_days,
            'vd_id' => $valid_days['id']
        )
    );
} else {
    // 新增source_object為test的valid_days資料
    $sth = $dbh->prepare('INSERT INTO `valid_days` (`u_id`, `provide_days`, `source_file`, `source_object`, `week`, `date`) VALUES (:u_id, :provide_days, :source_file, :source_object, :week, :date);');
    $sth->execute(
        array(
            'u_id' => $_GET['u_id'],
            'provide_days' => $_GET['days'],
            'source_file' => 'user_progress_date.php',
            'source_object' => $source_object,
            'week' => null,
            'date' => null
        )
    );
}
header("Location: ./user_progress_date.php");
?>