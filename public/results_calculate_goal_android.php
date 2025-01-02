<?php
/*
 * 結算是否達成目標(android版)
 * 
 * 2024-11-14   加入autoload.php，載入Log類別用Log::d()協助除錯
 * 2024-11-18   使用Goal::updateScore()取代原來的update_category_sql_string_array
 * 
 */

use Cyouliao\Goaleval\Log;
use Cyouliao\Goaleval\Goal;

include_once './../vendor/autoload.php';

require_once 'connect_db.php';
require_once 'get_user.php';
require_once 'html_head.php';
require_once 'results_calculate_lib.php';
require_once 'carbon_track_lib.php';

Log::d('Log is on.');

// 常數
define('APP_CATEGORY', array(
    'game',
    'video',
    'social',
    'communication',
    'total'
));
define('APP_CATEGORY_INFORMATION', array(
    'game' => array('zh' => '遊戲'),
    'video' => array('zh' => '影音播放與編輯'),
    'social' => array('zh' => '社交'),
    'communication' => array('zh' => '通訊'),
    'total' => array('zh' => '總使用時間')
));
if (!defined('OBJECT_DEVICE')) {
    define('OBJECT_DEVICE', '_android');
}

init_define_UPDATE_CATEGORY_SCORE_CALCULATE_SQL_STRING_TEMPLATE(OBJECT_DEVICE);
init_define_UPDATE_CATEGORY_SCORE_SAVE_CALCULATE_SQL_STRING_TEMPLATE(OBJECT_DEVICE);
init_define_IS_REACH_GOAL_SQL_STRING_TEMPLATE();
init_define_IS_SAVE_GOAL_SQL_STRING_TEMPLATE();
init_define_CARBON_TRACK_SLOGAN();
init_define_SELECT_USAGE_TIME_CURRENT_LAST_SQL_TEMPLATE(OBJECT_DEVICE);

$week = $week - 1;      // 因為是下週一結算上週全部的所以week要-1

Log::d("week: $week");

// 個別類別的sql指令字串，後面會在組成完整字串
$update_category_sql_string_array = array();
$update_category_save_sql_string_array = array();
$is_reach_goal_sql_string_array = array();
$is_save_goal_sql_string_array = array();
$category_goal_srting_array = array();      // v7_1新增，用於取得分數，以顯示取得的拼圖

if ($week > 1) {        // 由redirect.php引入，引入前已經確認過是否為第1-8週，故此處不用再確認小於等於8
    $sth = $dbh->prepare('SELECT `save_game`, `save_video`, `save_social`, `save_communication`, `save_entertainment`, `save_total` FROM `goal` WHERE `u_id` = :u_id AND `week` = :last_week LIMIT 1;');
    $sth->execute([
        'u_id' => $user['id'],
        'last_week' => ($week - 1)      // 查看上上週是否要補救，所以再減1
    ]);
    $save_score_flags = ($sth->fetchAll(PDO::FETCH_ASSOC))[0];
    Log::d('save score flags: ' . var_export($save_score_flags, true));
}

foreach (APP_CATEGORY as $category) {
    array_push($update_category_sql_string_array, sprintf(UPDATE_CATEGORY_SCORE_CALCULATE_SQL_STRING_TEMPLATE, $category));
    array_push($is_reach_goal_sql_string_array, sprintf(IS_REACH_GOAL_SQL_STRING_TEMPLATE, $category));
    array_push($category_goal_srting_array, "FLOOR((SUM(`score_{$category}`) + SUM(IFNULL(`score_save_{$category}`, 0))) / 100) AS goal_{$category}");      // v7_1新增

    if ($week > 1) {
        if (boolval($save_score_flags["save_{$category}"])) {
            array_push($update_category_save_sql_string_array, sprintf(UPDATE_CATEGORY_SCORE_SAVE_CALCULATE_SQL_STRING_TEMPLATE, $category));
            array_push($is_save_goal_sql_string_array, sprintf(IS_SAVE_GOAL_SQL_STRING_TEMPLATE, $category));
        }
    }
}

