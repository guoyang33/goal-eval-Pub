<?php

require_once 'get_user.php';
require_once 'connect_db.php';
require_once 'html_head.php';

$form_name = $_POST['form_name'];
$answer_sheet = $_POST['answer_sheet'];

// FORM_NAME['training_strategy']['future'][1];

$ENCOURAGE_SLOGAN = array(
    FORM_NAME['training_strategy']['future'][1] => array(
        '恭喜您完成自我目標設定，朝向目標勇往直前吧！'
    ),
    FORM_NAME['training_strategy']['future'][2] => array(
        '恭喜您完成自我目標設定，朝向目標勇往直前吧！'
    ),
    FORM_NAME['training_strategy']['advantages'][1] => array(
        '您很用心思考怎樣對自己才是好的，很不容易！',
        '您能了解自己，知道自己需要什麼，很棒！',
        '您越來越會想後果，並且學會克制自己！'
    ),
    FORM_NAME['training_strategy']['advantages'][2] => array(
        '您很用心思考怎樣對自己才是好的，很不容易！',
        '您能了解自己，知道自己需要什麼，很棒！',
        '您越來越會想後果，並且學會克制自己！'
    ),
    FORM_NAME['training_strategy']['misdirection'][1] => array(
        '哇! 您好有執行力，真不簡單！',
        '酷欸! 您行動力好強，真有魄力！',
        '您找到方法，克服了玩遊戲的渴求！'
    ),
    FORM_NAME['training_strategy']['misdirection'][2] => array(
        '哇! 您好有執行力，真不簡單！',
        '酷欸! 您行動力好強，真有魄力！',
        '您找到方法，克服了玩遊戲的渴求！'
    )
);

