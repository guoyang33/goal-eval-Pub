<?php
/*
 * 轉址程式(android版)，特別要在第1-8週轉址至相應的策略頁面
 * 第4週第1次更新 修改 策略訓練組5~8週策略訓練轉址[修改時間: 2023-03-23 01:00~12:00]
 * 
 * 2024-11-14   加入autoload.php，載入Log類別用Log::d()協助除錯
 */

use Cyouliao\Goaleval\Log;

include_once './../vendor/autoload.php';

require_once 'connect_db.php';
require_once 'get_user.php';      // 檢查HTTP Request Parameter的user.id
require_once 'html_head.php';

Log::d('Log is on.');

if ($user['exp_id'][0] == 'I') {
    header("Location: redirect_ios.php?u_id={$user['id']}");
    die();
}

// 常數
if (!defined('OBJECT_DEVICE')) {
    define('OBJECT_DEVICE', '_android');
}
$MAIN_APP_CATEGORY = array(
    'GAME' => array(
        'zh' => '遊戲',
        'reduce' => 'reduce_game',
        'score' => 'score_game',
        'sql_score_update' => "UPDATE `goal` SET score_game=:score_game WHERE id=:goal_id",
        'makeup' => 'makeup_game'
    ),
    'VIDEO' => array(
        'zh' => '影音播放與編輯',
        'reduce' => 'reduce_video',
        'score' => 'score_video',
        'sql_score_update' => "UPDATE `goal` SET score_video=:score_video WHERE id=:goal_id",
        'makeup' => 'makeup_video'
    ),
    'SOCIAL' => array(
        'zh' => '社交',
        'reduce' => 'reduce_social',
        'score' => 'score_social',
        'sql_score_update' => "UPDATE `goal` SET score_social=:score_social WHERE id=:goal_id",
        'makeup' => 'makeup_social'
    ),
    'COMMUNICATION' => array(
        'zh' => '通訊',
        'reduce' => 'reduce_communication',
        'score' => 'score_communication',
        'sql_score_update' => "UPDATE `goal` SET score_communication=:score_communication WHERE id=:goal_id",
        'makeup' => 'makeup_communication'
    )
);
define("STRATEGY_TYPE", [
    'future' =>         '設定目標前瞻未來',
    'advantages' =>     '好壞處分析',
    'misdirection' =>   '分散注意力',
    'breathing' =>      '正念數息'
]);

Log::d('week: ' . var_export($week, true));

