<?php
/**
 * 第7週 第一次更新版本 新增
 * 高級權限 查看使用者進度
 * 
 * 2024/11/11   因應要求，編碼中實驗組別由「策略目標組：1；目標組：2」改為「目標組：1；策略目標組：2」，程式碼做相對應調整
 * 
 */
require_once './../../html_head.php';
require_once './../../connect_db.php';

require_once 'progress_overview_lib.php';



define('SUPER_USER_TOKEN', 'superpermissionstoken');

// 驗證
if (array_key_exists('confirm_data', $_POST)) {
    if ($_POST['confirm_data']['super_user_token'] != SUPER_USER_TOKEN) {
        echo '您好像來錯地方囉，請聯絡相關人員<br>如果您是管理人員請重新登入';
        exit();
    }
} else {
    echo '您好像來錯地方囉，請聯絡相關人員<br>如果您是管理人員請重新登入';
    exit();
}

if (array_key_exists('progress_overview_data', $_POST)) {
    echo '<script type="text/javascript">
        window.addEventListener("load", () => {
            document.querySelector("#download_file").addEventListener("click", () => {
                const file_name = "progress_overview_' . $_POST['progress_overview_data']['selected_option'] . date('_Ymd') . '.csv";
                const xhr = new XMLHttpRequest();
                
                xhr.open("POST", "progress_overview_download.php");
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (this.status == 200) {
                        const blob = new Blob([this.response], {type: "text/csv"});
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement("a");

                        a.style = "display: none";
                        a.href = url;
                        a.download = file_name;
                        document.body.appendChild(a);
                        
                        a.click();
                        window.URL.revokeObjectURL(url);
                    } else {
                        alert(`錯誤: ${this.status}`);
                    }
                };
                xhr.send("confirm_data[super_user_token]=' . SUPER_USER_TOKEN . '&progress_overview_data[selected_option]=' . $_POST['progress_overview_data']['selected_option'] . '&progress_overview_data[file_name]=" + file_name);
            });
        });
    </script>';
    switch ($_POST['progress_overview_data']['selected_option']) {
        case 'android_set_goal':
            echo '<h1>Android目標調控組</h1>';
            $progress_device_lib = 'PROGRESS_LIB_ANDROID';
            $selected_group = 'ANDROID_SET_GOAL_REQUIRE_PROGRESS';
            $group_additional_condition = ' AND `u_id` IN (SELECT `id` FROM `user` WHERE `exp_id` LIKE "A' . YEAR_NO . '11%" OR `exp_id` LIKE "A' . YEAR_NO . '21%")';
            break;
        case 'android_training_strategy':
            echo '<h1>Android策略訓練組</h1>';
            $progress_device_lib = 'PROGRESS_LIB_ANDROID';
            $selected_group = 'ANDROID_TRAINING_STRATEGY_REQUIRE_PROGRESS';
            $group_additional_condition = ' AND `u_id` IN (SELECT `id` FROM `user` WHERE `exp_id` LIKE "A' . YEAR_NO . '12%" OR `exp_id` LIKE "A' . YEAR_NO . '22%")';
            break;
        case 'ios_set_goal':
            echo '<h1>Ios目標調控組</h1>';
            $progress_device_lib = 'PROGRESS_LIB_IOS';
            $selected_group = 'IOS_SET_GOAL_REQUIRE_PROGRESS';
            $group_additional_condition = ' AND `u_id` IN (SELECT `id` FROM `user` WHERE `exp_id` LIKE "I' . YEAR_NO . '11%" OR `exp_id` LIKE "I' . YEAR_NO . '21%")';
            break;
        case 'ios_training_strategy':
            echo '<h1>Ios策略訓練組</h1>';
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
    echo '<button type="button" id="download_file" class="btn btn-primary">下載csv檔</button>';
} else {
    echo '<script type="text/javascript">
            const FORM_TARGET_PREFIX = `PROGRESS_OVERVIEW_WINDOW_${Date.now()}`;
            let children_window_counter = 0;
            function formSubmit() {
                const new_children_window_name = `${FORM_TARGET_PREFIX}_${children_window_counter}`;
                document.querySelector("#option_form").setAttribute("target", new_children_window_name);
                window.open("", new_children_window_name);
                children_window_counter++;
            }
        </script>
        <form id="option_form" action="' . basename(__FILE__) . '" method="POST" onsubmit="formSubmit();" target="">
            <input type="hidden" name="confirm_data[super_user_token]" value="' . SUPER_USER_TOKEN . '">
            <h1 class="d-flex justify-content-center">受試者填答進度查看</h1>
            <h1 class="d-flex justify-content-center">請選擇要查看的組別</h1>
            <div class="d-flex justify-content-center">
                <span>
                    <label for="option_android_set_goal" class="btn btn-outline-primary">Android目標調控組</label>
                    <input type="submit" id="option_android_set_goal" class="btn-check d-none" name="progress_overview_data[selected_option]" value="android_set_goal">
                </span>
                &nbsp;&nbsp;&nbsp;
                <span>
                    <label for="option_android_training_strategy" class="btn btn-outline-primary">Android策略訓練組</label>
                    <input type="submit" id="option_android_training_strategy" class="btn-check d-none" name="progress_overview_data[selected_option]" value="android_training_strategy">
                </span>
            </div>
            <div class="d-flex justify-content-center">
                <span>
                    <label for="option_ios_set_goal" class="btn btn-outline-primary">Ios目標調控組</label>
                    <input type="submit" id="option_ios_set_goal" class="btn-check d-none" name="progress_overview_data[selected_option]" value="ios_set_goal">
                </span>
                &nbsp;&nbsp;&nbsp;
                <span>
                    <label for="option_ios_training_strategy" class="btn btn-outline-primary">Ios策略訓練組</label>
                    <input type="submit" id="option_ios_training_strategy" class="btn-check d-none" name="progress_overview_data[selected_option]" value="ios_training_strategy">
                </span>
            </div>
        </form>
    </div>
    </body>
    </html>';
    exit();
}

