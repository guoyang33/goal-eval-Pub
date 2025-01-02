<?php

// require_once 'MyPDO.php';

// 以下是 PDO 的宣告 $dbh 就是 資料庫 的database handle

$driver = 'mysql';
$host = 'localhost';
$port = ';port=3306';
$dbname = 'qsteense_goaleval';
$dsn = "{$driver}:host={$host}{$port};dbname={$dbname}";

$username = 'qsteense_goaleval_script';
$password = 'x1ycl_j7aN0Ck[kV';
$dbh = new PDO($dsn, $username, $password);

$dbh->exec("SET CHARACTER SET 'UTF8';");
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 常數
define('YEAR_NO', '113');
define('DEFAULT_START_DATE', '2024-10-21'); // 實際是 2024-10-22 但是要減一天
define('FORM_NAME', array(
    'self_evaluate' => array(
        'android' => array(
            'set_goal' => array(
                0 => 'self_evaluate_android_set_goal_0',
                1 => 'self_evaluate_android_set_goal_1-8',
                2 => 'self_evaluate_android_set_goal_1-8',
                3 => 'self_evaluate_android_set_goal_1-8',
                4 => 'self_evaluate_android_set_goal_1-8',
                5 => 'self_evaluate_android_set_goal_1-8',
                6 => 'self_evaluate_android_set_goal_1-8',
                7 => 'self_evaluate_android_set_goal_1-8',
                8 => 'self_evaluate_android_set_goal_1-8',
                9 => 'self_evaluate_android_set_goal_9-24',
                10 => 'self_evaluate_android_set_goal_9-24',
                11 => 'self_evaluate_android_set_goal_9-24',
                12 => 'self_evaluate_android_set_goal_9-24',
                13 => 'self_evaluate_android_set_goal_9-24',
                14 => 'self_evaluate_android_set_goal_9-24',
                15 => 'self_evaluate_android_set_goal_9-24',
                16 => 'self_evaluate_android_set_goal_9-24',
                17 => 'self_evaluate_android_set_goal_9-24',
                18 => 'self_evaluate_android_set_goal_9-24',
                19 => 'self_evaluate_android_set_goal_9-24',
                20 => 'self_evaluate_android_set_goal_9-24',
                21 => 'self_evaluate_android_set_goal_9-24',
                22 => 'self_evaluate_android_set_goal_9-24',
                23 => 'self_evaluate_android_set_goal_9-24',
                24 => 'self_evaluate_android_set_goal_9-24',
            ),
            'training_strategy' => array(
                0 => 'self_evaluate_android_training_strategy_0',
                1 => 'self_evaluate_android_training_strategy_1-8',
                2 => 'self_evaluate_android_training_strategy_1-8',
                3 => 'self_evaluate_android_training_strategy_1-8',
                4 => 'self_evaluate_android_training_strategy_1-8',
                5 => 'self_evaluate_android_training_strategy_1-8',
                6 => 'self_evaluate_android_training_strategy_1-8',
                7 => 'self_evaluate_android_training_strategy_1-8',
                8 => 'self_evaluate_android_training_strategy_1-8',
                9 => 'self_evaluate_android_training_strategy_9-24',
                10 => 'self_evaluate_android_training_strategy_9-24',
                11 => 'self_evaluate_android_training_strategy_9-24',
                12 => 'self_evaluate_android_training_strategy_9-24',
                13 => 'self_evaluate_android_training_strategy_9-24',
                14 => 'self_evaluate_android_training_strategy_9-24',
                15 => 'self_evaluate_android_training_strategy_9-24',
                16 => 'self_evaluate_android_training_strategy_9-24',
                17 => 'self_evaluate_android_training_strategy_9-24',
                18 => 'self_evaluate_android_training_strategy_9-24',
                19 => 'self_evaluate_android_training_strategy_9-24',
                20 => 'self_evaluate_android_training_strategy_9-24',
                21 => 'self_evaluate_android_training_strategy_9-24',
                22 => 'self_evaluate_android_training_strategy_9-24',
                23 => 'self_evaluate_android_training_strategy_9-24',
                24 => 'self_evaluate_android_training_strategy_9-24',
            ),
        ),
        'ios' => array(
            'set_goal' => array(
                0 => 'self_evaluate_ios_set_goal_0',
                1 => 'self_evaluate_ios_set_goal_1-8',
                2 => 'self_evaluate_ios_set_goal_1-8',
                3 => 'self_evaluate_ios_set_goal_1-8',
                4 => 'self_evaluate_ios_set_goal_1-8',
                5 => 'self_evaluate_ios_set_goal_1-8',
                6 => 'self_evaluate_ios_set_goal_1-8',
                7 => 'self_evaluate_ios_set_goal_1-8',
                8 => 'self_evaluate_ios_set_goal_1-8',
                9 => 'self_evaluate_ios_set_goal_9-24',
                10 => 'self_evaluate_ios_set_goal_9-24',
                11 => 'self_evaluate_ios_set_goal_9-24',
                12 => 'self_evaluate_ios_set_goal_9-24',
                13 => 'self_evaluate_ios_set_goal_9-24',
                14 => 'self_evaluate_ios_set_goal_9-24',
                15 => 'self_evaluate_ios_set_goal_9-24',
                16 => 'self_evaluate_ios_set_goal_9-24',
                17 => 'self_evaluate_ios_set_goal_9-24',
                18 => 'self_evaluate_ios_set_goal_9-24',
                19 => 'self_evaluate_ios_set_goal_9-24',
                20 => 'self_evaluate_ios_set_goal_9-24',
                21 => 'self_evaluate_ios_set_goal_9-24',
                22 => 'self_evaluate_ios_set_goal_9-24',
                23 => 'self_evaluate_ios_set_goal_9-24',
                24 => 'self_evaluate_ios_set_goal_9-24',
            ),
            'training_strategy' => array(
                0 => 'self_evaluate_ios_training_strategy_0',
                1 => 'self_evaluate_ios_training_strategy_1-8',
                2 => 'self_evaluate_ios_training_strategy_1-8',
                3 => 'self_evaluate_ios_training_strategy_1-8',
                4 => 'self_evaluate_ios_training_strategy_1-8',
                5 => 'self_evaluate_ios_training_strategy_1-8',
                6 => 'self_evaluate_ios_training_strategy_1-8',
                7 => 'self_evaluate_ios_training_strategy_1-8',
                8 => 'self_evaluate_ios_training_strategy_1-8',
                9 => 'self_evaluate_ios_training_strategy_9-24',
                10 => 'self_evaluate_ios_training_strategy_9-24',
                11 => 'self_evaluate_ios_training_strategy_9-24',
                12 => 'self_evaluate_ios_training_strategy_9-24',
                13 => 'self_evaluate_ios_training_strategy_9-24',
                14 => 'self_evaluate_ios_training_strategy_9-24',
                15 => 'self_evaluate_ios_training_strategy_9-24',
                16 => 'self_evaluate_ios_training_strategy_9-24',
                17 => 'self_evaluate_ios_training_strategy_9-24',
                18 => 'self_evaluate_ios_training_strategy_9-24',
                19 => 'self_evaluate_ios_training_strategy_9-24',
                20 => 'self_evaluate_ios_training_strategy_9-24',
                21 => 'self_evaluate_ios_training_strategy_9-24',
                22 => 'self_evaluate_ios_training_strategy_9-24',
                23 => 'self_evaluate_ios_training_strategy_9-24',
                24 => 'self_evaluate_ios_training_strategy_9-24',
            ),
        ),
    ),
    'training_strategy' => array(
        'future' => array(
            1 => 'training_strategy_future_tutorial_1',
            2 => 'training_strategy_future_tutorial_2',
            3 => 'training_strategy_future_practice',
            4 => 'training_strategy_future_practice',
        ),
        'advantages' => array(
            1 => 'training_strategy_advantages_tutorial_1',
            2 => 'training_strategy_advantages_tutorial_2',
            3 => 'training_strategy_advantages_practice',
            4 => 'training_strategy_advantages_practice',
        ),
        'misdirection' => array(
            1 => 'training_strategy_misdirection_tutorial_1',
            2 => 'training_strategy_misdirection_tutorial_2',
            3 => 'training_strategy_misdirection_practice',
            4 => 'training_strategy_misdirection_practice',
        ),
        'breathing' => array(
            1 => 'training_strategy_breathing_tutorial_1',
            2 => 'training_strategy_breathing_tutorial_2',
            3 => 'training_strategy_breathing_practice',
            4 => 'training_strategy_breathing_practice',
        ),
        'apply' => array(
            5 => 'training_strategy_apply',
            6 => 'training_strategy_apply',
            7 => 'training_strategy_apply',
            8 => 'training_strategy_apply',
        ),
    ),
));

