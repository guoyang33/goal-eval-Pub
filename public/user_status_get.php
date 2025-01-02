<?php
/**
 * 取得各種狀態資料
 */
require_once 'connect_db.php';
require_once 'json_header.php';

$u_id = $_GET['u_id'];
$password = $_GET['password'];

// 檢查密碼正確
$sth = $dbh->prepare("SELECT * FROM `user` WHERE `id` = :u_id LIMIT 1");
$sth->execute([ 'u_id' => $u_id ]);
$user = $sth->fetch(PDO::FETCH_ASSOC);
if ($user === false) {      // 沒找到user
    $response['headers']['status'] = 'USER_PASSWORD_ERROR';
    $response['headers']['error_msg'] = 'Post u_id or password does not match any user.';
} else {        // 找到user
    // 檢查密碼
    if ($password !== $user['password']) {      // 不正確
        $response['headers']['status'] = 'USER_PASSWORD_ERROR';
        $response['headers']['error_msg'] = 'Post u_id or password does not match any user.';
    } else {
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
        $response['contents']['todo_list'] = array(
            // 'high_risk_situation',
            // 'set_goal',
            // 'training_strategy',
            // 'self_evaluate',
            // 'set_goal_makeup',
            // 'training_strategy_makeup'
        );
    }
}
echo json_encode($response);
?>