// 計算補救分數(第二週之後才算)
if ($week > 1) {
    if (boolval($update_category_save_sql_string_array)) {
        // $sql = 'UPDATE `goal` SET ' . implode(',', $update_category_save_sql_string_array) . ' WHERE `u_id` = :u_id AND `week` = (:week - 1) LIMIT 1;';
        // $sth = $dbh->prepare($sql);
        // $sth->execute([
        //     'u_id' => $user['id'],
        //     'week' => $week
        // ]);

        $updateScoreSaveSuccess = Goal::updateScoreSave($user['id'], $week, boolval($user['is_ios']));

        if ($updateScoreSaveSuccess === false) {
        // if ($dbh->lastInsertId() === false) {
            echo '<div class="d-grid pt-5 pb-3 px-2">
            <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: UPDATE goal(save score)</h2>
            <p>errorInfo: ';
            var_dump($dbh->errorInfo());
            echo '</p>
            </div>';
            exit();
        }
    }
}

// Log::d('updateCategorySQLStringArray: ' . var_export($update_category_sql_string_array, true));
// Log::d('updateCategorySQLStringArray.implode(,): ' . var_export(implode(',', $update_category_sql_string_array), true));

// $sql = 'UPDATE `goal` SET ' . implode(',', $update_category_sql_string_array) . ' WHERE `u_id` = :u_id AND `week` = :week LIMIT 1;';
// Log::d("sql: $sql");

// try {
//     $sth = $dbh->prepare($sql);
//     $sth->execute([
//         'u_id' => $user['id'],
//         'week' => $week
//     ]);
//     Log::d("sql executed");

// } catch (Exception $e) {
//     Log::d("Exception: " . $e->getMessage());

// } catch (PDOException $e) {
//     Log::d("PDOException: " . $e->getMessage());

// } finally {
//     Log::d("dbh->errorCode: " . print_r($dbh->errorCode(), true));

// }


// $dbLastInsertId = $dbh->lastInsertId();
// Log::d("dbLastInsertId: " . var_export($dbLastInsertId, true));

try {
    Log::d("user.is_ios: " . $user['is_ios']);
    Log::d("user.is_ios: " . var_export(boolval($user['is_ios']), true));

    $updateScoreSuccess = Goal::updateScore($user['id'], $week, boolval($user['is_ios']));

} catch (Exception $e) {
    Log::d("Exception: " . $e->getMessage());

} catch (PDOException $e) {
    Log::d("PDOException: " . $e->getMessage());
} finally {
    Log::d("update score success: " . var_export($updateScoreSuccess, true));
}