// 定義不同組別，填完表單等所提供的有效進度增加天數
define('PROVIDE_VALID_DAYS', array(
    'training_strategy' => array(
        'self_evaluate_android_training_strategy_0'      => 0,
        'self_evaluate_android_training_strategy_1-8'    => 0,
        'self_evaluate_android_training_strategy_9-24'   => 7,
        'self_evaluate_ios_training_strategy_0'          => 0,
        'self_evaluate_ios_training_strategy_1-8'        => 0,
        'self_evaluate_ios_training_strategy_9-24'       => 7,
        'set_goal_android'                               => 0,
        'set_goal_ios'                                   => 0,
        'high_risk_situation_form'                       => 0,
        'training_strategy_future_tutorial_1'            => 1,
        'training_strategy_future_tutorial_2'            => 1,
        'training_strategy_future_practice'              => 1,
        'training_strategy_advantages_tutorial_1'        => 1,
        'training_strategy_advantages_tutorial_2'        => 1,
        'training_strategy_advantages_practice'          => 1,
        'training_strategy_misdirection_tutorial_1'      => 1,
        'training_strategy_misdirection_tutorial_2'      => 1,
        'training_strategy_misdirection_practice'        => 1,
        'training_strategy_breathing_tutorial_1'         => 4,
        'training_strategy_breathing_tutorial_2'         => 4,
        'training_strategy_breathing_practice'           => 4,
        'training_strategy_apply' => array(      // 第幾次提交
            1 => 1,
            2 => 1,
            3 => 1,
            4 => 4
        )
    ),
    'set_goal' => array(
        'self_evaluate_android_set_goal_0'               => 0,
        'self_evaluate_android_set_goal_1-8'             => 0,
        'self_evaluate_android_set_goal_9-24'            => 7,
        'self_evaluate_ios_set_goal_0'                   => 0,
        'self_evaluate_ios_set_goal_1-8'                 => 0,
        'self_evaluate_ios_set_goal_9-24'                => 7,
        'set_goal_android'                               => 7,
        'set_goal_ios'                                   => 7,
    )
));

