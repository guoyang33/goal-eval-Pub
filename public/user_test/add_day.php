<?php
/*
 * add_day.php
 * 增加計畫天數
 * 實際上是將開始日期向前推一天
 */

require_once '../get_user.php';

$start_date = new DateTimeImmutable($user['start_date']);
$start_date = $start_date->sub(new DateInterval('P1D'));

// 更新使用者開始日期
$sth = $dbh->prepare('UPDATE user SET start_date = :start_date WHERE id = :u_id');
$sth->execute(array(
    'start_date' => $start_date->format('Y-m-d'),
    'u_id' => $user['id']
));

// 查詢測試用有效天數
$sth = $dbh->prepare('SELECT * FROM valid_days WHERE u_id = :u_id AND source_file = :source_file AND source_object = :source_object');
$sth->execute(array(
    'u_id' => $user['id'],
    'source_file' => 'test',
    'source_object' => 'test'
));
$valid_days = $sth->fetch(PDO::FETCH_ASSOC);
if ($valid_days) {
    // 更新測試用有效天數
    $sth = $dbh->prepare('UPDATE valid_days SET provide_days = :provide_days WHERE id = :vd_id');
    $sth->execute(array(
        'provide_days' => intval($valid_days['provide_days']) + 1,
        'vd_id' => $valid_days['id']
    ));
} else {
    // 新增測試用有效天數
    $sth = $dbh->prepare('INSERT INTO valid_days (u_id, provide_days, source_file, source_object) VALUES (:u_id, :provide_days, :source_file, :source_object)');
    $sth->execute(array(
        'u_id' => $user['id'],
        'provide_days' => 1,
        'source_file' => 'test',
        'source_object' => 'test'
    ));
}

header('Location: main_panel.php?u_id=' . $user['id']);
?>