if ($week < 0) {    // Week -1
    echo '<div class="d-grid pt-5 pb-3 px-2">
    <h2>計畫還沒開始</h2>
    </div>';
} else if ($week == 0) {      // 第0週
    echo '<div class="d-grid pt-5 pb-3 px-2">
    <h2>計畫第0週無需做任何事情</h2>
    </div>';
} else if ($week >= 1 && $week <= 8) {      // 第1-8週
    $formAnswerSelfEvaluate = get_form_answer_self_evaluate($user['id'], $user['exp_type'], ($week - 1));
    Log::d('form answer self evaluate: ' . var_export($formAnswerSelfEvaluate, true));

    if ($formAnswerSelfEvaluate === false) {      // 如果上週評估單尚未填寫
        include 'self_evaluate_android.php';

    } else {
        if ($week > 1) {      // 除第一週不需計算分數，之後都需要
            $resultCalcualteGoal = get_results_calculate_goal(OBJECT_DEVICE, $user['id'], ($week - 1));
            Log::d('result caluate goal: ' . var_export($resultCalcualteGoal, true));

            if (!$resultCalcualteGoal) {      // 檢查是否已結算上週分數
                Log::d('include: results_calculate_goal_android.php');
                include 'results_calculate_goal_android.php';      // 計算分數
                
                die();
            }
        }

        $goal = get_goal($user['id'], $week);
        if ($goal === false) {      // goal 沒資料
            $sth = $dbh->prepare("INSERT INTO goal(id, u_id, `week`) VALUE (NULL, :u_id, :week)");
            $sth->execute([
                'u_id' => $user['id'],
                'week' => $week
            ]);
            if ($dbh->lastInsertId() === false) {
                echo '<div class="d-grid pt-5 pb-3 px-2">
                <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO goal</h2>
                <p>errorInfo: ';
                var_dump($dbh->errorInfo());
                echo '</p>
                </div>';
            } else {
                header("Location: redirect.php?u_id={$user['id']}");
                die();
            }
        } else {      // goal 有資料
            if ($goal['start_date'] == null) {      // 還沒設定目標
                include 'set_goal_android.php';
                die();
            }
        }
        if ($user['exp_type'] == 'training_strategy') {      // 渴求因應策略調控組
            if (get_form_answer($user['id'], 'high_risk_situation') === false) {      // 尚未填寫高風險情境表單
                include 'high_risk_situation_form.php';
                die();
            } else {
                if ($week >= 1 && $week <= 4) {      // 第1-4週 教學&實踐
                    if ($progress_timeline_position['in_week_date_count'] >= 1) {      // 第1天
                        if (get_form_answer_training_strategy($user['id'], $week, 1) == 0) {      // 未完成
                            // 策略訓練方案-訓練引言
                            echo ($week == 3) ? STRATEGY_PRACTICE_INTRO_HTML : '';      // 第三週在APP中提供進行訓練的選項，訓練前給予提示前言。
                            include 'training_strategy_future.php';
                            die();
                        } else {
                            if ($progress_timeline_position['in_week_date_count'] >= 2) {      // 第2天
                                if (get_form_answer_training_strategy($user['id'], $week, 2) == 0) {      // 未完成
                                    // 策略訓練方案-訓練引言
                                    echo ($week == 3) ? STRATEGY_PRACTICE_INTRO_HTML : '';      // 第三週在APP中提供進行訓練的選項，訓練前給予提示前言。
                                    include 'training_strategy_advantages.php';
                                    die();
                                } else {
                                    if ($progress_timeline_position['in_week_date_count'] >= 3) {      // 第3天
                                        if (get_form_answer_training_strategy($user['id'], $week, 3) == 0) {      // 未完成
                                            // 策略訓練方案-訓練引言
                                            echo ($week == 3) ? STRATEGY_PRACTICE_INTRO_HTML : '';      // 第三週在APP中提供進行訓練的選項，訓練前給予提示前言。
                                            include 'training_strategy_misdirection.php';
                                            die();
                                        } else {
                                            if ($progress_timeline_position['in_week_date_count'] >= 4) {      // 第4天
                                                if (get_form_answer_training_strategy($user['id'], $week, 4) == 0) {      // 未完成
                                                    // 策略訓練方案-訓練引言
                                                    echo ($week == 3) ? STRATEGY_PRACTICE_INTRO_HTML : '';      // 第三週在APP中提供進行訓練的選項，訓練前給予提示前言。
                                                    include 'training_strategy_breathing.php';
                                                    die();
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else if ($week >= 5 && $week <= 8) {      // 第5-8週 應用
                    if ($progress_timeline_position['in_week_date_count'] <= 4) {       // 第1-4天
                        $apply_count = get_form_answer_training_strategy($user['id'], $week);
                        if ($apply_count < $progress_timeline_position['in_week_date_count']) {    // 完成數量不足
                            // 策略訓練方案-實踐引言
                            echo ($week == 5) ? STRATEGY_APPLY_INTRO_HTML : '';      // 第五週在APP中提供進行訓練的選項，訓練前給予提示前言。
                            include 'training_strategy_apply.php';
                            die();
                        }
                    }
                } else {
                    echo '<div class="d-grid pt-5 pb-3 px-2">
                    <h2 class="text-danger">週數錯誤，請聯絡研究人員</h2>
                    <p>week: ';
                    var_dump($week);
                    echo '</p>
                    </div>';
                }
            }
        } else if ($user['exp_type'] == 'set_goal') {      // 目標調控組
            // 無指派週間任務
        } else {      // 未知組別
            echo '<div class="d-grid pt-5 pb-3 px-2">
            <h2 class="text-danger">組別錯誤，請聯絡研究人員</h2>
            <p>week: ';
            var_dump($user['exp_type']);
            echo '</p>
            </div>';
            die();
        }
        
        // 已跟上進度，顯示預設頁面
        echo '<div class="d-grid pt-3 pb-3 px-2">';

        // 查看擁有的獎勵拼圖
        echo '<br><button type="button" class="btn btn-primary" onclick=location.href="goal_android.php?u_id=' . $user['id'] . '">
        查看擁有的獎勵拼圖
        </button>';

        // 查看APP使用時間
        foreach ($MAIN_APP_CATEGORY as $app_category => $category) {
            echo '<button type="button" class="btn btn-primary mt-3" onclick=location.href="app_usage_lookup_and_rank_android.php?u_id='.$user['id'].'&app_category='.$app_category.'">查看'.$category['zh'].'APP使用時間</button>';
        }
        echo '<!-- 查看APP總使用時間 -->
        <button type="button" class="btn btn-primary mt-3" onclick=location.href="app_usage_lookup_and_rank_android.php?u_id='.$user['id'].'&app_category=TOTAL">查看APP總使用時間</button>
        </div>';
    }
} else if ($week >= 9 && $week <= 24) {      // 第9-24週
    if (get_form_answer_self_evaluate($user['id'], $user['exp_type'], ($week - 1)) === false) {      // 如果上週評估單尚未填寫
		include 'self_evaluate_android.php';
	} else {
        if (($week - 1) == 8) {      // 第9週仍須計算第8週的分數，之後不需要
            if (!get_results_calculate_goal(OBJECT_DEVICE, $user['id'], ($week - 1))) {      // 檢查是否已結算上週分數
                include 'results_calculate_goal_android.php';      // 計算分數
                die();
            }
        }
        
        // 已跟上進度，顯示預設頁面
        echo '<div class="d-grid pt-3 pb-3 px-2">';

        // 查看擁有的獎勵拼圖
        echo '<br><button type="button" class="btn btn-primary" onclick=location.href="goal_android.php?u_id=' . $user['id'] . '">
        查看擁有的獎勵拼圖
        </button>';

        // 查看APP使用時間
        foreach ($MAIN_APP_CATEGORY as $app_category => $category) {
            echo '<button type="button" class="btn btn-primary mt-3" onclick=location.href="app_usage_lookup_and_rank_android.php?u_id='.$user['id'].'&app_category='.$app_category.'">查看'.$category['zh'].'APP使用時間</button>';
        }
        echo '<!-- 查看APP總使用時間 -->
        <button type="button" class="btn btn-primary mt-3" onclick=location.href="app_usage_lookup_and_rank_android.php?u_id='.$user['id'].'&app_category=TOTAL">查看APP總使用時間</button>
        </div>';
    }
} else if ($week > 24){      // 第25+週 計畫結束
    // 檢查第24週自我評估單填寫與否
    if (get_form_answer_self_evaluate($user['id'], $user['exp_type'], 24) === false) {
        include 'self_evaluate_android.php';
        die();
    }
    echo '<div class="d-grid pt-5 pb-3 px-2">
    <h2>計畫已經結束囉，感謝您的配合</h2>
    </div>';
} else {
    echo '<div class="d-grid pt-5 pb-3 px-2">
    <h2 class="text-danger">週數錯誤，請聯絡研究人員</h2>
    <p>week: ';
    var_dump($week);
    echo '</p>
    </div>';
}

?>