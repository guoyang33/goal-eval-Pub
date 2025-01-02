<?php
/**
 * v7_1新增
 * 顯示使用時間及排名 Ios
 */
require_once 'connect_db.php';
require_once 'get_user.php';
require_once 'html_head.php';

require_once 'app_usage_lookup_and_rank_lib.php';



$category = strtolower($_GET['app_category']);

if ($user['exp_id'][0] == 'A') {
    header("Location: app_usage_lookup_and_rank_android.php?u_id={$user['id']}&app_category={$category}");
    exit();
}

// 常數
if (!defined('OBJECT_DEVICE')) {
    define('OBJECT_DEVICE', '_ios');
}

echo '<div class="d-grid pt-3 pb-3 px-2">';
echo '<h1>' . APP_CATEGORY_INFORMATION[$category]['name_zh'] . '的使用時間及排名</h1>';
echo '<table class="table table-striped table-hover"><tr><th>週數</th><th>使用時間</th><th>排名</th></tr>';

$week_counter = 0;
foreach (get_category_usage_weekly($dbh, OBJECT_DEVICE, $category, $user['id'], $week) as $row) {
    echo "<tr><td>{$week_counter}</td><td>" . time_beautifier(($row['usage_time'] * 60)) . "</td><td>在{$row['total_data']}位使用者中，排名第{$row['rank']}位</td></tr>";
    $week_counter++;
}

$usage_time_sum = get_category_usage_sum($dbh, OBJECT_DEVICE, $category, $user['id'], $week);
echo "<tr class='table-success'><td>加總</td><td>" . time_beautifier(($usage_time_sum['total_usage_time'] * 60)) . "</td><td>在{$usage_time_sum['total_data']}位使用者中，排名第{$usage_time_sum['rank']}位</td></tr>";

echo '<table>';

echo '<p class="text-secondary">※名次越前面即代表使用時間越少<br>※加總時間僅與相同進度的使用者做比較</p><br>';

echo '<br><button type="button" class="btn btn-primary" onclick="window.location.replace(' . "'redirect_ios.php?u_id={$user['id']}'" . ');">
返回主選單
</button>';

echo '</div>';
?>