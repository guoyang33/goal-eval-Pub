<?php
/*
 * 設定當週減量目標 ios
 * 
 * 2022-11-18:待優化 界面 > 程式邏輯
 */
require_once 'connect_db.php';
require_once 'get_user.php';
require_once 'html_head.php';
require_once 'results_calculate_lib.php';

define("MAIN_APP_CATEGORY", [
    'GAME' =>               '遊戲',
    'SOCIAL' =>             '社交',
    'ENTERTAINMENT' =>      '娛樂'
]);

if (!defined('OBJECT_DEVICE')) {
    define('OBJECT_DEVICE', '_ios');
}

init_define_SELECT_CATEGORY_SCORE_CALCULATE_SQL_STRING_TEMPLATE(OBJECT_DEVICE);
init_define_SELECT_CATEGORY_SCORE_SAVE_CALCULATE_SQL_STRING_TEMPLATE(OBJECT_DEVICE);
init_define_IS_REACH_GOAL_SQL_STRING_TEMPLATE();
init_define_GET_NEW_BASELINE_USAGE_SQL_STRING_TEMPLATE(OBJECT_DEVICE);

$all_need_save_time = array();



$goal = get_goal($user['id'], $week);
if ($goal['start_date'] != null && $user['test'] != 1) {
    // 顯示設定的減量目標

    echo '<div class="d-grid pt-5 px-2">
    <p>本週已經設定過目標囉</p>';
    echo '<button type="button" class="btn btn-secondary" onclick=location.href="redirect_ios.php?u_id='.$user['id'].'">
    返回主頁面
    </button>
    </div>';
} else {
    $baseline_usage_time_mean = array(
        'GAME' => 0,
        'SOCIAL' => 0,
        'ENTERTAINMENT' => 0,
        'TOTAL' => 0
    );
    $last_week_usage_time_mean = array(
        'GAME' => 0,
        'SOCIAL' => 0,
        'ENTERTAINMENT' => 0,
        'TOTAL' => 0
    );
    if ($week == 1) {       // 第1週
        // 查詢category_usage_manual_ios資料表中第0週的資料
        // 因為資料表中紀錄的usage_time是以分鐘為單位，所以要乘上60
        $sth = $dbh->prepare('SELECT category, usage_time * 60 / 7 AS usage_time_mean_sec FROM `category_usage_manual_ios` WHERE `u_id`=:u_id AND `week` = 0;');
        $sth->execute([
            'u_id' => $user['id']
        ]);
        foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row) {
            if (in_array(strtoupper($row['category']), array_keys($baseline_usage_time_mean))) {
                $baseline_usage_time_mean[strtoupper($row['category'])] = $row['usage_time_mean_sec'];
                $last_week_usage_time_mean[strtoupper($row['category'])] = $row['usage_time_mean_sec'];
            }
        }
    } else if ($week >= 2 && $week <= 8) {      // 第2-8週
        $is_reach_goal_sql_string_array = array();      // 用於查看上週達標狀況的sql模板字串
        $all_need_save_js_display_obj = array();
        $save_goal_check_html = '<br><div class="d-rid pb-2 px-2"><h3><strong>【搶救100分】可搶救上週未達標的類別：</strong></h3><h6>※除了達成本週減量目標，若前一週未達標，本週再額外減少使用前一週所超過的時間可補領獎勵點數。</h6><h6>※若系統計算您上週比目標多出來的使用時間大於或等於本週所定減量目標時間，則搶救的目標時間即為0小時（完全不使用）。</h6><table class="table table-striped"><tr><th>APP類別</th><th>目標時間</th><th>是否要搶救?(是請勾選)</th></tr>';

        // 查詢category_usage_manual_ios資料表中第0週乘上有達標週減量目標的資料
        // 因為資料表中紀錄的usage_time是以分鐘為單位，所以要乘上60
        foreach (array_keys($baseline_usage_time_mean) as $baseline_category) {
            $sth = $dbh->prepare(sprintf(SELECT_CATEGORY_SCORE_CALCULATE_SQL_STRING_TEMPLATE, strtolower($baseline_category)));
            $sth->execute([
                'u_id' => $user['id'],
                'week' => $week
            ]);
            $baseline_usage_time_mean[$baseline_category] = ($sth->fetchAll(PDO::FETCH_ASSOC))[0]['usage_time'] * 60 / 7;

            array_push($is_reach_goal_sql_string_array, sprintf(IS_REACH_GOAL_SQL_STRING_TEMPLATE, strtolower($baseline_category)));
        }

        // 查看上週達標狀況
        $sth = $dbh->prepare('SELECT ' . implode(',', $is_reach_goal_sql_string_array) . ' FROM `goal` WHERE `u_id`=:u_id AND `week`=:last_week LIMIT 1;');
        $sth->execute([
            'u_id' => $user['id'],
            'last_week' => ($week - 1)
        ]);
        $last_week_reach_goal = ($sth->fetchAll(PDO::FETCH_ASSOC))[0];

        // 設定是否出現可搶救目標選項
        foreach ($last_week_reach_goal as $is_reach_goal_category => $is_reach_goal) {
            if (!boolval($is_reach_goal)) {
                $need_save_category = str_replace('reach_goal_', '', $is_reach_goal_category);
                $sth = $dbh->prepare(sprintf(SELECT_CATEGORY_SCORE_SAVE_CALCULATE_SQL_STRING_TEMPLATE, $need_save_category));
                $sth->execute([
                    'u_id' => $user['id'],
                    'week' => $week
                ]);
                $all_need_save_time[$need_save_category] = ($sth->fetchAll(PDO::FETCH_ASSOC))[0]["score_save_{$need_save_category}_time"] * 60 / 7;

                $all_need_save_js_display_obj[$need_save_category] = '{';
                if ($baseline_usage_time_mean[strtoupper($need_save_category)] == 0) {
                    $all_need_save_js_display_obj[$need_save_category] .= "0:'00:00:00'";
                    $default_save_usage_time_display = '00:00:00';
                } else {
                    for ($i = 3; $i <= 10; $i++) {
                        $reduce_rate = 1 - ($i * 0.01);
                        $save_time_reduce_formatted = time_beautifier(ceil(($baseline_usage_time_mean[strtoupper($need_save_category)] * $reduce_rate) - $all_need_save_time[$need_save_category]));
                        if ($i == 5) {
                            $default_save_usage_time_display = (((($baseline_usage_time_mean[strtoupper($need_save_category)] * $reduce_rate) - $all_need_save_time[$need_save_category]) < 0) ? '00:00:00' : $save_time_reduce_formatted);
                        }
                        $all_need_save_js_display_obj[$need_save_category] .= "{$i}:'" . (((($baseline_usage_time_mean[strtoupper($need_save_category)] * $reduce_rate) - $all_need_save_time[$need_save_category]) < 0) ? '00:00:00' : $save_time_reduce_formatted) . "',";
                    }
                }
                $all_need_save_js_display_obj[$need_save_category] .= '}';

                $save_goal_check_html .= (
                    '<tr><td>' . (($need_save_category == 'total') ? '總使用時間' : MAIN_APP_CATEGORY[strtoupper($need_save_category)]) . '</td>
                    <td id="save_' . $need_save_category . '_time">' . $default_save_usage_time_display . '</td>
                    <td><input type="checkbox" class="form-check-input mt-0" name="save_score[save_' . $need_save_category . ']"></td></tr>'
                );
            }
        }
        $save_goal_check_html .= '</table></div>';

        // 查詢category_usage_manual_ios資料表中第N-1週的資料
        // 因為資料表中紀錄的usage_time是以分鐘為單位，所以要乘上60
        $sth = $dbh->prepare('SELECT category, usage_time * 60 / 7 AS usage_time_mean_sec FROM `category_usage_manual_ios` WHERE `u_id`=:u_id AND `week`=:last_week;');
        $sth->execute([
            'u_id' => $user['id'],
            'last_week' => $week - 1
        ]);
        foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row) {
            if (in_array(strtoupper($row['category']), array_keys($last_week_usage_time_mean))) {
                $last_week_usage_time_mean[strtoupper($row['category'])] = $row['usage_time_mean_sec'];
            }
        }
    } else {
        echo '<div class="d-grid pt-5 px-2">
        <p>本週已經不需要設定目標囉</p>';
        echo '<button type="button" class="btn btn-secondary" onclick=location.href="redirect_ios.php?u_id='.$user['id'].'">
        返回主頁面
        </button>
        </div>';
        die();
    }

    // 顯示
    echo '<div class="d-grid pt-5 pb-3 px-2">
    <h2>設定本週減量時間</h2>
    </div>
    <div class="d-rid pb-2 px-2">
    <h3>您在上一週的手機使用時間</h3>
    <table class="table table-striped">
    <tr>
    <th>APP類別</th>
    <th>平均使用時間(天)</th>
    </tr>';
    foreach (MAIN_APP_CATEGORY as $app_category => $category_chinese) {
        echo '<tr>
        <td>'.$category_chinese.'</td>
        <td>'.time_beautifier($last_week_usage_time_mean[$app_category]).'</td>
        </tr>';
    }
    echo '<tr class="table-success">
    <td>總使用時間</td>
    <td>'.time_beautifier($last_week_usage_time_mean['TOTAL']).'</td>
    </tr>
    </table>
    </div>';

    // 顯示設定選項
    echo '<div class="d-rid pb-2 px-2">
    <form action="set_goal_ios_submit.php" method="POST">
    <input type="hidden" name="u_id" value="'.$user['id'].'">
    <h3>請您依預備週的使用為基準來設定本週的減量目標</h3>';
    foreach (MAIN_APP_CATEGORY as $app_category => $category_chinese) {
        if ($baseline_usage_time_mean[$app_category] == 0) {
            echo '<h4>'.$category_chinese.'</h4>
            <div class="pb-2 px-2 row">
            <input type="hidden" name="reduce_rate['.$app_category.']" value="0">
            <input type="range" class="form-range col"  value="0" min="3" max="10" disabled>
            <output class="col">0%(00:00:00)</output>
            </div>'; 
        } else {
            // $sth = $dbh->prepare(sprintf(GET_NEW_BASELINE_USAGE_SQL_STRING_TEMPLATE, strtolower($app_category)));
            // $sth = $dbh->prepare(sprintf(SELECT_CATEGORY_SCORE_CALCULATE_SQL_STRING_TEMPLATE, strtolower($app_category)));
            // echo $sth->queryString;
            // $sth->execute([
            //     'u_id' => $user['id'],
            //     'week' => $week
            // ]);
            // $new_baseline_usage_time = $sth->fetch(PDO::FETCH_COLUMN, 0);
            $js_display_obj = '{';
            for ($i=3; $i<=10; $i++) {
                $reduce_rate = 1 - ($i/100);
                // $usage_time_reduce_formatted = time_beautifier(ceil($new_baseline_usage_time * 60 / 7 * $reduce_rate));
                $usage_time_reduce_formatted = time_beautifier(ceil($baseline_usage_time_mean[$app_category] * $reduce_rate));
                if ($i == 5) {
                    $default_reduce_usage_time_display = "{$i}%({$usage_time_reduce_formatted})";
                }
                $js_display_obj .= "{$i}:'{$i}%({$usage_time_reduce_formatted})',";
            }
            $js_display_obj .= '}';
            echo '<h4>'.$category_chinese.'</h4>
            <div class="pb-2 px-2 row">
            <input type="range" class="form-range col" name="reduce_rate['.$app_category.']" value="5" min="3" max="10" oninput="this.nextElementSibling.value='.$js_display_obj.'[this.value];' . (in_array(strtolower($app_category), array_keys($all_need_save_time)) ? (' document.querySelector(\'#save_' . strtolower($app_category) . '_time\').innerHTML = ' . $all_need_save_js_display_obj[strtolower($app_category)] . '[this.value];') : '') . '">
            <output class="col">'.$default_reduce_usage_time_display.'</output>
            </div>';
        }
    }
    // 總使用時間
    if ($baseline_usage_time_mean['TOTAL'] == 0) {
        echo '<h4>總螢幕使用時間</h4>
        <div class="pb-2 px-2 row">
        <input type="hidden" name="reduce_rate[TOTAL]" value="0">
        <input type="range" class="form-range col"  value="0" min="3" max="10" disabled>
        <output class="col">0%(00:00:00)</output>
        </div>';
    } else {
        $js_display_obj = '{';
        for ($i=3; $i<=10; $i++) {
            $reduce_rate = 1 - ($i/100);
            $usage_time_reduce_beautifier = time_beautifier(ceil($baseline_usage_time_mean['TOTAL'] * $reduce_rate));
            if ($i == 5) {
                $default_reduce_usage_time_display = "{$i}%({$usage_time_reduce_beautifier})";
            }
            $js_display_obj .= "{$i}:'{$i}%({$usage_time_reduce_beautifier})',";
        }
        $js_display_obj .= '}';
        echo '<h4>總螢幕使用時間</h4>
        <div class=" pb-2 px-2 row">
        <input type="range" class="form-range col" name="reduce_rate[TOTAL]" value="5" min="3" max="10" oninput="this.nextElementSibling.value='.$js_display_obj.'[this.value];' . (in_array('total', array_keys($all_need_save_time)) ? (' document.querySelector(\'#save_total_time\').innerHTML = ' . $all_need_save_js_display_obj['total'] . '[this.value];') : '') . '">
        <output class="col">'.$default_reduce_usage_time_display.'</output>
        </div>';
    }

    // 印出是否搶救的表格
    if (boolval($all_need_save_time)) {
        echo $save_goal_check_html;
    }

    echo '<div class="d-grid pb-2 px-2">
    <input type="submit" class="btn btn-primary" value="提交">
    </div>
    </form>
    </div>';
}
?>