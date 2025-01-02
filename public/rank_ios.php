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

// 查詢設定目標
$goal = get_goal($user['id'], $week);
$week_start_date = new DateTimeImmutable($goal['start_date']);

// 查詢app_usage上週使用時間
$sth = $dbh->prepare("SELECT SUM(usage_time) FROM app_usage WHERE u_id=:u_id AND `date`>=:start_date AND `date`<=:end_date");
$sth->execute([
    'u_id' => $user['id'],
    'start_date' => $week_start_date->sub(new DateInterval('P7D'))->format('Y-m-d'),
    'end_date' => $week_start_date->sub(new DateInterval('P1D'))->format('Y-m-d')
]);
$usage_time_mean = intval($sth->fetch(PDO::FETCH_COLUMN, 0)) / 7;

// 計算總人數
$user_count = 0;
// 查詢app_usage有上一週資料的人數(Android組)
$sql = "SELECT COUNT(*)
        FROM 
            -- 查詢前一週有效天數
            (
                SELECT
                    u_id,
                    COUNT(`date`)
                FROM 
                    (
                        SELECT
                            A.u_id,
                            A.date
                        FROM 
                            app_usage A,
                            -- 查詢參與者前一週日期範圍
                            (
                                SELECT
                                    A.u_id,
                                    MAX(A.week)-1 AS `week`,
                                    DATE_SUB(A.start_date, INTERVAL SUM(C.days)+7 DAY) AS `start_date`,
                                    DATE_SUB(A.start_date, INTERVAL 1 DAY) AS end_date
                                FROM
                                    goal A,
                                    week_adjust C
                                WHERE 
                                    (A.u_id IN (SELECT id FROM user WHERE exp_id LIKE 'A" . YEAR_NO . "____') AND A.start_date IS NOT NULL)
                                    AND 
                                    C.u_id=A.u_id 
                                    AND 
                                    C.week=A.week-1
                                GROUP BY A.u_id
                            ) AS B
                        WHERE
                            (A.date>=B.start_date AND A.date<=B.end_date)
                            AND 
                            (B.u_id=A.u_id)
                        GROUP BY A.u_id, A.date
                    ) AS A
                GROUP BY u_id
            ) AS A";
$sth = $dbh->prepare($sql);
$sth->execute();
$user_count = $user_count + intval($sth->fetch(PDO::FETCH_COLUMN, 0));

// 查詢app_usage有上一週資料的人數(iOS組)
$sql = "SELECT
            COUNT(*)
        FROM
            (
                SELECT
                    u_id
                FROM
                    goal
                WHERE
                    u_id IN (SELECT id FROM user WHERE exp_id LIKE 'I" . YEAR_NO . "____')
                GROUP BY 
                    u_id
            ) AS A";
$sth = $dbh->prepare($sql);
$sth->execute();
$user_count = $user_count + intval($sth->fetch(PDO::FETCH_COLUMN, 0));

// 查詢app_usage排名
$rank = 1;
// 查詢前一週平均使用時間少於自己的人數(Android組)
$sql = "SELECT COUNT(*)
        FROM 
            (
                SELECT
                    u_id,
                    SUM(usage_time)/COUNT(`date`) AS usage_time_mean
                FROM 
                    (
                        SELECT
                            A.u_id,
                            A.date,
                            SUM(A.usage_time) AS usage_time
                        FROM 
                            app_usage A,
                            (
                                SELECT
                                    A.u_id,
                                    DATE_SUB(A.start_date, INTERVAL SUM(C.days)+7 DAY) AS `start_date`,
                                    DATE_SUB(A.start_date, INTERVAL 1 DAY) AS end_date
                                FROM
                                    goal A,
                                    week_adjust C
                                WHERE 
                                    (A.u_id IN (SELECT id FROM user WHERE exp_id LIKE 'A" . YEAR_NO . "____') AND A.start_date IS NOT NULL)
                                    AND 
                                    C.u_id=A.u_id 
                                    AND 
                                    C.week=A.week-1
                                GROUP BY A.u_id
                            ) AS B
                        WHERE
                            (A.date>=B.start_date AND A.date<=B.end_date)
                            AND 
                            (B.u_id=A.u_id)
                        GROUP BY A.u_id, A.date
                    ) AS A
                GROUP BY u_id
            ) AS A
        WHERE usage_time_mean<:usage_time_mean";
$sth = $dbh->prepare($sql);
$sth->execute([
    'usage_time_mean' => $usage_time_mean
]);
$rank = $rank + intval($sth->fetch(PDO::FETCH_COLUMN, 0));

// 查詢前一週平均使用時間少於自己的人數(iOS組)
$sql = "SELECT COUNT(*)
        FROM 
            (
                SELECT
                    u_id,
                    SUM(usage_time)/7 AS usage_time_mean
                FROM 
                    (
                        SELECT
                            A.u_id,
                            A.date,
                            SUM(A.usage_time) AS usage_time
                        FROM 
                            app_usage A,
                            (
                                SELECT
                                    u_id,
                                    DATE_SUB(start_date, INTERVAL 7 DAY) AS `start_date`,
                                    DATE_SUB(start_date, INTERVAL 1 DAY) AS end_date
                                FROM
                                    goal
                                WHERE 
                                    (u_id IN (SELECT id FROM user WHERE exp_id LIKE 'A" . YEAR_NO . "____') AND `start_date` IS NOT NULL)
                                GROUP BY u_id
                            ) AS B
                        WHERE
                            (A.date>=B.start_date AND A.date<=B.end_date)
                            AND 
                            (B.u_id=A.u_id)
                        GROUP BY A.u_id, A.date
                    ) AS A
                GROUP BY u_id
            ) AS A
        WHERE usage_time_mean<:usage_time_mean";
$sth = $dbh->prepare($sql);
$sth->execute([
    'usage_time_mean' => $usage_time_mean
]);
$rank = $rank + intval($sth->fetch(PDO::FETCH_COLUMN, 0));


if ($usage_time_mean > 0) {
    echo '<div class="d-grid pt-2 pb-2 mx-2">
    <h4>
    <p>您上週在'.$user_count.'個人中排名第 '.$rank.' 名，平均每日總使用時間 '.time_beautifier($usage_time_mean).' (時:分:秒)</p>';
    // 查詢上週設定目標
    $goal_last = get_goal($user['id'], $week-1);
    if ($goal_last !== false) {
        $usage_time_goal = floor($usage_time_mean * ((100 - intval($goal_last['reduce_total'])) / 100));
        $usage_time_goal_diff = $usage_time_goal - $usage_time_mean;
        echo '<p>與設定的日平均時間'.time_beautifier($usage_time_goal).'相比';
        if ($usage_time_goal_diff > 0) {
            echo '少了約'.time_beautifier($usage_time_goal_diff).'</p>';
        } else {
            echo '多了約'.time_beautifier($usage_time_goal_diff * -1).'</p>';
        }
    }
    echo '</h4>
    </div>';
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