<?php
/*
 * 轉址程式，特別要在第1-8週轉址至相應的策略頁面
 */
require_once 'connect_db.php';
require_once 'get_user.php';        // 檢查HTTP Request Parameter的user.id
require_once 'html_head.php';

// 常數
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



if ($week < 0) {      // Week -1
    echo '<div class="d-grid pt-5 pb-3 px-2">
    <h2>計畫還沒開始</h2>
    </div>';
} else if ($week == 0) {    // 第0週
    /* // 檢查合規資料天數
    $sql = "SELECT
                COUNT(date)
            FROM
                (SELECT `date` FROM app_usage WHERE u_id=:u_id AND `date`>=:start_date AND `date`<DATE(NOW()) GROUP BY `date`) AS A
            WHERE
                A.date NOT IN (SELECT `date` FROM app_usage_error WHERE u_id=:u_id)";
    $sth = $dbh->prepare($sql);
    $sth->execute([
        'u_id' => $user['id'],
        'start_date' => $start_date->format('Y-m-d')
    ]);
    if ($sth->fetch(PDO::FETCH_COLUMN, 0) < 7) {        // 少於7天
        // 查詢異常資料
        $sql = "SELECT * FROM app_usage_error WHERE u_id=:u_id AND `date`>=:start_date AND `date`<=DATE(NOW())";
        $sth = $dbh->prepare($sql);
        $sth->execute([
            'u_id' => $user['id'],
            'start_date' => $start_date->format('Y-m-d')
        ]);
        $app_usage_error = $sth->fetchAll(PDO::FETCH_ASSOC);
        if (count($app_usage_error) > 0) {
            echo '<div class="d-grid pt-5 pb-3 px-2">
            <h2 class="text-danger">注意！您在下列日期的資料可能存在異常，請向研究人員確認</h2>
            <table class="table table-bordered">
            <tr>
                <th>日期</th>
                <th>總使用時間</th>
            </tr>';
            foreach ($app_usage_error as $row) {
                $usage_time_formatted = time_beautifier($row['usage_time']);
                echo "<tr>
                    <td>{$row['date']}</td>
                    <td>{$usage_time_formatted}</td>
                </tr>";
            }
            echo '</table>
            </div>';
        } else {
            echo '<p>第'.$date_interval->days.'天 今天是'.$today->format('Y-m-d(l)').' 計畫第0週 (預備週)</p>';

            // 查詢app_usage最新日期
            $sth = $dbh->prepare("SELECT MAX(`date`) FROM app_usage WHERE u_id = :u_id AND `date`<DATE(NOW())");
            $sth->execute([ 
                'u_id' => $user['id'] 
            ]);
            $newest_data_date = $sth->fetch(PDO::FETCH_COLUMN, 0);
            if ($newest_data_date == null) {
            } else {
                $newest_data_date = new DateTimeImmutable($newest_data_date);
                echo '<h3>您目前最新資料為'.$newest_data_date->format('Y-m-d').'</h3>';
                if ($newest_data_date >= $today->sub(new DateInterval("P1D"))) {
                    echo '<h3 class="text-primary">今日無須更新</h3>';
                } else {
                    echo '<h3 class="text-danger">請打開健康上網APP上傳最新資料</h3>';
                }
            }
            echo '<div class="d-grid pt-2 pb-3 px-2">
            <h3>本週目標：</h3>
            <p class="h4">
            本週沒有任何的目標，僅需要每日確認APP有上傳資料並顯示「今日資料已上傳」的字樣即可。
            </p>
            <p class="h4">
            以下為本計畫APP「健康上網自控APP」的使用說明和規則，<span class="text-danger">請務必詳讀</span>
            。
            </p>
            </div>';
    
            // 顯示使用說明和規則
            include 'introduction.php';
        }
    } else {        // 合規資料滿7天
        // 第0週評估單
        if (get_form_answer_self_evaluate($user['id'], $user['exp_type'], 0) === false) {       // 尚未填寫
            include 'self_evaluate_android.php';
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
                // 計算跟當週預計結算日差幾天
                // 結算日應為第7天，即N+1週的第0天
                $week_end_date = $start_date->add(new DateInterval('P7D'));
                
                // 新增week_adjust資料
                $sth = $dbh->prepare("INSERT INTO week_adjust (id, u_id, week, `days`) VALUE (NULL, :u_id, 0, :days)");
                $sth->execute([
                    'u_id' => $user['id'],
                    'days' => $today->diff($week_end_date)->days
                ]);
                if ($dbh->lastInsertId() === false) {
                    echo '<div class="d-grid pt-5 pb-3 px-2">
                    <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO goal</h2>
                    <p>errorInfo: ';
                    var_dump($dbh->errorInfo());
                    echo '</p>
                    </div>';
                } else {
                    // 跳轉頁面
                    header("Location: redirect.php?u_id={$user['id']}");
                }
            }
        }
    } */
	if (get_form_answer_self_evaluate($user['id'], $user['exp_type'], 0) === false) {       // 尚未填寫
		include 'self_evaluate_android.php';
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
			// 計算跟當週預計結算日差幾天
			// 結算日應為第7天，即N+1週的第0天
			$week_end_date = $start_date->add(new DateInterval('P7D'));
			
			// 新增week_adjust資料
			$sth = $dbh->prepare("INSERT INTO week_adjust (id, u_id, week, `days`) VALUE (NULL, :u_id, 0, :days)");
			$sth->execute([
				'u_id' => $user['id'],
				'days' => $today->diff($week_end_date)->days
			]);
			if ($dbh->lastInsertId() === false) {
				echo '<div class="d-grid pt-5 pb-3 px-2">
				<h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO goal</h2>
				<p>errorInfo: ';
				var_dump($dbh->errorInfo());
				echo '</p>
				</div>';
			} else {
				// 跳轉頁面
				header("Location: redirect.php?u_id={$user['id']}");
			}
		}
	}
} else if ($week >= 1 && $week <= 8) {      // 第1-8週
    // 查詢goal資料
    $goal = get_goal($user['id'], $week);
    if ($goal === false) {      // goal 沒資料
        echo '<div class="d-grid pt-5 pb-3 px-2">
        <h3>結算中</h3>
        <p>請點下方按鈕重新載入</p>
        <p class="text-bg-danger">若重新載入後還是這個畫面請聯絡研究人員</p>
        <button class="btn btn-primary" onclick=location.href="redirect.php?u_id='.$user['id'].'">重新載入</button>
        </div>';
    } else {        // goal有資料
        if ($goal['start_date'] == null) {      // 還沒設定目標
            include 'set_goal_android.php';
        } else {
            // 檢查不合規資料
            $sql = "SELECT * FROM app_usage_error WHERE u_id=:u_id AND `date`>=:start_date AND `date`<DATE(NOW())";
            $sth = $dbh->prepare($sql);
            $sth->execute([
                'u_id' => $user['id'],
                'start_date' => $goal['start_date']
            ]);
            $app_usage_error = $sth->fetchAll(PDO::FETCH_ASSOC);
            if (count($app_usage_error) > 0) {      // 有異常資料
                echo '<div class="d-grid pt-5 pb-3 px-2">
                <h2 class="text-danger">注意！您在下列日期的資料可能存在異常，請向研究人員確認</h2>
                <table class="table table-bordered">
                <tr>
                    <th>日期</th>
                    <th>總使用時間</th>
                </tr>';
                foreach ($app_usage_error as $row) {
                    echo "<tr>
                        <td>{$row['date']}</td>
                        <td>".time_beautifier($row['usage_time'])."</td>
                    </tr>";
                }
                echo '</table>
                </div>';
            }
            
            // 查詢合規資料天數
            $sql = "SELECT
                        MAX(`date`) AS date_max,
                        COUNT(*) AS date_count
                    FROM 
                        (
                            SELECT
                                `date`
                            FROM 
                                app_usage
                            WHERE
                                u_id=:u_id
                                AND
                                `date`>=:start_date
                                AND
                                `date`<DATE(NOW())
                                AND
                                `date` NOT IN (SELECT `date` FROM app_usage_error WHERE u_id=:u_id)
                            GROUP BY
                                `date`
                        ) AS A";
            $sth = $dbh->prepare($sql);
            var_dump($goal['start_date']);
            $sth->execute([
                'u_id' => $user['id'],
                'start_date' => $goal['start_date']
            ]);
            $app_usage_date = $sth->fetch(PDO::FETCH_ASSOC);

            // 計算分數
            // 查詢app_usage baseline資料
            // 查詢第0週日平均
            $app_usage_mean_baseline = array();
            $app_usage_mean_baseline_total = 0;
            $sql = "SELECT
                        app_category,
                        SUM(usage_time)/COUNT(*) AS usage_time_mean 
                    FROM 
                        app_usage
                    WHERE 
                        u_id=:u_id
                        AND 
                        `date` NOT IN (SELECT `date` FROM app_usage_error WHERE u_id=:u_id)
                        AND 
                        `date`>=(SELECT `start_date` FROM user WHERE id=:u_id)
                        AND
                        `date`<(SELECT `start_date` FROM goal WHERE u_id=:u_id AND week=1)
                        AND
                        `date` NOT IN (SELECT `date` FROM app_usage_error WHERE u_id=:u_id)
                    GROUP BY
                        app_category";
            $sth = $dbh->prepare($sql);
            $sth->execute([
                'u_id' => $user['id']
            ]);
            foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row) {
                foreach ($MAIN_APP_CATEGORY as $app_category => $category) {
                    if ($category['zh'] == $row['app_category']) {
                        $app_usage_mean_baseline[$app_category] = $row['usage_time_mean'];
                        break;
                    }
                }
                $app_usage_mean_baseline_total += $row['usage_time_mean'];
            }
            $usage_time_goal_total = $app_usage_mean_baseline_total * (100 - $goal['reduce_total']) / 100;
            $goal_score_total = 0;
            // 當週app_usage 所有app資料
            $sth = $dbh->prepare("SELECT `date`, SUM(usage_time) AS usage_time_sum FROM app_usage WHERE u_id=:u_id AND `date`>=(SELECT `start_date` FROM goal WHERE u_id=:u_id AND `week`=:week) AND `date`<DATE(NOW()) GROUP BY `date` ORDER BY `date` ASC");
            $sth->execute([
                'u_id' => $user['id'],
                'week' => $week
            ]);
            $date_list = array();
            $date_count = 0;
            foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $date_count++;
                if ($date_count > 7) {
                    if ($goal_score_total >= 70) {
                        $goal_score_total = 100;
                    }
                    break;
                } else {
                    $date_list[] = $row['date'];
                    if ($row['usage_time_sum'] <= $usage_time_goal_total) {
                        $goal_score_total += 10;
                    }
                }
            }
            // 更新 goal
            if ($goal_score_total != $goal['score_total']) {
                $sth = $dbh->prepare("UPDATE goal SET score_total=:score_total WHERE id=:goal_id");
                $sth->execute([
                    'score_total' => $goal_score_total,
                    'goal_id' => $goal['id']
                ]);
                $goal['score_total'] = $goal_score_total;
            }
            
            // 4大類
            foreach ($MAIN_APP_CATEGORY as $app_category => $category) {
                if (!key_exists($app_category, $app_usage_mean_baseline)) {
                    $usage_time_goal = 0;
                } else {
                    $usage_time_goal = $app_usage_mean_baseline[$app_category] * (100 - $goal[$category['reduce']]) / 100;
                }
                $goal_score = 0;
                // 當週app_usage該類APP資料
                $sth = $dbh->prepare("SELECT `date`, SUM(usage_time) AS usage_time_sum FROM app_usage WHERE u_id=:u_id AND `date`>=(SELECT `start_date` FROM goal WHERE u_id=:u_id AND `week`=:week) AND `date`<DATE(NOW()) AND app_category=:app_category GROUP BY `date` ORDER BY `date` ASC");
                $sth->execute([
                    'u_id' => $user['id'],
                    'week' => $week,
                    'app_category' => $category['zh']
                ]);
                $app_usage = $sth->fetchAll(PDO::FETCH_ASSOC);
                foreach ($date_list as $date) {
                    foreach ($app_usage as $row) {
                        if ($row['date'] == $date) {
                            if ($row['usage_time_sum'] <= $usage_time_goal) {
                                $goal_score += 10;
                                break;
                            }
                        }
                    }
                }
                if ($goal_score >= 70) {
                    $goal_score = 100;
                }
                // 更新 goal
                if ($goal_score != $goal[$category['score']]) {
                    $sth = $dbh->prepare($category['sql_score_update']);
                    $sth->execute([
                        $category['score'] => $goal_score,
                        'goal_id' => $goal['id']
                    ]);
                    $goal[$category['score']] = $goal_score;
                }
            }
            if ($user['exp_type'] == 'training_strategy') {     // 策略訓練組
                // 高風險情境
                if (get_form_answer($user['id'], 'high_risk_situation') === false) {      // 尚未填寫
                    include 'high_risk_situation_form.php';
                    die();
                } else {
                    if ($week >= 1 && $week <= 4) {         // 第1-4週 教學&實踐
                        if ($app_usage_date['date_count'] >= 0) {        // 第1天
                            if (get_form_answer_training_strategy($user['id'], $week, 1) == 0) {
                                include 'training_strategy_future.php';
                                die();
                            } else {
                                if ($app_usage_date['date_count'] >= 1) {        // 第2天
                                    if (get_form_answer_training_strategy($user['id'], $week, 2) == 0) {
                                        include 'training_strategy_advantages.php';
                                        die();
                                    } else {
                                        if ($app_usage_date['date_count'] >= 2) {        // 第3天
                                            if (get_form_answer_training_strategy($user['id'], $week, 3) == 0) {
                                                include 'training_strategy_misdirection.php';
                                                die();
                                            } else {
                                                if ($app_usage_date['date_count'] >= 3) {        // 第4天
                                                    if (get_form_answer_training_strategy($user['id'], $week, 4) == 0) {        // 未完成
                                                        include 'training_strategy_breathing.php';
                                                        die();
                                                    } else {        // 已完成
                                                        if ($app_usage_date['date_count'] >= 7) {        // 第7+天
                                                            // 結算
                                                            // 自我評估單
                                                            if (get_form_answer_self_evaluate($user['id'], $user['exp_type'], $week) === false) {       // 未填寫
                                                                include 'self_evaluate_android.php';
                                                                die();
                                                            } else {
                                                                // 新增goal新的一週的資料
                                                                $sql = "INSERT INTO 
                                                                            goal(id, u_id, week, makeup_game, makeup_video, makeup_social, makeup_communication, makeup_total)
                                                                        VALUE 
                                                                            (
                                                                                NULL,
                                                                                :u_id,
                                                                                :week,
                                                                                :makeup_game
                                                                                :makeup_video
                                                                                :makeup_social
                                                                                :makeup_communication
                                                                                :makeup_total
                                                                            )";
                                                                $sth = $dbh->prepare($sql);
                                                                $sth->execute([
                                                                    'u_id' => $user['id'],
                                                                    'week' => $week + 1,
                                                                    'makeup_makeup_game' => (($goal['score_game'] == 100) ? 0 : 1),
                                                                    'makeup_makeup_video' => (($goal['score_video'] == 100) ? 0 : 1),
                                                                    'makeup_makeup_social' => (($goal['score_social'] == 100) ? 0 : 1),
                                                                    'makeup_makeup_communication' => (($goal['score_communication'] == 100) ? 0 : 1),
                                                                    'makeup_makeup_total' => (($goal['score_total'] == 100) ? 0 : 1)
                                                                ]);
                                                                if ($dbh->lastInsertId() === false) {
                                                                    echo '<div class="d-grid pt-5 pb-3 px-2">
                                                                    <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO goal</h2>
                                                                    <p>errorInfo: ';
                                                                    var_dump($dbh->errorInfo());
                                                                    echo '</p>
                                                                    </div>';
                                                                } else {
                                                                    // 計算跟當週預計結算日差幾天
                                                                    // 結算日應為第7天，即N+1週的第0天
                                                                    $week_end_date = $start_date->add(new DateInterval('P7D'));
                                                                    
                                                                    // 新增week_adjust資料
                                                                    $sth = $dbh->prepare("INSERT INTO week_adjust (id, u_id, week, `days`) VALUE (NULL, :u_id, 0, :days)");
                                                                    $sth->execute([
                                                                        'u_id' => $user['id'],
                                                                        'days' => $today->diff($week_end_date)->days
                                                                    ]);
                                                                    if ($dbh->lastInsertId() === false) {
                                                                        echo '<div class="d-grid pt-5 pb-3 px-2">
                                                                        <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO goal</h2>
                                                                        <p>errorInfo: ';
                                                                        var_dump($dbh->errorInfo());
                                                                        echo '</p>
                                                                        </div>';
                                                                    } else {
                                                                        // 跳轉頁面
                                                                        header("Location: redirect.php?u_id={$user['id']}");
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else if ($week >= 5 && $week <= 8) {         // 第5-8週 應用
                        if ($app_usage_date['date_count'] <= 3) {       // 第1-4天
                            if (get_form_answer_training_strategy($user['id'], $week) < $app_usage_date['date_count'] + 1) {    // 完成數量不足
                                // 查詢當日是否有完成過
                                $sth = $dbh->prepare("SELECT * FROM form_answer WHERE f_id=(SELECT id FROM form WHERE `name`='training_strategy_apply') AND u_id=:u_id AND `date`=:today LIMIT 1");
                                $sth->execute([
                                    'u_id' => $user['id'],
                                    'today' => $today->format('Y-m-d')
                                ]);
                                if ($sth->fetch(PDO::FETCH_ASSOC) === false) {      // 沒有記錄
                                    include 'training_strategy_apply.php';
                                    die();
                                }
                            }
                        } else {
                            if (get_form_answer_training_strategy($user['id'], $week) < 4) {    // 完成數量不足
                                // 查詢當日是否有完成過
                                $sth = $dbh->prepare("SELECT * FROM form_answer WHERE f_id=(SELECT id FROM form WHERE `name`='training_strategy_apply') AND u_id=:u_id AND `date`=:today LIMIT 1");
                                $sth->execute([
                                    'u_id' => $user['id'],
                                    'today' => $today->format('Y-m-d')
                                ]);
                                if ($sth->fetch(PDO::FETCH_ASSOC) === false) {      // 沒有記錄
                                    include 'training_strategy_apply.php';
                                    die();
                                }
                            } else {        // 已完成
                                // 準備結算
                                if ($app_usage_date['date_count'] >= 7) {       // 第8+天
                                    // 自我評估單
                                    if (get_form_answer_self_evaluate($user['id'], $user['exp_type'], $week) === false) {       // 未填寫
                                        include 'self_evaluate_android.php';
                                        die();
                                    } else {
                                        // 新增goal新的一週的資料
                                        $sql = "INSERT INTO 
                                                    goal(id, u_id, week, makeup_game, makeup_video, makeup_social, makeup_communication, makeup_total)
                                                VALUE 
                                                    (
                                                        NULL,
                                                        :u_id,
                                                        :week,
                                                        :makeup_game
                                                        :makeup_video
                                                        :makeup_social
                                                        :makeup_communication
                                                        :makeup_total
                                                    )";
                                        $sth = $dbh->prepare($sql);
                                        $sth->execute([
                                            'u_id' => $user['id'],
                                            'week' => $week + 1,
                                            'makeup_makeup_game' => (($goal['score_game'] >= 100) ? 0 : 1),
                                            'makeup_makeup_video' => (($goal['score_video'] >= 100) ? 0 : 1),
                                            'makeup_makeup_social' => (($goal['score_social'] >= 100) ? 0 : 1),
                                            'makeup_makeup_communication' => (($goal['score_communication'] >= 100) ? 0 : 1),
                                            'makeup_makeup_total' => (($goal['score_total'] >= 100) ? 0 : 1)
                                        ]);
                                        if ($dbh->lastInsertId() === false) {
                                            echo '<div class="d-grid pt-5 pb-3 px-2">
                                            <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO goal</h2>
                                            <p>errorInfo: ';
                                            var_dump($dbh->errorInfo());
                                            echo '</p>
                                            </div>';
                                        } else {
                                            // 計算跟當週預計結算日差幾天
                                            // 結算日應為第7天，即N+1週的第0天
                                            $week_end_date = $start_date->add(new DateInterval('P7D'));
                                            
                                            // 新增week_adjust資料
                                            $sth = $dbh->prepare("INSERT INTO week_adjust (id, u_id, week, `days`) VALUE (NULL, :u_id, 0, :days)");
                                            $sth->execute([
                                                'u_id' => $user['id'],
                                                'days' => $today->diff($week_end_date)->days
                                            ]);
                                            if ($dbh->lastInsertId() === false) {
                                                echo '<div class="d-grid pt-5 pb-3 px-2">
                                                <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO goal</h2>
                                                <p>errorInfo: ';
                                                var_dump($dbh->errorInfo());
                                                echo '</p>
                                                </div>';
                                            } else {
                                                // 跳轉頁面
                                                header("Location: redirect.php?u_id={$user['id']}");
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if ($app_usage_date['date_count'] >= 0) {        // 第1天
                            if (get_form_answer_training_strategy($user['id'], $week, 1) == 0) {
                                include 'training_strategy_apply.php';
                                die();
                            } else {
                                if ($app_usage_date['date_count'] >= 1) {        // 第2天
                                    if (get_form_answer_training_strategy($user['id'], $week, 2) <= 1) {
                                        include 'training_strategy_apply.php';
                                        die();
                                    } else {
                                        if ($app_usage_date['date_count'] >= 2) {        // 第3天
                                            if (get_form_answer_training_strategy($user['id'], $week, 3) <= 2) {
                                                include 'training_strategy_apply.php';
                                                die();
                                            } else {
                                                if ($app_usage_date['date_count'] >= 3) {        // 第4天
                                                    if (get_form_answer_training_strategy($user['id'], $week, 4) <= 3) {        // 未完成
                                                        include 'training_strategy_apply.php';
                                                        die();
                                                    } else {        // 已完成
                                                        if ($app_usage_date['date_count'] >= 7) {        // 第8+天
                                                            // 結算
                                                            // 自我評估單
                                                            if (get_form_answer_self_evaluate($user['id'], $user['exp_type'], $week) === false) {       // 未填寫
                                                                include 'self_evaluate_android.php';
                                                                die();
                                                            } else {
                                                                // 新增goal新的一週的資料
                                                                $sql = "INSERT INTO 
                                                                            goal(id, u_id, week, makeup_game, makeup_video, makeup_social, makeup_communication, makeup_total)
                                                                        VALUE 
                                                                            (
                                                                                NULL,
                                                                                :u_id,
                                                                                :week,
                                                                                :makeup_game
                                                                                :makeup_video
                                                                                :makeup_social
                                                                                :makeup_communication
                                                                                :makeup_total
                                                                            )";
                                                                $sth = $dbh->prepare($sql);
                                                                $sth->execute([
                                                                    'u_id' => $user['id'],
                                                                    'week' => $week + 1,
                                                                    'makeup_makeup_game' => (($goal['score_game'] == 100) ? 0 : 1),
                                                                    'makeup_makeup_video' => (($goal['score_video'] == 100) ? 0 : 1),
                                                                    'makeup_makeup_social' => (($goal['score_social'] == 100) ? 0 : 1),
                                                                    'makeup_makeup_communication' => (($goal['score_communication'] == 100) ? 0 : 1),
                                                                    'makeup_makeup_total' => (($goal['score_total'] == 100) ? 0 : 1)
                                                                ]);
                                                                if ($dbh->lastInsertId() === false) {
                                                                    echo '<div class="d-grid pt-5 pb-3 px-2">
                                                                    <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO goal</h2>
                                                                    <p>errorInfo: ';
                                                                    var_dump($dbh->errorInfo());
                                                                    echo '</p>
                                                                    </div>';
                                                                } else {
                                                                    // 計算跟當週預計結算日差幾天
                                                                    // 結算日應為第7天，即N+1週的第0天
                                                                    $week_end_date = $start_date->add(new DateInterval('P7D'));
                                                                    
                                                                    // 新增week_adjust資料
                                                                    $sth = $dbh->prepare("INSERT INTO week_adjust (id, u_id, week, `days`) VALUE (NULL, :u_id, 0, :days)");
                                                                    $sth->execute([
                                                                        'u_id' => $user['id'],
                                                                        'days' => $today->diff($week_end_date)->days
                                                                    ]);
                                                                    if ($dbh->lastInsertId() === false) {
                                                                        echo '<div class="d-grid pt-5 pb-3 px-2">
                                                                        <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO goal</h2>
                                                                        <p>errorInfo: ';
                                                                        var_dump($dbh->errorInfo());
                                                                        echo '</p>
                                                                        </div>';
                                                                    } else {
                                                                        // 跳轉頁面
                                                                        header("Location: redirect.php?u_id={$user['id']}");
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
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
            } else if ($user['exp_type'] == 'set_goal') {       // 目標調控組
                if ($app_usage_date['date_count'] >= 7) {
                    // 結算
                    // 自我評估單
                    if (get_form_answer_self_evaluate($user['id'], $user['exp_type'], $week) === false) {       // 未填寫
                        include 'self_evaluate_android.php';
                        die();
                    } else {
                        // 新增goal新的一週的資料
                        $sql = "INSERT INTO 
                                    goal(id, u_id, week, makeup_game, makeup_video, makeup_social, makeup_communication, makeup_total)
                                VALUE 
                                    (
                                        NULL,
                                        :u_id,
                                        :week,
                                        :makeup_game
                                        :makeup_video
                                        :makeup_social
                                        :makeup_communication
                                        :makeup_total
                                    )";
                        $sth = $dbh->prepare($sql);
                        $sth->execute([
                            'u_id' => $user['id'],
                            'week' => $week + 1,
                            'makeup_makeup_game' => (($goal['score_game'] == 100) ? 0 : 1),
                            'makeup_makeup_video' => (($goal['score_video'] == 100) ? 0 : 1),
                            'makeup_makeup_social' => (($goal['score_social'] == 100) ? 0 : 1),
                            'makeup_makeup_communication' => (($goal['score_communication'] == 100) ? 0 : 1),
                            'makeup_makeup_total' => (($goal['score_total'] == 100) ? 0 : 1)
                        ]);
                        if ($dbh->lastInsertId() === false) {
                            echo '<div class="d-grid pt-5 pb-3 px-2">
                            <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO goal</h2>
                            <p>errorInfo: ';
                            var_dump($dbh->errorInfo());
                            echo '</p>
                            </div>';
                        } else {
                            // 計算跟當週預計結算日差幾天
                            // 結算日應為第7天，即N+1週的第0天
                            $week_end_date = $start_date->add(new DateInterval('P7D'));
                            
                            // 新增week_adjust資料
                            $sth = $dbh->prepare("INSERT INTO week_adjust (id, u_id, week, `days`) VALUE (NULL, :u_id, 0, :days)");
                            $sth->execute([
                                'u_id' => $user['id'],
                                'days' => $today->diff($week_end_date)->days
                            ]);
                            if ($dbh->lastInsertId() === false) {
                                echo '<div class="d-grid pt-5 pb-3 px-2">
                                <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO goal</h2>
                                <p>errorInfo: ';
                                var_dump($dbh->errorInfo());
                                echo '</p>
                                </div>';
                            } else {
                                // 跳轉頁面
                                header("Location: redirect.php?u_id={$user['id']}");
                            }
                        }
                    }
                }
            }
        
            echo '<div class="d-grid pt-3 pb-3 px-2">
            <p>第'.$date_interval->days.'天 今天是'.$today->format('Y-m-d(l)').' 計畫第'.$week.'週</p>
            <h3>
            您目前最新資料為'.$app_usage_date['date_max'].'<br>
            本週已收集'.$app_usage_date['date_count'].'天資料
            </h3>';
            var_dump($app_usage_date['date_max']);
            var_dump($today->sub(new DateInterval('P1D'))->format('Y-m-d'));
            if ($app_usage_date['date_max'] == $today->sub(new DateInterval('P1D'))->format('Y-m-d')) {
                echo '<h3 class="text-primary">今日無須更新</h3>';
            } else {
                echo '<h3 class="text-danger">請打開健康上網APP上傳最新資料</h3>';
            }
            // 排名
            include 'rank.php';
            // 拼圖 自我評估單
            include 'puzzle.php';
            // 查看APP使用時間
            echo '<div class="d-grid pt-3 pb-3 px-2">';
            foreach ($MAIN_APP_CATEGORY as $app_category => $category) {
                echo '<button class="btn btn-primary mt-3" onclick=location.href="goal.php?u_id='.$user['id'].'&app_category='.$app_category.'">查看'.$category['zh'].'APP使用時間</button>';
            }
            echo '<!-- 查看APP總使用時間 -->
            <button class="btn btn-primary mt-3" onclick=location.href="goal.php?u_id='.$user['id'].'&app_category=TOTAL">查看APP總使用時間</button>
            </div>';
        }
    }
} else if ($week >= 9 && $week <= 24) {     // 第9-24週
    $goal = get_goal($user['id'], $week);
    if ($goal['start_date'] == null) {
        $sth = $dbh->prepare("UPDATE goal SET `start_date`=:today WHERE id=:goal_id");
        $sth->execute([
            'today' => $today->format('Y-m-d'),
            'goal_id' => $goal['id']
        ]);
        $goal['start_date'] = $today->format('Y-m-d');
    } else {
        // 查詢合規資料天數
        $sql = "SELECT
                    MAX(`date`) AS date_max,
                    COUNT(*) AS date_count
                FROM 
                    (
                        SELECT
                            `date`
                        FROM 
                            app_usage
                        WHERE
                            u_id=:u_id
                            AND
                            `date`>=:start_date
                            AND
                            `date`<DATE(NOW())
                            AND
                            `date` NOT IN (SELECT `date` FROM app_usage_error WHERE u_id=:u_id)
                        GROUP BY
                            `date`
                    ) AS A";
        $sth = $dbh->prepare($sql);
        $sth->execute([
            'u_id' => $user['id'],
            'start_date' => $goal['start_date']
        ]);
        $app_usage_date = $sth->fetch(PDO::FETCH_ASSOC);

        if ($app_usage_date['date_count'] >= 7) {
            // 結算
            // 自我評估單
            if (get_form_answer_self_evaluate($user['id'], $user['exp_type'], $week) === false) {       // 未填寫
                include 'self_evaluate_android.php';
                die();
            } else {
                // 新增goal新的一週的資料
                $sql = "INSERT INTO 
                            goal(id, u_id, week)
                        VALUE 
                            (
                                NULL,
                                :u_id,
                                :week
                            )";
                $sth = $dbh->prepare($sql);
                $sth->execute([
                    'u_id' => $user['id'],
                    'week' => $week + 1
                ]);
                if ($dbh->lastInsertId() === false) {
                    echo '<div class="d-grid pt-5 pb-3 px-2">
                    <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO goal</h2>
                    <p>errorInfo: ';
                    var_dump($dbh->errorInfo());
                    echo '</p>
                    </div>';
                } else {
                    // 計算跟當週預計結算日差幾天
                    // 結算日應為第7天，即N+1週的第0天
                    $week_end_date = $start_date->add(new DateInterval('P7D'));
                    
                    // 新增week_adjust資料
                    $sth = $dbh->prepare("INSERT INTO week_adjust (id, u_id, week, `days`) VALUE (NULL, :u_id, 0, :days)");
                    $sth->execute([
                        'u_id' => $user['id'],
                        'days' => $today->diff($week_end_date)->days
                    ]);
                    if ($dbh->lastInsertId() === false) {
                        echo '<div class="d-grid pt-5 pb-3 px-2">
                        <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO goal</h2>
                        <p>errorInfo: ';
                        var_dump($dbh->errorInfo());
                        echo '</p>
                        </div>';
                    } else {
                        // 跳轉頁面
                        header("Location: redirect.php?u_id={$user['id']}");
                    }
                }
            }
        }
    
        echo '<div class="d-grid pt-3 pb-3 px-2">
        <p>第'.$date_interval->days.'天 今天是'.$today->format('Y-m-d(l)').' 計畫第'.$week.'週</p>
        <h3>
        您目前最新資料為'.$app_usage_date['date_max'].'<br>
        本週已收集'.$app_usage_date['date_count'].'天資料
        </h3>';
        if ($app_usage_date['date_max'] == $today->sub(new DateInterval('P1D'))->format('Y-m-d')) {
            echo '<h3 class="text-primary">今日無須更新</h3>';
        } else {
            echo '<h3 class="text-danger">請打開健康上網APP上傳最新資料</h3>';
        }
        include 'rank.php';
        include 'puzzle.php';

        echo '<div class="d-grid pt-3 pb-3 px-2">';
        foreach ($MAIN_APP_CATEGORY as $app_category => $category) {
            echo '<button class="btn btn-primary mt-3" onclick=location.href="goal.php?u_id='.$user['id'].'&app_category='.$app_category.'">查看'.$category['zh'].'APP使用時間</button>';
        }
        echo '<!-- 查看APP總使用時間 -->
        <button class="btn btn-primary mt-3" onclick=location.href="goal.php?u_id='.$user['id'].'&app_category=TOTAL">查看娛樂APP使用時間</button>
        </div>';
    }

    
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