// 函數定義
// 檢查研究編號: 1:成癮組|2:非成癮組
function addiction_check($exp_id) {
    if ($exp_id[4] == 1) {
        return 1;
    } else {
        return 0;
    }
}

// 檢查研究編號: 1:策略調控組|2:目標調控組
function exp_type_check($exp_id) {
    if ($exp_id[5] == 1) {
        return 'training_strategy';
    } else {
        return 'set_goal';
    }
}

// 檢查研究編號格式: 通用
function exp_id_format_check($exp_id) {
    if (strlen($exp_id) != 8) {
        return false;
    } else {
        if (!in_array($exp_id[0], ['A', 'I'])) {    // 首字
            return false;
        } else {
            if (substr($exp_id, 1, 3) != YEAR_NO) {     // 年份
                return false;
            } else {
                if (!in_array($exp_id[4], [1,2])) {        // 1:成癮 | 2:非成癮
                    return false;
                } else {
                    if (!in_array($exp_id[5], [1,2])) {     // 1:目標對照組 | 2:策略調控組
                        return false;
                    } else {
                        if (!is_numeric(substr($exp_id, 6, 2))) {       // 流水號
                            return false;
                        } else {        
                            return true;
                        }
                    }
                }
            }
        }
    }
}

// 檢查研究編號格式 android版
function exp_id_format_check_android($exp_id) {
    if (!exp_id_format_check($exp_id)) {
        return false;
    } else {
        if ($exp_id[0] != 'A') {
            return false;
        } else {
            return true;
        }
    }
}

