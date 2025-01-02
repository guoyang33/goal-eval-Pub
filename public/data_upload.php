<?php
/**
 * 接收上傳資料
 */
require_once 'connect_db.php';
require_once 'json_header.php';

$u_id = $_POST['u_id'];
$password = $_POST['password'];

// 檢查密碼正確
$sth = $dbh->prepare("SELECT * FROM `user` WHERE `id` = :u_id AND `password` = :password LIMIT 1");
$sth->execute([
    'u_id' => $u_id,
    'password' => $password
]);
$user = $sth->fetch(PDO::FETCH_ASSOC);
if ($user === false) {      // 沒找到user
    $response['headers']['status'] = 'USER_PASSWORD_ERROR';
    $response['headers']['error_msg'] = 'Post u_id or password does not match any user.';
} else {        // 找到user
    // 查詢 app_category
    $sth = $dbh->prepare("SELECT * FROM `app_category` WHERE 1 GROUP BY `package_name`");
    $sth->execute();
    $app_category = array();
    foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $app_category[$row['package_name']] = $row['category'];
    }

    // 查app_usage有資料的日期
    if (array_key_exists('usage_data', $_POST)) {
        $usage_data = $_POST['usage_data'];
        if (is_array($usage_data)) {
            foreach ($usage_data as $date => $usage_time_list) {
                $sth = $dbh->prepare("DELETE FROM `app_usage` WHERE `u_id` = :u_id AND `date` = :date");
                $sth->execute([
                    'u_id' => $user['id'],
                    'date' => $date
                ]);
                $sth = $dbh->prepare("INSERT INTO `app_usage` VALUES (NULL, :u_id, :package_name, :app_category, :usage_time, :date, 0)");
                $total_usage_time = 0;
                foreach ($usage_time_list as $package_name => $usage_time) {
                    $total_usage_time = $total_usage_time + intval($usage_time);
                    $category = (array_key_exists($package_name, $app_category)) ? $app_category[$package_name] : 'UNKNOW';
                    $sth->bindParam('u_id', $user['id'], PDO::PARAM_INT);
                    $sth->bindParam('package_name', $package_name, PDO::PARAM_STR);
                    $sth->bindParam('app_category', $category, PDO::PARAM_STR);
                    $sth->bindParam('usage_time', $usage_time, PDO::PARAM_INT);
                    $sth->bindParam('date', $date, PDO::PARAM_STR);
                    $sth->execute();
                }
                if ($total_usage_time >= (86400/4)*3) {     // 總使用時間超過4分之3天 可能為異常資料
                    $sth = $dbh->prepare("INSERT INTO app_usage_error(u_id, `date`, usage_time) VALUE (:u_id, :date, :usage_time)");
                    $sth->execute([
                        'u_id' => $user['id'],
                        'date' => $date,
                        'usage_time' => $total_usage_time
                    ]);
                }
            }
        }
    }
}
echo json_encode($response);
?>