<?php
/*
 * 查看排名
 * 第1週第2(總:8)天開始可以看昨日排名
 * 第2週第1(總:14)天開始可以看上週排名
 * 
 * 排名規則：以實際減量百分比進行排序，減量值越大，排名越高
 */
require_once 'connect_db.php';
require_once 'get_user.php';
require_once 'html_head.php';

// 加分獎勵小語
$ENCOURAGE_SLOGAN = array(
    1 => array(
        '我很棒!',
        '我給自己掌聲!',
        '我為自己喝采!',
        '我勇於面對挑戰!',
        '我勇氣十足!',
        '我天天進步!',
        '我日日成長!'
    ),
    2 => array(
        '我很棒!',
        '我給自己掌聲!',
        '我為自己喝采!',
        '我勇於面對挑戰!',
        '我勇氣十足!',
        '我天天進步!',
        '我日日成長!'
    ),
    3 => array(
        '我很行!',
        '我執行力強!',
        '我懂得拒絕誘惑!',
        '我很會時間管理!',
        '我很有智慧!',
        '我能堅持!',
        '我很有毅力!'
    ),
    4 => array(
        '我很行!',
        '我執行力強!',
        '我懂得拒絕誘惑!',
        '我很會時間管理!',
        '我很有智慧!',
        '我能堅持!',
        '我很有毅力!'
    ),
    5 => array(
        '我超讚!',
        '我很有韌性!',
        '我是問題解決高手!',
        '我很有自控力!',
        '我突破自我!',
        '我超越自己!',
        '我很厲害!'
    ),
    6 => array(
        '我超讚!',
        '我很有韌性!',
        '我是問題解決高手!',
        '我很有自控力!',
        '我突破自我!',
        '我超越自己!',
        '我很厲害!'
    ),
    7 => array(
        '我自我管理能力愈來愈好!',
        '我愈來愈有自信!',
        '我是能堅持到底的人!',
        '我相信我會成功!',
        '我很有策略達標!',
        '我自制、我行!',
        '很想要，但我能克制!'
    ),
    8 => array(
        '我自我管理能力愈來愈好!',
        '我愈來愈有自信!',
        '我是能堅持到底的人!',
        '我相信我會成功!',
        '我很有策略達標!',
        '我自制、我行!',
        '很想要，但我能克制!'
    ),
    false => array(
        '雖有挫折，我再努力!',
        '我會愈挫愈勇，繼續努力!',
        '只是一時挫折，再努力會達標!',
        '面對誘惑，找出方法，我會克服!',
        '失敗是成功之母，找出方法再努力，我會成功!',
        '只要我堅持努力，我會成功!',
        '挫折只是一時，我會成功!'
    ),
);

// 查詢昨天使用時間
$sth = $dbh->prepare("SELECT SUM(usage_time) FROM app_usage WHERE u_id=:u_id AND `date`=:date");
$sth->execute([
    'u_id' => $user['id'],
    'date' => $app_usage_date['date_max']
]);
$usage_time = intval($sth->fetch(PDO::FETCH_COLUMN, 0));

// 跟前一天比較
$sql = "SELECT
            SUM(usage_time)-:usage_time
        FROM
            app_usage
        WHERE
            u_id=:u_id
            AND
            `date`=(
                SELECT
                    MAX(`date`)
                FROM
                    app_usage
                WHERE
                    u_id=:u_id
                    AND
                    `date` NOT IN (SELECT `date` FROM app_usage_error WHERE u_id=:u_id)
                    AND
                    `date`<>:date_max
            )";
$sth = $dbh->prepare($sql);
$sth->execute([
    'usage_time' => $usage_time,
    'u_id' => $user['id'],
    'date_max' => $app_usage_date['date_max']
]);
$usage_time_diff = intval($sth->fetch(PDO::FETCH_COLUMN, 0));

// 查詢app_usage其他參與者的最新資料
$sql = "SELECT COUNT(*)
        FROM
            (
                SELECT u_id, SUM(usage_time) AS usage_time_sum
                FROM app_usage
                WHERE u_id IN
                    (
                        SELECT id
                        FROM user
                    ) AND `date` = :date
                GROUP BY u_id
            ) AS A";
$sth = $dbh->prepare($sql);
$sth->execute([
    'date' => $app_usage_date['date_max']
]);
$user_count = intval($sth->fetch(PDO::FETCH_COLUMN, 0));

