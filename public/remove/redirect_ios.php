<?php
/**
 * 轉址程式(iOS版)，特別要在第1-8週轉址至相應的策略頁面
 */
require_once 'connect_db.php';
require_once 'get_user.php';
require_once 'html_head.php';

if ($week < 0) {      // Week -1
    echo '<div class="d-grid pt-5 pb-3 px-2">
    <h2>計畫還沒開始</h2>
    </div>';
} else if ($week == 0) {
    if ($date_interval->days > 6) {     // 滿7天 進入第1週
        // 第0週評估單
        if (get_form_answer_self_evaluate_ios($user['id'], $user['exp_type'], 0) === false) {
            include 'self_evaluate_ios.php';
        } else {
            $sth = $dbh->prepare("INSERT INTO goal(id, u_id, `week`) VALUE (NULL, :u_id, 1)");
            $sth->execute([
                'u_id' => $user['id']
            ]);
            if ($dbh->lastInsertId() === false) {
                echo '<div class="d-grid pt-5 pb-3 px-2">
                <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO goal</h2>
                <p>errorInfo: ';
                var_dump($dbh->errorInfo());
                echo '</p>
                </div>';
            } else {
                header("Location: redirect_ios.php?exp_id={$user['exp_id']}");
            }
        }
    } else {
        include 'introduction.php';
    }
} else if ($week >= 1 && $week <= 8) {      // 第1-8週
    // 查詢goal資料
    $goal = get_goal($user['id'], $week);
    if ($goal === false) {      // goal 沒資料
        echo '<div class="d-grid pt-5 pb-3 px-2">
        <h3>結算中</h3>
        <p>請點下方按鈕重新載入</p>
        <p class="text-bg-danger">若重新載入後還是這個畫面請聯絡研究人員</p>
        <button class="btn btn-primary" onclick=location.href="redirect_ios.php?exp_id='.$user['exp_id'].'">重新載入</button>
        </div>';
    } else {
        if ($goal['start_date'] == null) {      // 還沒設定目標
            include 'set_goal_ios.php';
        } else {
            if ($user['exp_type'] == 'training_strategy') {     // 策略調控組
                // 高風險情境
                if (get_form_answer($user['id'], 'high_risk_situation') === false) {        // 尚未填寫
                    include 'high_risk_situation_form.php';
                } else {
                    $week_start_date = new DateTimeImmutable($goal['start_date']);
                    $week_date_interval = $today->diff($week_start_date);
                    if ($week == 1) {       // 第1週
                        if ($week_date_interval->days >= 0 && get_form_answer($user['id'], 'training_strategy_tutorial_1_future') === false) {                      // 第0天 設定目標、前瞻未來
                            include 'training_strategy_tutorial_1_future.php';
                        } else {
                            if ($week_date_interval->days >= 1 && get_form_answer($user['id'], 'training_strategy_tutorial_1_advantages') === false) {              // 第1天 好壞處分析
                                include 'training_strategy_tutorial_1_advantages.php';
                            } else {
                                if ($week_date_interval->days >= 2 && get_form_answer($user['id'], 'training_strategy_tutorial_1_misdirection') === false) {        // 第2天 分散注意力
                                    include 'training_strategy_tutorial_1_misdirection.php';
                                } else {
                                    if ($week_date_interval->days >= 3 && get_form_answer($user['id'], 'training_strategy_tutorial_1_breathing') === false) {       // 第3天 正念數息
                                        include 'training_strategy_tutorial_1_breathing.php';
                                    } else {
                                         // 計算天數 & 週結算 & 評估單

                                         echo '<p>第'.$date_interval->days.'天 今天是'.$today->format('Y-m-d(l)').' 計畫第'.$week.'週</p>';
                                        // 排名
                                        include 'rank_ios.php';
                                        // 拼圖 自我評估單
                                        include 'puzzle.php';
                                        // 查看APP使用時間
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                // 計算天數 & 週結算 & 評估單

                echo '<p>第'.$date_interval->days.'天 今天是'.$today->format('Y-m-d(l)').' 計畫第'.$week.'週</p>';
                // 排名
                include 'rank_ios.php';
                // 拼圖 自我評估單
                include 'puzzle.php';
                // 查看APP使用時間
                echo '<div class="d-grid pt-3 pb-3 px-2">
                <!-- 查看遊戲APP使用時間 -->
                <button class="btn btn-primary mt-3" onclick=location.href="goal_ios.php?u_id='.$user['id'].'&app_category=GAME">查看遊戲APP使用時間</button>
                <!-- 查看社交APP使用時間 -->
                <button class="btn btn-primary mt-3" onclick=location.href="goal_ios.php?u_id='.$user['id'].'&app_category=SOCIAL">查看社交APP使用時間</button>
                <!-- 查看娛樂APP使用時間 -->
                <button class="btn btn-primary mt-3" onclick=location.href="goal_ios.php?u_id='.$user['id'].'&app_category=ENTERTAINMENT">查看娛樂APP使用時間</button>
                <!-- 查看APP總使用時間 -->
                <button class="btn btn-primary mt-3" onclick=location.href="goal_ios.php?u_id='.$user['id'].'&app_category=TOTAL">查看娛樂APP使用時間</button>
                </div>';
            }
        }
    }
} else if ($week >= 9 && $week <= 24) {     // 第9-24週

} else if ($week >= 25){        // 第25+週 計畫結束
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