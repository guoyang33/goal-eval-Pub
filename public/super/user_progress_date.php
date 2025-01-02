<?php
/**
 * 查詢所有使用者的日期進度資料
 * 
*/
require_once './../html_head.php';
require_once './../connect_db.php';

echo '
<div class="d-grid pt-5 pb-3 px-2">
    <h1>查詢所有使用者的日期進度資料</h1>
</div>
<div class="d-grid pt-5 pb-3 px-2">
    <table class="table table-hover table-bordered">
        <tr>
            <th>id</th>
            <th>編號</th>
            <th>開始日期</th>
            <th>實際天數</th>
            <th>有效天數</th>
            <th>週數</th>
            <th>當週天數</th>
        </tr>
';

// 查詢所有使用者
$sth = $dbh->prepare("SELECT * FROM `user` WHERE `exp_id` LIKE :exp_id_like_a OR `exp_id` LIKE :exp_id_like_i ORDER BY `exp_id` ASC;");
$sth->execute(
    array(
        'exp_id_like_a' => 'A' . YEAR_NO . '%',
        'exp_id_like_i' => 'I' . YEAR_NO . '%'
    )
);
$users = $sth->fetchAll(PDO::FETCH_ASSOC);

// 查詢有效天數
$sth = $dbh->prepare("SELECT `u_id`, SUM(`provide_days`) AS `provide_days_sum` FROM `valid_days` GROUP BY `u_id`;");
$sth->execute();
$valid_days = $sth->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    $user['valid_days'] = 0;
    $start_date = new DateTimeImmutable($user['start_date']);
    $today = new DateTimeImmutable(date('Y-m-d'));
    $real_date_interval = $start_date->diff($today);
    $valid_days_days = 0;
    foreach ($valid_days as $valid_day) {
        if ($user['id'] == $valid_day['u_id']) {
            $valid_days_days = intval($valid_day['provide_days_sum']);
            break;
        }
    }
    $valid_date_interval = $start_date->diff($start_date->modify("{$valid_days_days} day"));
    $date_interval = $start_date->diff($start_date->modify((min(intval($real_date_interval->format('%r%a')), $valid_days_days)) . ' day'));
    if ($date_interval->invert == 1) {      // today < start_date
        $week = -1;
    } else {                                // today >= start_date
        if (gettype($date_interval->days / 7) == 'integer') {      // 第幾周
            $week = ($date_interval->days / 7) - 1;
        } else {
            $week = floor($date_interval->days / 7);
        }
        $progress_timeline_position = array(
            'week' => $week,
            'in_week_date_count' => (($date_interval->days % 7) ? ($date_interval->days % 7) : 7)      // 第幾天
        );
    }
    echo '
    <tr>
        <td>' . $user['id'] . '</td>
        <td>' . $user['exp_id'] . '</td>
        <td>' . $user['start_date'] . '</td>
        <td>' . $real_date_interval->format('%r%a') . '</td>
        <td>' . $valid_date_interval->format('%r%a') . '</td>
        <td>' . $progress_timeline_position['week'] . '</td>
        <td>' . $progress_timeline_position['in_week_date_count'] . '</td>
        <td>
            <a class="btn btn-outline-primary" href="./user_progress_date_add.php?u_id=' . $user['id'] . '&is_ios=' . $user['is_ios'] . '&days=8">天數8</a>
            <a class="btn btn-outline-success" href="./user_progress_date_add.php?u_id=' . $user['id'] . '&is_ios=' . $user['is_ios'] . '&days=1">天數++</a>
            <a class="btn btn-outline-success" href="./user_progress_date_sub.php?u_id=' . $user['id'] . '&is_ios=' . $user['is_ios'] . '&days=1">天數--</a>
        </td>
    </tr>
    ';
}
echo '
    </table>
</div>
';

exit;
/*
----------------------------------------------*/
$sth = $dbh->prepare("SELECT `id` FROM `user` WHERE `exp_id` LIKE :exp_id_like;");
$sth->execute(
    array(
        'exp_id_like' => 'A' . YEAR_NO . '%'
    )
);
$user_id = $sth->fetchAll();

$counter = 0;

$sth = $dbh->prepare('INSERT INTO `valid_days`(`id`, `u_id`, `provide_days`, `source_file`, `source_object`, `week`, `date`) VALUES (NULL, :u_id, :provide_days, :source_file, :source_object, :week, :date);');

echo '<h4>(';
foreach ($user_id as $u_ids) {
    $sth->execute([
        'u_id' => $u_ids['id'],
        'provide_days' => 8,
        'source_file' => 'SINGLE_USED_MANUAL_PHP_SCRIPT',
        'source_object' => '{"REASON":"PROVIDE_BEGINNING_PROGRESS_ANDROID", "THROUGH":"single_used_manual_php_script---for_android"}',
        'week' => 0,
        'date' => date('Y-m-d')
    ]);

    if ($dbh->lastInsertId() === false) {
        echo '<h1>INSERT ERROR: ';
        var_dump($dbh->errorInfo());
        echo '</h1>';
    }

    echo "{$u_ids['id']}, ";
    $counter += 1;
}
echo ')</h4>';
echo "<h4>Length: {$counter}</h4>";

?>