// 查詢策略訓練form_answer
// 將會數量查詢結果
function get_form_answer_training_strategy($u_id, $week, $step = null) {
    global $dbh;

    $form_name = null;
    if ($week >= 1 && $week <= 4) {
        if ($step == 1) {
            $form_name = FORM_NAME['training_strategy']['future'][$week];
        } else if ($step == 2) {
            $form_name = FORM_NAME['training_strategy']['advantages'][$week];
        } else if ($step == 3) {
            $form_name = FORM_NAME['training_strategy']['misdirection'][$week];
        } else if ($step == 4) {
            $form_name = FORM_NAME['training_strategy']['breathing'][$week];
        } else {
            echo '<div class="d-grid pt-3 px-5>
            <h2 class="text-danger">取得策略訓練表單名稱錯誤，請聯絡研究人員</h2>
            <p>step: ';
            var_dump($step);
            echo '</p>';
            die();
        }
    } else if ($week >= 5 && $week <= 8) {
        $form_name = FORM_NAME['training_strategy']['apply'][$week];
    } else {
        echo '<div class="d-grid pt-3 px-5>
        <h2 class="text-danger">取得策略訓練表單名稱錯誤(週數不正確)，請聯絡研究人員</h2>
        <p>week: ';
        var_dump($week);
        echo '</p>';
        die();
    }
    // if ($week == 1) {               // 第1週 教學1
    //     if ($step == 1) {               // 第1招 設定目標
    //         $form_name = 'training_strategy_future_tutorial_1';
    //     } else if ($step == 2) {        // 第2招 好壞處
    //         $form_name = 'training_strategy_advantages_tutorial_1';
    //     } else if ($step == 3) {        // 第3招 分散注意
    //         $form_name = 'training_strategy_misdirection_tutorial_1';
    //     } else if ($step == 4) {        // 第4招 正念數息
    //         $form_name = 'training_strategy_breathing_tutorial_1';
    //     }
    // } else if ($week == 2) {        // 第2週 教學2
    //     if ($step == 1) {               // 第1招 設定目標
    //         $form_name = 'training_strategy_future_tutorial_2';
    //     } else if ($step == 2) {        // 第2招 好壞處
    //         $form_name = 'training_strategy_advantages_tutorial_2';
    //     } else if ($step == 3) {        // 第3招 分散注意
    //         $form_name = 'training_strategy_misdirection_tutorial_2';
    //     } else if ($step == 4) {        // 第4招 正念數息
    //         $form_name = 'training_strategy_breathing_tutorial_2';
    //     }
    // } else if ($week >= 3 && $week <= 4) {        // 第3-4週 實踐
    //     if ($step == 1) {               // 第1招 設定目標
    //         $form_name = 'training_strategy_future_practice';
    //     } else if ($step == 2) {        // 第2招 好壞處
    //         $form_name = 'training_strategy_advantages_practice';
    //     } else if ($step == 3) {        // 第3招 分散注意
    //         $form_name = 'training_strategy_misdirection_practice';
    //     } else if ($step == 4) {        // 第4招 正念數息
    //         $form_name = 'training_strategy_breathing_practice';
    //     }
    // } else if ($week >= 5 && $week <= 8) {        // 第5-8週 應用
    //     $form_name = 'training_strategy_apply';
    // }

    if ($form_name == null) {
        return 0;
    } else {
        $sql = "SELECT
                    COUNT(*)
                FROM 
                    form_answer 
                WHERE 
                    u_id=:u_id 
                    AND 
                    f_id=(SELECT id FROM form WHERE `name`=:f_name LIMIT 1)
                    AND
                    `week`=:week";
        $sth = $dbh->prepare($sql);
        $sth->execute([
            'u_id' => $u_id,
            'f_name' => $form_name,
            'week' => $week
        ]);
        return intval($sth->fetch(PDO::FETCH_COLUMN, 0));
    }
}