$column_counter = 0;
$progress_condition_string_array = array();
$progress_column_name_array = array();
$progress_column_name_zh_string = '';
foreach (constant($selected_group) as $progress) {
    array_push($progress_condition_string_array, sprintf(SQL_VIEW_CONDITION_STRING_TEMPLATE, constant($progress_device_lib)['column'][$progress]['sql_condition_string'], constant($progress_device_lib)['column'][$progress]['sql_result'], "c{$column_counter}"));
    array_push($progress_column_name_array, "SUM(`c{$column_counter}`) AS `c{$column_counter}`");
    $progress_column_name_zh_string .= ('<th>' . constant($progress_device_lib)['column'][$progress]['name_zh'] . '</th>');

    $column_counter++;
}

$sth = $dbh->prepare(sprintf(SQL_CREATE_VIEW_STRING_TEMPLATE, implode(',', $progress_condition_string_array), constant($progress_device_lib)['cluster_sql_condition_string'], $group_additional_condition));
$sth->execute();

$sth = $dbh->prepare(sprintf(SQL_SELECT_STRING_TEMPLATE, implode(',', $progress_column_name_array)));
$sth->execute();
$result = $sth->fetchAll();

$sth = $dbh->prepare(SQL_DROP_STRING_TEMPLATE);
$sth->execute();

echo '<table class="table table-striped-columns table-hover"><tr><th>編號</th><th>u_id</th>' . $progress_column_name_zh_string . '</tr>';
foreach ($result as $row) {
    echo "<tr><td>{$row['exp_id']}</td><td>{$row['u_id']}</td>";
    for ($column = 2 ; $column < (count($row) / 2) ; $column++) {
        $valid_times = constant($progress_device_lib)['column'][constant($selected_group)[$column - 2]]['sql_condition_string'];
        echo '<td>' . ((in_array($row[$column], constant($progress_device_lib)['column'][constant($selected_group)[$column - 2]]['valid_times'])) ? $row[$column] : "<strong class='text-danger'>{$row[$column]}</strong>") . '</td>';
    }
    echo '</tr>';
}
echo '</table>';

?>