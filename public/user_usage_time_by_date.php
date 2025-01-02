<?php
/**
 * 顯示參與者各週總使用時間
 */
require_once 'connect_db.php';
// require_once 'html_head.php';

$app_usage = array();
$user_list = array();
$date_list = array();
$sql = "SELECT A.id, A.exp_id, B.date, SEC_TO_TIME(SUM(B.usage_time)) AS usage_time, SUM(B.usage_time) AS usage_time_sec
        FROM user A, app_usage B
        WHERE A.exp_id LIKE 'A" . YEAR_NO . "____' AND B.u_id = A.id
        GROUP BY B.u_id, B.date
        ORDER BY A.exp_id, B.date";
// $sql = "SELECT A.id, A.exp_id, B.date, SEC_TO_TIME(SUM(B.usage_time)) AS usage_time, SUM(B.usage_time) AS usage_time_sec
//         FROM user A, app_usage B
//         WHERE A.id = 29 AND B.u_id = A.id
//         GROUP BY B.u_id, B.date
//         ORDER BY B.u_id, B.date";
$sth = $dbh->prepare($sql);
$sth->execute();
foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $exp_id = $row['exp_id'];
    $user_list[$exp_id] = $row['id'];
    $date = $row['date'];
    $usage_time = $row['usage_time'];
    $usage_time_sec = $row['usage_time_sec'];
    if (!key_exists($exp_id, $app_usage)) {
        $app_usage[$exp_id] = array();
    }
    $app_usage[$exp_id][$date] = array(
        'usage_time' => $usage_time,
        'usage_time_sec' => $usage_time_sec
    );
    if (!in_array($date, $date_list)) {
        $date_list[] = $date;
    }
}

echo '<table border=1>
    <tr>
        <th>參與者編號</th>';
foreach ($date_list as $date) {
    echo "<th>{$date}</th>";
}
echo '<th>錯誤率</th>';
echo '</tr>';
foreach ($app_usage as $exp_id => $usage_time) {
    $count = 0;
    $error_count = 0;
    echo "<tr>
        <td>{$exp_id}({$user_list[$exp_id]})</td>";
    foreach ($date_list as $date) {
        if (key_exists($date, $usage_time)) {
            $count++;
            if ($usage_time[$date]['usage_time_sec'] > 86400) {
                $error_count++;
                echo '<td bgcolor=red>';
            } else {
                echo '<td>';
            }
            echo $usage_time[$date]['usage_time'];
        } else {
            echo '<td>';
        }
        echo '</td>';
    }
    echo "<td>{$error_count}/{$count}</td>";
    echo '</tr>';
}
echo '</table>'
?>