// 檢查未完成事項
function check_todo($u_id, $week, $exp_type) {
    global $dbh;
    if ($week > 0) {
        // 上週自我評估單
        if (get_form_answer_self_evaluate($u_id, $exp_type, $week-1) === false) {       // 評估單未填
            return false;
        } else {
            // 設定目標
            if ($week <= 8) {
                if (get_goal($u_id, $week) === false) {     // 未設定目標
                    return false;
                } else {
                    if ($exp_type == 'training_strategy') {     // 策略調控組
                        // $training_strategy_list = get_training_strategy_list($u_id, $week);
                        if (!check_training_strategy($u_id, $week)) {       // 訓練次數不足
                            return false;
                        } else {
                            return true;
                        }
                    }
                }
            }
        }
    }
    return true;
}

// 取得該週的日期範圍
function get_week_date_range($u_id, $week, $start_date) {
    global $dbh;
    // 查詢 goal
    $sth = $dbh->prepare("SELECT * FROM goal WHERE u_id = :u_id AND week = :week LIMIT 1");
    $sth->execute([
        'u_id' => $u_id,
        'week' => $week
    ]);
    $goal = $sth->fetch(PDO::FETCH_ASSOC);
    
    if ($goal !== false && $goal['start_date'] != null) {       // goal 有資料

        // 查詢 week_adjust
        $sth = $dbh->prepare("SELECT SUM(days) as days FROM week_adjust WHERE u_id = :u_id AND week = :week");
        $sth->execute([
            'u_id' => $u_id,
            'week' => $week
        ]);
        $week_adjust_days = intval($sth->fetch(PDO::FETCH_COLUMN, 0));

        $week_start_date = new DateTimeImmutable($goal['start_date']);
        $week_days = 6 + $week_adjust_days;
        $week_end_date = $week_start_date->add(new DateInterval("P{$week_days}D"));

        $week_date_interval = $week_end_date->diff($week_start_date);

    } else {        //goal 沒資料
        
        $last_week_end_date = $start_date->sub(new DateInterval("P1D"));
        // 取得週數調整資料
        $week_adjust = array();
        $sth = $dbh->prepare("SELECT week, SUM(days) AS days FROM week_adjust WHERE u_id = :u_id AND week <= :week GROUP BY week");
        $sth->execute([
            'u_id' => $u_id,
            'week' => $week
        ]);
        foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $week_adjust[$row['week']] = intval($row['days']);
        }
        
        for ($i=0; $i<=$week; $i++) {
            if (key_exists($i-1, $week_adjust)) {       // i-1週調整
                $last_week_end_date = $last_week_end_date->add(new DateInterval("P{$week_adjust[$i-1]}D"));
            }
            $week_start_date = $last_week_end_date->add(new DateInterval("P1D"));
            $week_end_date = $week_start_date->add(new DateInterval("P6D"));
            $last_week_end_date = $week_end_date;
            
            if (key_exists($i, $week_adjust)) {     // 第i週調整
                $week_end_date = $week_end_date->add(new DateInterval("P{$week_adjust[$i]}D"));
            }
        }
        $week_date_interval = $week_end_date->diff($week_start_date);

    }
    return array(
        'week_start_date' => $week_start_date,
        'week_end_date' => $week_end_date,
        'week_date_interval' => $week_date_interval
    );
}