// 查詢app_usage排名
$sql = "SELECT COUNT(*) + 1
        FROM
            (
                SELECT u_id, SUM(usage_time) AS usage_time_sum
                FROM app_usage
                WHERE u_id IN
                    (
                        SELECT id
                        FROM user
                    ) AND `date` = :date
                GROUP BY u_id
            ) AS A
        WHERE A.usage_time_sum<:usage_time";
$sth = $dbh->prepare($sql);
$sth->execute([
    'date' => $app_usage_date['date_max'],
    'usage_time' => $usage_time
]);
$rank = intval($sth->fetch(PDO::FETCH_COLUMN, 0));

if ($usage_time > 0) {
    echo '<div class="d-grid pt-2 pb-2 mx-2">
    <h4>
    <p>您昨日在'.$user_count.'個人中排名第 '.$rank.' 名，累積時間 '.time_beautifier($usage_time).' (時:分:秒)</p>';
    if ($usage_time_diff > 0) {
        echo '<p>與前一天相比減少了'.time_beautifier($usage_time_diff).'(時:分:秒)</p>';
    } else {
        echo '<p>與前一天相比多了'.time_beautifier($usage_time_diff * -1).'(時:分:秒)</p>';
    }
    echo '</h4>';
    if ($week >= 1 && $week <= 8) {
        // 計算減量目標
        $goal = get_goal($user['id'], $week);
        if ($goal['reduce_total'] > 0) {
            // 查詢第0週日平均
            $sql = "SELECT
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
                        `date`<(SELECT `start_date` FROM goal WHERE u_id=:u_id AND week=1)";
            $sth = $dbh->prepare($sql);
            $sth->execute([
                'u_id' => $user['id']
            ]);
            $usage_time_mean_baseline = intval($sth->fetch(PDO::FETCH_COLUMN, 0));
            // 跟減量目標比較
            $usage_time_goal = $usage_time_mean_baseline * ((100 - intval($goal['reduce_total'])) / 100);
            $usage_time_goal_diff = $usage_time_goal - $usage_time;
            echo '<p>與設定的日平均時間('.time_beautifier($usage_time_goal).')相比';
            if ($usage_time_goal_diff > 0) {        // 達標
                echo '少了約'.time_beautifier($usage_time_goal_diff).'</p>';
                if ($goal['score_total'] == 100) {
                    echo '<p class="text-success>成功達成本週設定的目標，積分+40，目前已累積100分，下週請繼續保持！</p>';
                } else if ($goal['score_total'] < 70) {
                    echo '<p class="text-success>成功達成本週設定的目標，積分+40，目前已累積'.$goal['score_total'].'分，下週請繼續保持！</p>';
                } else {
                    echo '<div class="d-grid pt-5 pb-3 px-2">
                    <h2 class="text-danger">分數計算錯誤: goal.score_total</h2>
                    <p>score_total: ';
                    var_dump($goal['score_total']);
                    echo '</p>
                    </div>';
                }
                // 顯示獎勵小語
                if ($user['exp_type'] == 'training_strategy') {
                    echo '<h3 class="text-success">'.$ENCOURAGE_SLOGAN[$week][random_int(0, 6)].'</h3>';
                }
            } else {        // 未達標
                echo '多了約'.time_beautifier($usage_time_goal_diff * -1).'</p>';
                // 顯示獎勵小語
                echo '<p>未達成本週設定的目標，目前累積'.intval($goal['score_total']).'分，明天請繼續加油！</p>';
                if ($user['exp_type'] == 'training_strategy') {
                    echo '<h3 class="text-danger">'.$ENCOURAGE_SLOGAN[false][random_int(0, 6)].'</h3>';
                }
            }
        }
    }
} else {
}
/**
 * <p>第13天今天是2019-12-18 (Wednesday)計畫第1週</p
 * <h3>您目前最新資料為2012-12-17</h3>
 * <h3>今日無須更新</h3>
 * <hr>
 * <h4>
 * <p>您昨日在3個人中排名第 1 名，累積時間 00:24:15 (時:分:秒)</p>
 * <p>與前一天相比減少了:(時:分:秒)</p>
 * <p>與設定的日平均時間00:08:47相比多了約15分</p>
 * </h4>
 */
?>