if ($updateScoreSuccess === false) {
    echo '<div class="d-grid pt-5 pb-3 px-2">
    <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: UPDATE goal</h2>
    <p>errorInfo: ';
    var_dump($dbh->errorInfo());
    echo '</p>
    </div>';

} else {
    echo '<div class="d-grid pt-5 pb-3 px-2">
    <h2>上週使用時間結算完成</h2><br>';
    
    $sql = 'SELECT ' . implode(',', $is_reach_goal_sql_string_array) . ' FROM `goal` WHERE `u_id`=:u_id AND `week`=:week LIMIT 1;';
    Log::d('SQL: ' . var_export($sql, true));

    $sth = $dbh->prepare($sql);

    try {
        $sth->execute([
            'u_id' => $user['id'],
            'week' => $week
        ]);
    } catch (Exception $e) {
        Log::d('Exception: ' . var_export($e->getMessage(), true));
    }

    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    Log::d('Query result: ' . var_export($result, true));

    echo '<table class="table table-bordered">';
    foreach (APP_CATEGORY as $category) {
        echo '<tr>
            <th>
                ' . APP_CATEGORY_INFORMATION[$category]['zh'] . '
                <span style="font-weight:lighter">使用時間減量目標</span>
            </th>
            <td>
                ' . ((boolval($result[0]["reach_goal_{$category}"])) ? '<strong class="text-primary">達標！</strong>' : '<strong class="text-danger">未達標</strong>') . '
            </td>
        </tr>';
    }
    echo '</table>';

    // 碳足跡小語
    // 先檢查是否達標
    echo '<hr>';
    echo '<h3 class="text-info">你的碳足跡減量情況：</h3>';
    // 查詢上週與上上週的使用時間
    $sth = $dbh->prepare(SELECT_USAGE_TIME_CURRENT_LAST_SQL_TEMPLATE);
    Log::d('sql: ' . var_export(SELECT_USAGE_TIME_CURRENT_LAST_SQL_TEMPLATE, true));
    $sth->execute(array(
        'u_id' => $user['id'],
        'current_week' => $week,
        'last_week' => ($week - 1),
        'category' => 'total'
    ));
    $usage_time_sum = $sth->fetch(PDO::FETCH_ASSOC);
    $usage_time_current_week_beautify = time_beautifier($usage_time_sum['current_week'] * 60);
    $usage_time_diff = abs($usage_time_sum['last_week'] - $usage_time_sum['current_week']);
    $carbon_out = round(($usage_time_sum['current_week'] / 60) * 0.5, 2);
    $carbon_out_diff = round(($usage_time_diff / 60) * 0.5, 2);
    if ($usage_time_sum['last_week'] > $usage_time_sum['current_week']) {       // 減少 (上週 > 本週)
        echo '<h4>
        上週總使用時間' . $usage_time_current_week_beautify . '，碳足跡為' . $carbon_out . ' 公克，較前週
        <span class="text-primary">減少</span>
        ' . $carbon_out_diff . ' 公克。
        </h4>'; 
        if (boolval($result[0]['reach_goal_total'])) {      // 有無達標
            echo '<h4>' . CARBON_TRACK_SLOGAN[$week]['goal-less'] . '</h4>';
        } else {
            echo '<h4>' . CARBON_TRACK_SLOGAN[$week]['fail-less'] . '</h4>';
        }
    } else {
        if ($usage_time_sum['last_week'] == $usage_time_sum['current_week']) {  // 一樣 (上週 = 本週)
            echo '<h4>
            上週總使用時間' . $usage_time_current_week_beautify . '，碳足跡為' . $carbon_out . ' 公克，與前週
            <span class="text-primary">相同</span>
            。
            </h4>';
        } else {                                                        // 增加 (上週 < 本週)
            echo '<h4>
            上週總使用時間' . $usage_time_current_week_beautify . '，碳足跡為' . $carbon_out . ' 公克，較前週
            <span class="text-danger">增加</span>
            ' . $carbon_out_diff . ' 公克。
            </h4>';
        }
        if (boolval($result[0]['reach_goal_total'])) {      // 有無達標
            echo '<h4>' . CARBON_TRACK_SLOGAN[$week]['goal-more'] . '</h4>';
        } else {
            echo '<h4>' . CARBON_TRACK_SLOGAN[$week]['fail-more'] . '</h4>';
        }
    }

    // 印出是否補救成功
    if ($week > 1) {
        if (boolval($update_category_save_sql_string_array)) {
            echo '<br><h2>補救目標：</h2><br>';

            $sth = $dbh->prepare('SELECT ' . implode(',', $is_save_goal_sql_string_array) . ' FROM `goal` WHERE `u_id`=:u_id AND `week`=:last_week LIMIT 1;');
            $sth->execute([
                'u_id' => $user['id'],
                'last_week' => ($week - 1)      // 看上上週的補救是否成功
            ]);
            $save_success = ($sth->fetchAll(PDO::FETCH_ASSOC))[0];
            
            foreach ($save_success as $is_save_category => $is_save) {
                echo '<h3><strong>' . APP_CATEGORY_INFORMATION[str_replace('is_save_', '', $is_save_category)]['zh'] . '</strong>使用時間搶救目標&emsp;&emsp;&emsp;' . ((boolval($is_save)) ? '<strong class="text-danger">達標！</strong>' : '<strong>未達標</strong>') . '</h3>';
            }
        }
    }

    echo '<br><p>請回到首頁，設定下週減量目標和檢查今天使用手機時間與排名！</p>
    <button type="button" class="btn btn-primary" onclick=location.href="redirect.php?u_id='.$user['id'].'">
    返回主選單
    </button>
    </div>';

    // v7_1新增
    echo '<div class="d-grid pt-5 pb-3 px-2"><h2>取得的成就拼圖：</h2>';
    $sth = $dbh->prepare('SELECT ' . implode(',', $category_goal_srting_array) . ' FROM `goal` WHERE `u_id` = :u_id;');
    $sth->execute(['u_id' => $user['id']]);
    $category_goal_result = $sth->fetch(PDO::FETCH_ASSOC);
    foreach (APP_CATEGORY as $category) {
        if (boolval($result[0]["reach_goal_{$category}"])) {
            echo '<img class="img-fluid" src="img/goal_' . $category . '_' . $category_goal_result["goal_{$category}"] . '.jpg"><br>';
        }
    }
    echo '</div>';
}

?>