// 查詢 form
function get_form($form_name) {
    global $dbh;
    $sth = $dbh->prepare("SELECT * FROM `form` WHERE `name` = :form_name LIMIT 1;");
    $sth->execute([ 'form_name' => $form_name ]);
    $form = $sth->fetch(PDO::FETCH_ASSOC);
    return $form;
}

// 查詢 form_answer
function get_form_answer($u_id, $form_name) {
    global $dbh;
    $form = get_form($form_name);
    $sth = $dbh->prepare("SELECT * FROM `form_answer` WHERE `u_id` = :u_id AND `f_id` = :f_id LIMIT 1;");
    $sth->execute([
        'u_id' => $u_id,
        'f_id' => $form['id']
    ]);
    $form_answer = $sth->fetch(PDO::FETCH_ASSOC);
    return $form_answer;
}
function get_form_answer_by_id($fa_id) {
    global $dbh;
    $sth = $dbh->prepare("SELECT * FROM `form_answer` WHERE `id` = :fa_id LIMIT 1;");
    $sth->execute([
        'fa_id' => $fa_id
    ]);
    $form_answer = $sth->fetch(PDO::FETCH_ASSOC);
    return $form_answer;
}

// 查詢 goal
function get_goal($u_id, $week) {
    global $dbh;
    $sth = $dbh->prepare("SELECT * FROM `goal` WHERE `u_id` = :u_id AND `week` = :week LIMIT 1;");
    $sth->execute([
        'u_id' => $u_id,
        'week' => $week
    ]);
    $goal = $sth->fetch(PDO::FETCH_ASSOC);
    return $goal;
}

// 查詢 training_strategy
// 陣列 week <= week
// 格式 week => date_list(填寫日期) => [date1, date2,...]
//          => strategy_type_list => [type1, type2,...]
//          => count => INT(填寫數量)
function check_training_strategy($u_id, $week) {
    global $dbh;
    if ($week == 1) {        // 第1週 策略訓練教學
        $sth = $dbh->prepare("SELECT * FROM form_answer WHERE u_id = :u_id AND week = :week AND f_id in (2, 3, 4, 5) GROUP BY f_id");
        $sth->execute([
            'u_id' => $u_id,
            'week' => $week
        ]);
        if ($sth->rowCount() < 4) {
            return false;
        }
    }
    return true;
    
}

// 查詢策略訓練 教學記錄
function get_form_answer_training_strategy_tutorial($u_id, $strategy_type) {
    global $dbh;
    switch ($strategy_type) {
        case 'future':
            $form_name = 'training_strategy_tutorial_future';
            break;
        case 'advantages':
            $form_name = 'training_strategy_tutorial_advantages';
            break;
        case 'misdirection':
            $form_name = 'training_strategy_tutorial_misdirection';
            break;
        case 'breathing':
            $form_name = 'training_strategy_tutorial_breathing';
            break;
        default:
            echo 'strategy_type error: '.$strategy_type;
            die();
    }
    // $form = get_form($form_name);
    
    $sth = $dbh->prepare("SELECT * FROM form_answer WHERE u_id = :u_id AND f_id = (SELECT id FROM form WHERE `name` = :form_name) LIMIT 1");
    $sth->execute([
        'u_id' => $u_id,
        'form_name' => $form_name
    ]);
    $form_answer = $sth->fetch(PDO::FETCH_ASSOC);
    return $form_answer;
}

