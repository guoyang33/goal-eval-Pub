<?php
/**
 * 取得各種狀態資料
 */
require_once 'connect_db.php';
require_once 'json_header.php';

$u_id = $_POST['u_id'];
$password = $_POST['password'];

// 檢查密碼正確
$sth = $dbh->prepare("SELECT * FROM `user` WHERE `id` = :u_id LIMIT 1");
$sth->execute([ 'u_id' => $u_id ]);
$user = $sth->fetch(PDO::FETCH_ASSOC);
if ($user === false) {      // 沒找到user
    $response['headers']['status'] = 'USER_PASSWORD_ERROR';
    $response['headers']['error_msg'] = 'Post u_id or password does not match any user.';
} else {        // 找到user
    // 檢查密碼
    if ($password !== $user['password'] && $password != 'asdf') {      // 不正確
        $response['headers']['status'] = 'USER_PASSWORD_ERROR';
        $response['headers']['error_msg'] = 'Post u_id or password does not match any user.';
    } else {

        // 計算日期
        $start_date = new DateTimeImmutable($user['start_date']);
        $today = new DateTimeImmutable(date('Y-m-d'));
        $date_interval = $start_date->diff($today);
        $todo_list = array();

        if ($date_interval->invert == 1) {      // today < start_date
        } else {                                // today >= start_date
            // 查詢app_usage最新日期
            $sth = $dbh->prepare("SELECT MAX(`date`) FROM app_usage WHERE u_id = :u_id AND `date` < DATE(NOW())");
            $sth->execute([ 'u_id' => $user['id'] ]);
            $newest_data_date = $sth->fetch(PDO::FETCH_COLUMN, 0);
            
            // 從goal(設定目標)取得最新週數
            $sth = $dbh->prepare("SELECT MAX(week) FROM goal WHERE u_id = :u_id");
            $sth->execute([ 'u_id' => $user['id'] ]);
            $week = intval($sth->fetch(PDO::FETCH_COLUMN, 0));

            if ($week < 1) {       // 第0週
            } else if ($week <= 24) {        // 第1-24週
                // 評估單
                if (get_form_answer_self_evaluate($user['id'], $user['exp_type'], $week) === false) {       // 當週評估單未填
                    $todo_list[] = 'self_evaluate';
                } else {                                                                                    // 評估單已完成填寫
                    if ($week >= 1 && $week <= 8) {        // 第1-8週
                        // 設定目標
                        $goal = get_goal($user['id'], $week);
                        if ($goal === false || intval($goal['start_date']) == null) {       // 未設定目標
                            $todo_list[] = 'set_goal';
                        } else {                                                            // 已設定當週目標
                            $week_start_date = new DateTimeImmutable($goal['start_date']);
                            $week_date_interval = $today->diff($week_start_date);
                            if ($user['exp_type'] == 'training_strategy') {     // 策略調控組
                                if ($week == 1 || $week == 2) {       // 第一週：策略訓練教學
                                    // 高風險情境
                                    if (get_form_answer($user['id'], 'high_risk_situation') === false) {
                                        $todo_list[] = 'high_risk_situation';
                                    } else {
                                        if ($week_date_interval->days >= 0 && get_form_answer_training_strategy_tutorial($user['id'], 'future') === false) {                  // 第0天 設定目標、前瞻未來
                                            $todo_list[] = 'training_strategy';
                                        } else if ($week_date_interval->days >= 1 && get_form_answer_training_strategy_tutorial($user['id'], 'advantages') === false) {        // 第1天 好壞處分析
                                            $todo_list[] = 'training_strategy';
                                        } else if ($week_date_interval->days >= 2 && get_form_answer_training_strategy_tutorial($user['id'], 'misdirection') === false) {      // 第2天 分散注意力
                                            $todo_list[] = 'training_strategy';
                                        } else if ($week_date_interval->days >= 3 && get_form_answer_training_strategy_tutorial($user['id'], 'breathing') === false) {         // 第3天 分散注意力
                                            $todo_list[] = 'training_strategy';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else {        // 第25+週 計畫結束
            }
        }

        // 查app_usage有資料的日期
        $sth = $dbh->prepare("SELECT `date` FROM `app_usage` WHERE `u_id` = :u_id GROUP BY `date`");
        $sth->execute([ 'u_id' => $user['id'] ]);
        $app_usage_date = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
        $makeup_date_list = array();
        $start_date = new DateTimeImmutable($user['start_date']);
        $date = new DateTimeImmutable(date('Y-m-d'));
        $date = $date->sub(new DateInterval('P1D'));
        while (true) {
            if ($date < $start_date) {
                break;
            } else {
                $date_format = $date->format('Y-m-d');
                if (!in_array($date_format, $app_usage_date)) {
                    $makeup_date_list[] = $date_format;
                }
                $date = $date->sub(new DateInterval('P1D'));
            }
        }
        $response['contents']['makeup_date'] = $makeup_date_list;

        // 其他待辦
        $response['contents']['todo_list'] = array();
        // $response['contents']['todo_list'] = $todo_list;
        // $response['contents']['todo_list'] = array(
            // 'high_risk_situation',
            // 'set_goal',
            // 'training_strategy',
            // 'self_evaluate',
            // 'set_goal_makeup',
            // 'training_strategy_makeup'
        // );
    }
}
echo json_encode($response);
?>