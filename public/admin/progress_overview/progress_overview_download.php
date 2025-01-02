<?php
/**
 * 第7週 第一次更新版本 新增
 * 高級權限 查看使用者進度 下載
 * 
 * 2024/11/11   因應要求，編碼中實驗組別由「策略目標組：1；目標組：2」改為「目標組：1；策略目標組：2」，程式碼做相對應調整
 * 
 */

// 驗證
session_start();
$adminLogin = $_SESSION['admin_login'] ?? null;
?>

<?php if ($adminLogin != 1): ?>
    <script type="text/javascript">
        window.alert("您尚未登入，將導向至登入頁面");
        window.location.href = "/admin/login.php";
    </script>
<?php endif ?>

<?php

require_once './../../connect_db.php';

require_once 'progress_overview_lib.php';



define('SUPER_USER_TOKEN', 'superpermissionstoken');

// if (array_key_exists('confirm_data', $_POST)) {
//     if ($_POST['confirm_data']['super_user_token'] != SUPER_USER_TOKEN) {
//         http_response_code(403);
//         exit();
//     }
// } else {
//     http_response_code(403);
//     exit();
// }

if (array_key_exists('progress_overview_data', $_POST)) {
    switch ($_POST['progress_overview_data']['selected_option']) {
        case 'android_set_goal':
            $progress_device_lib = 'PROGRESS_LIB_ANDROID';
            $selected_group = 'ANDROID_SET_GOAL_REQUIRE_PROGRESS';
            $group_additional_condition = ' AND `u_id` IN (SELECT `id` FROM `user` WHERE `exp_id` LIKE "A' . YEAR_NO . '11%" OR `exp_id` LIKE "A' . YEAR_NO . '21%")';
            break;
        case 'android_training_strategy':
            $progress_device_lib = 'PROGRESS_LIB_ANDROID';
            $selected_group = 'ANDROID_TRAINING_STRATEGY_REQUIRE_PROGRESS';
            $group_additional_condition = ' AND `u_id` IN (SELECT `id` FROM `user` WHERE `exp_id` LIKE "A' . YEAR_NO . '12%" OR `exp_id` LIKE "A' . YEAR_NO . '22%")';
            break;
        case 'ios_set_goal':
            $progress_device_lib = 'PROGRESS_LIB_IOS';
            $selected_group = 'IOS_SET_GOAL_REQUIRE_PROGRESS';
            $group_additional_condition = ' AND `u_id` IN (SELECT `id` FROM `user` WHERE `exp_id` LIKE "I' . YEAR_NO . '11%" OR `exp_id` LIKE "I' . YEAR_NO . '21%")';
            break;
        case 'ios_training_strategy':
            $progress_device_lib = 'PROGRESS_LIB_IOS';
            $selected_group = 'IOS_TRAINING_STRATEGY_REQUIRE_PROGRESS';
            $group_additional_condition = ' AND `u_id` IN (SELECT `id` FROM `user` WHERE `exp_id` LIKE "I' . YEAR_NO . '12%" OR `exp_id` LIKE "I' . YEAR_NO . '22%")';
            break;
        default:
            echo '<div class="d-grid pt-5 pb-3 px-2">
                <h2 class="text-danger">管理人員頁面選項錯誤，請聯絡相關人員</h2>
                <p>progress_overview_data[selected_option]: ';
            var_dump($_POST['progress_overview_data']['selected_option']);
            echo '</p></div>';
    }
}
// } else {
//     http_response_code(404);
//     exit();
// }
    
$column_counter = 0;
$progress_condition_string_array = array();
$progress_column_name_array = array();
$progress_column_name_zh_array = array('編號', 'u_id');
foreach (constant($selected_group) as $progress) {
    array_push($progress_condition_string_array, sprintf(SQL_VIEW_CONDITION_STRING_TEMPLATE, constant($progress_device_lib)['column'][$progress]['sql_condition_string'], constant($progress_device_lib)['column'][$progress]['sql_result'], "c{$column_counter}"));
    array_push($progress_column_name_array, "SUM(`c{$column_counter}`) AS `c{$column_counter}`");
    array_push($progress_column_name_zh_array, constant($progress_device_lib)['column'][$progress]['name_zh']);

    $column_counter++;
}

$sth = $dbh->prepare(sprintf(SQL_CREATE_VIEW_STRING_TEMPLATE, implode(',', $progress_condition_string_array), constant($progress_device_lib)['cluster_sql_condition_string'], $group_additional_condition));
$sth->execute();

$sth = $dbh->prepare(sprintf(SQL_SELECT_STRING_TEMPLATE, implode(',', $progress_column_name_array)));
$sth->execute();
$result = $sth->fetchAll();

$sth = $dbh->prepare(SQL_DROP_STRING_TEMPLATE);
$sth->execute();

if (array_key_exists('file_name', $_POST['progress_overview_data'])) {
    $file_name = $_POST['progress_overview_data']['file_name'];
} else {
    $file_name = 'progress_overview_' . $_POST['progress_overview_data']['selected_option'] . date('_Ymd') . '.csv';
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $file_name . '"');

$data2client_download = fopen('php://output', 'wb');
fputcsv($data2client_download, $progress_column_name_zh_array);
foreach ($result as $row) {
    $next_line2write_array = array();
    for ($column = 0 ; $column < (count($row) / 2) ; $column++) {
        array_push($next_line2write_array, $row[$column]);
    }
    fputcsv($data2client_download, $next_line2write_array);
}
fclose($data2client_download);

?>