// 查詢自我評估單的填寫記錄 iOS版
function get_form_answer_self_evaluate_ios($u_id, $exp_type, $week) {
    global $dbh;

    // v7_1版本修改
    if (($week >= 0) && ($week <= 24)) {
        $form_name = FORM_NAME['self_evaluate']['ios'][$exp_type][$week];
    } else {
        echo 'get_form_answer_self_evaluate_ios error: week out of range 0-24';
        die();
    }

    /* v7_1版本修改
    if ($exp_type == 'set_goal') {      // 目標調控組
        if ($week == 0) {       // 第0週
            $form_name = 'self_evaluate_ios_set_goal_0';
        } else if ($week >= 1 && $week <=8) {        // 第1-8週
            $form_name = 'self_evaluate_ios_set_goal_1-8';
        } else if ($week >= 9 && $week <= 24) {        // 第9-24週
            $form_name = 'self_evaluate_ios_set_goal_9-24';
        } else {
            echo 'get_form_answer_self_evaluate_ios error: week out of range 0-24';
            die();
        }
    } else {        // 策略調控組
        if ($week == 0) {       // 第0週
            $form_name = 'self_evaluate_ios_training_strategy_0';
        } else if ($week >= 1 && $week <=8) {        // 第1-8週
            $form_name = 'self_evaluate_ios_training_strategy_1-8';
        } else if ($week >= 9 && $week <= 24) {        // 第9-24週
            $form_name = 'self_evaluate_ios_training_strategy_9-24';
        } else {
            echo 'get_form_answer_self_evaluate_ios error: week out of range 0-24';
            die();
        }
    }
    */

    // 先取得form
    $form = get_form($form_name);

    $sth = $dbh->prepare("SELECT * FROM `form_answer` WHERE `u_id` = :u_id AND `f_id` = :f_id AND `week` = :week LIMIT 1");
    $sth->execute([
        'u_id' => $u_id,
        'f_id' => $form['id'],
        'week' => $week
    ]);
    $form_answer = $sth->fetch(PDO::FETCH_ASSOC);
    return $form_answer;
}

// 查詢自我評估單的填寫記錄
function get_form_answer_self_evaluate($u_id, $exp_type, $week) {
    global $dbh;

    if (!key_exists($exp_type, FORM_NAME['self_evaluate']['android'])) {
        echo '<div class="d-grid pt-3 px-5>
        <h2 class="text-danger">取得策略訓練表單名稱錯誤(組別錯誤)，請聯絡研究人員</h2>
        <p>exp_type: ';
        var_dump($exp_type);
        echo '</p>';
        die();
    } else {
        if (!key_exists($week, FORM_NAME['self_evaluate']['android'][$exp_type])) {
            echo '<div class="d-grid pt-3 px-5>
            <h2 class="text-danger">取得策略訓練表單名稱錯誤(週數錯誤)，請聯絡研究人員</h2>
            <p>week: ';
            var_dump($week);
            echo '</p>';
            die();
        } else {
            $form_name = FORM_NAME['self_evaluate']['android'][$exp_type][$week];
    
            $sql = "SELECT
                        *
                    FROM
                        `form_answer`
                    WHERE
                        u_id=:u_id
                        AND
                        `week`=:week
                        AND
                        f_id=(SELECT id FROM form WHERE `name`=:form_name)
                    LIMIT
                        1";
            $sth = $dbh->prepare($sql);
            $sth->execute([
                'u_id' => $u_id,
                'form_name' => $form_name,
                'week' => $week
            ]);
            $form_answer = $sth->fetch(PDO::FETCH_ASSOC);
            return $form_answer;
        }
    }
    // $form_name = FORM_NAME['self_evaluate']['android'][$exp_type][$week];
    // if ($exp_type == 'training_strategy') {        // 策略調控組
    //     if ($week == 0) {       // 第0週
    //         $form_name = 'self_evaluate_android_set_goal_0';
    //     } else if ($week >= 1 && $week <= 8) {        // 第1-8週
    //         $form_name = 'self_evaluate_android_set_goal_1-8';
    //     } else if ($week >= 9 && $week <= 24) {        // 第9-24週
    //         $form_name = 'self_evaluate_android_set_goal_9-24';
    //     } else {
    //         return false;
    //     }
    // } else if ($exp_type == 'set_goal') {      // 目標調控組
    //     if ($week == 0) {       // 第0週
    //         $form_name = 'self_evaluate_android_training_strategy_0';
    //     } else if ($week >= 1 && $week <= 8) {        // 第1-8週
    //         $form_name = 'self_evaluate_android_training_strategy_1-8';
    //     } else if ($week >= 9 && $week <= 24) {        // 第9-24週
    //         $form_name = 'self_evaluate_android_training_strategy_9-24';
    //     } else {
    //         return false;
    //     }
    // } else {
    //     return false;
    // }
    // // 先取得form
    // $form = get_form($form_name);
    
    // $sth = $dbh->prepare("SELECT * FROM `form_answer` WHERE `u_id` = :u_id AND `f_id` = :f_id AND `week` = :week LIMIT 1");
    // $sth->execute([
    //     'u_id' => $u_id,
    //     'f_id' => $form['id'],
    //     'week' => $week
    // ]);
    // $form_answer = $sth->fetch(PDO::FETCH_ASSOC);
    // return $form_answer;
}