$form = get_form($form_name);
if ($form === false) {
    echo '<div class="d-grid pt-5 pb-3 px-2">
    <h2 class="text-danger">表單名稱錯誤，請聯絡研究人員</h2>
    <p>form_name: ';
    var_dump($form_name);
    echo '</p>
    </div>';
} else {
    // 檢查是否重複
    $sth = $dbh->prepare("SELECT * FROM form_answer WHERE u_id = :u_id AND week = :week AND f_id = :f_id LIMIT 1");
    if (in_array($form_name, FORM_NAME['training_strategy']['breathing'])) {      // 如果是正念數息因為是一週中最後一個進度，可能因此跨週，所以要根據條件與否來檢查上週或這週的
        if (($progress_timeline_position['in_week_date_count'] - 4) == -3) {
            $sth->execute(['u_id' => $user['id'], 'week' => ($week - 1), 'f_id' => $form['id']]);
        } else {
            $sth->execute(['u_id' => $user['id'], 'week' => $week, 'f_id' => $form['id']]);
        }
    } else {
        $sth->execute(['u_id' => $user['id'], 'week' => $week, 'f_id' => $form['id']]);
    }
    $form_answer = $sth->fetch(PDO::FETCH_ASSOC);

    if (boolval($form_answer)) {
        echo '<h1><strong class="text-danger">警告！請勿重複提交</strong></h1>';
    } else {
        $makeup = 0;
        // 需要再優化該部分
        // $goal = get_goal($user['id'], $week);
        // 取得今天天數
        // $week_start_date = new DateTimeImmutable($goal['start_date']);
        // $week_date_interval = $week_start_date->diff($today);
        if (in_array($form_name, FORM_NAME['training_strategy']['future'])) {
            if ($progress_timeline_position['in_week_date_count'] > 0) {        // 超過第0天
                $makeup = 1;
            }
        } else if (in_array($form_name, FORM_NAME['training_strategy']['advantages'])) {
            if ($progress_timeline_position['in_week_date_count'] > 1) {        // 超過第1天
                $makeup = 1;
            }
        } else if (in_array($form_name, FORM_NAME['training_strategy']['misdirection'])) {
            if ($progress_timeline_position['in_week_date_count'] > 2) {        // 超過第1天
                $makeup = 1;
            }
        } else if (in_array($form_name, FORM_NAME['training_strategy']['breathing'])) {
            if ($progress_timeline_position['in_week_date_count'] > 3) {        // 超過第1天
                $makeup = 1;
            }
        }

        // 新增form_answer資料
        $sth = $dbh->prepare("INSERT INTO `form_answer` (id, `f_id`, `u_id`, `answer_sheet`, `week`, `date`, makeup) VALUES (NULL, :f_id, :u_id, :answer_sheet, :week, :today, :makeup);");
        $sth->execute([
            'f_id' => $form['id'],
            'u_id' => $user['id'],
            'answer_sheet' => json_encode($answer_sheet),
            'week' => $week,
            'today' => $today->format('Y-m-d'),
            'makeup' => $makeup
        ]);
        if ($dbh->lastInsertId() === false) {
            echo '<div class="d-grid pt-5 pb-3 px-2">
            <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO form_answer</h2>
            <p>errorInfo: ';
            var_dump($dbh->errorInfo());
            echo '</p>
            </div>';
        } else {
            // 新增進度(有效天數)
            $insert_valid_days_status = insert_provide_valid_days($user['id'], PROVIDE_VALID_DAYS[$user['exp_type']][$form_name], basename(__FILE__), $form_name, $week);
            if ($insert_valid_days_status['error']) {
                echo '<div class="d-grid pt-5 pb-3 px-2">
                    <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO valid_days</h2>
                    <p>errorInfo: ';
                    var_dump($insert_valid_days_status['content']);
                    echo '</p>
                    </div>';
                exit();
            }
            
            echo '<div class="d-grid pt-5 px-2">
            <p>提交成功</p>';
            // 顯示鼓勵小語
            if (key_exists($form_name, $ENCOURAGE_SLOGAN)) {
                echo '<p class="h3">'.$ENCOURAGE_SLOGAN[$form_name][random_int(0, count($ENCOURAGE_SLOGAN[$form_name])-1)].'</p>';
            }
            // 獎勵拼圖
            if ($week >= 1 && $week <= 4) {
                /*
                if ($makeup == 1) {
                    echo '<img class="img-fluid" src="img/training_strategy_finish_makeup.png">';
                } else {}
                */
                // 查詢form_answer本週已完成的策略
                $sql = "SELECT COUNT(*) FROM form_answer WHERE u_id=:u_id AND `week`=:week AND makeup=1";
                $sth = $dbh->prepare($sql);
                $sth->execute([
                    'u_id' => $user['id'],
                    'week' => $week
                ]);
                $form_answer_makeup_count = intval($sth->fetch(PDO::FETCH_COLUMN, 0));
                if (in_array($form_name, FORM_NAME['training_strategy']['future'])) {
                    $puzzle_id = $form_answer_makeup_count;
                } else if (in_array($form_name, FORM_NAME['training_strategy']['advantages'])) {
                    $puzzle_id = $form_answer_makeup_count;
                } else if (in_array($form_name, FORM_NAME['training_strategy']['misdirection'])) {
                    $puzzle_id = $form_answer_makeup_count;
                } else if (in_array($form_name, FORM_NAME['training_strategy']['breathing'])) {
                    $puzzle_id = $form_answer_makeup_count;
                } else {
                    echo '<div class="d-grid pt-5 pb-3 px-2">
                    <h2 class="text-danger">表單名稱錯誤，請聯絡研究人員</h2>
                    <p>form_name: ';
                    var_dump($form_name);
                    echo '</p>
                    </div>';
                    die();
                }
                echo '<img class="img-fluid" src="img/training_strategy_finish_'.$puzzle_id.'.png">';
            }
        }
    }
}


if ($user['is_ios'] == 1) {
    echo '<div class="d-grid pt-5 pb-3 px-2">
        <button type="button" class="btn btn-secondary" onclick=location.href="redirect_ios.php?u_id='.$user['id'].'">
            返回主選單
        </button>
    </div>';
} else {
    echo '<div class="d-grid pt-5 pb-3 px-2">
        <button type="button" class="btn btn-secondary" onclick=location.href="redirect.php?u_id='.$user['id'].'">
            返回主選單
        </button>
    </div>';
}
?>