<?php
/**
 * 查看APP使用時間 iOS版
 */
require_once 'connect_db.php';
require_once 'get_user.php';
require_once 'html_head.php';

$APP_CATEGORY_LIST = array(
    'GAME' => '遊戲',
    'SOCIAL' => '社交',
    'ENTERTAINMENT' => '娛樂'
);

if (!key_exists('app_category', $_GET)) {
    echo '<div class="d-grid pt-5 pb-3 px-2">
    <h3 class="text-danger">APP類別錯誤，請聯絡研究人員</h3>
    <p>GET: ';
    var_dump($_GET);
    echo '</p>';
} else {
    $app_category = $_GET['app_category'];
    if (!in_array($app_category, array('GAME', 'SOCIAL', 'ENTERTAINMENT', 'TOTAL'))) {
        echo '<div class="d-grid pt-5 pb-3 px-2">
        <h3 class="text-danger">APP類別錯誤，請聯絡研究人員</h3>
        <p>app_category: ';
        var_dump($app_category);
        echo '</p>'; 
    } else {
        // 查詢設定目標
        $goal = get_goal($user['id'], $week);
        $week_start_date = new DateTimeImmutable($goal['start_date']);
        $last_week_start_date = $week_start_date->sub(new DateInterval('P7D'))->format('Y-m-d');        // 本週開始日-7
        $last_week_end_date = $week_start_date->sub(new DateInterval('P1D'))->format('Y-m-d');          // 本週開始日-1
        
        
        // 查詢app_usage上週使用時間
        if ($app_category == 'TOTAL') {
            $sth = $dbh->prepare("SELECT SUM(usage_time) FROM app_usage WHERE u_id=:u_id AND `date`>=:start_date AND `date`<=:end_date");    
            $sth->execute([
                'u_id' => $user['id'],
                'start_date' => $last_week_start_date,
                'end_date' => $last_week_end_date
            ]);
        } else {
            $sth = $dbh->prepare("SELECT SUM(usage_time) FROM app_usage WHERE u_id=:u_id AND `date`>=:start_date AND `date`<=:end_date AND app_category=:app_category");    
            $sth->execute([
                'u_id' => $user['id'],
                'start_date' => $last_week_start_date,        
                'end_date' => $last_week_end_date,           
                'app_category' => $APP_CATEGORY_LIST($app_category)
            ]);
        }
        $usage_time_mean = intval($sth->fetch(PDO::FETCH_COLUMN, 0)) / 7;

        // 查詢app_usage其他參與者的最新資料(Android組)
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
                            (A.u_id IN (SELECT id FROM user WHERE exp_id LIKE 'A" . YEAR_NO . "____') AND A.date>=B.start_date AND A.date<=B.end_date)
                            AND 
                            (B.u_id=A.u_id)
                        GROUP BY A.u_id, A.date
                    ) AS A
                GROUP BY u_id
            ) AS A";
        $sth = $dbh->prepare($sql);
        $sth->execute();
        $user_count = intval($sth->fetch(PDO::FETCH_COLUMN, 0));

    }
}
echo '<button class="btn btn-secondary" onclick=location.href="redirect_ios.php?exp_id='.$user['exp_id'].'">返回主畫面</button>';