// 使用時間格式化: 秒數 => HH:MM:SS
function time_beautifier($seconds) {
    $beautiful_time =   str_pad(floor($seconds/3600), 2, '0', STR_PAD_LEFT).':'.
                        str_pad(floor($seconds/60)%60, 2, '0', STR_PAD_LEFT).':'.
                        str_pad($seconds%60, 2, '0', STR_PAD_LEFT);
                        return $beautiful_time;
}

// 產生長度 6 的英數亂碼，當密碼用
function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 6; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

// try
// {
// 	$dbh = new MyPDO();
// 	$dbh->exec("SET CHARACTER SET 'UTF8';");
// 	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// }
// catch (Exception $e)
// {
// 	echo '
// 		<center style="padding-top:15%;">
// 		<strong><font color="#FF0000" size="+1">未知錯誤發生，請聯絡系統管理員。</font></strong><br><br>
// 		請點 <a href="./index.php"><strong><font size="+1">這裡</font></strong></a> 以返回系統首頁。
// 	';
// 	die();
// }
// catch (PDOException $e)
// {
// 	echo '
// 		<center style="padding-top:15%;">
// 		<strong><font color="#FF0000" size="+1">資料庫連線失敗，請聯絡系統管理員。</font></strong><br><br>
// 		請點 <a href="./index.php"><strong><font size="+1">這裡</font></strong></a> 以返回系統首頁。
// 	';
// 	die();
// }

// 新增:用來增加有效天數，並寫入資料庫中
function insert_provide_valid_days($user_id, $provide_days, $source_file, $source_object, $week) {
    global $dbh;

    $sth = $dbh->prepare('INSERT INTO `valid_days`(`id`, `u_id`, `provide_days`, `source_file`, `source_object`, `week`, `date`) VALUES (NULL, :u_id, :provide_days, :source_file, :source_object, :week, :date);');
    $sth->execute([
        'u_id' => $user_id,
        'provide_days' => $provide_days,
        'source_file' => $source_file,
        'source_object' => $source_object,
        'week' => $week,
        'date' => date('Y-m-d')
    ]);
    if ($dbh->lastInsertId() === false) {
        return array('error' => true, 'content' => $dbh->errorInfo());
    } else {
        return array('error' => false, 'content' => 'success');
    }
}

// 檢查該週是否已結算過
function get_results_calculate_goal($object_device, $user_id, $week) {
    global $dbh;
    // object_device有_android跟_ios兩種
    $sql_string_template = array(
        '_android' => 'AND `score_game` IS NOT NULL AND `score_video` IS NOT NULL AND `score_social` IS NOT NULL AND `score_communication` IS NOT NULL AND `score_total` IS NOT NULL',
        '_ios' => 'AND `score_game` IS NOT NULL AND `score_social` IS NOT NULL AND `score_entertainment` IS NOT NULL AND `score_total` IS NOT NULL'
    );

    $sth = $dbh->prepare(sprintf('SELECT COUNT(*) AS `ckeck_goal_result` FROM `goal` WHERE `u_id`=:u_id AND `week`=:week %1$s;', $sql_string_template[$object_device]));
    $sth->execute([
        'u_id' => $user_id,
        'week' => $week
    ]);
    return boolval($sth->fetch(PDO::FETCH_COLUMN, 0));
}

?>