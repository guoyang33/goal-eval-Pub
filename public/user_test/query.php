<?php
/*
 * user_test/query.php
 * 查詢使用者資料
 * 若不存在則自動新增
 */

define('TEST_USER_DEFAULT_ADDICT', 1);    // 預設成癮組別: 成癮組
define('TEST_USER_DEFAULT_PASSWORD', '123456');    // 預設密碼

print_r($_POST);
if (!isset($_POST['login_type'])) {
    echo 'no login_type';
    exit;
} else {
    require_once '../connect_db.php';
    switch ($_POST['login_type']) {
        case 'login':
            echo 'login';
            if (!exp_id_format_check($_POST['exp_id'])) {
                echo '<br>研究編號格式錯誤';
                echo '<br><a href="index.php">回到登入頁面</a>';
                exit;
            } else {
                $sth = $dbh->prepare("SELECT * FROM `user` WHERE `exp_id` = :exp_id LIMIT 1;");
                $sth->execute(array(
                    'exp_id' => $_POST['exp_id']
                ));
                $user = $sth->fetch(PDO::FETCH_ASSOC);
                if ($user === false) {
                    echo '<br>no user';
                    echo '<br><a href="index.php">回到登入頁面</a>';
                    exit;
                } else {
                    $u_id = $user['id'];
                    header("location: main_panel.php?u_id={$u_id}");
                }
            }
            break;
        case 'create':
            echo 'create';
            if (!isset($_POST['device'])) {
                echo '<br>手機類別未選擇';
                echo '<br><a href="index.php">回到登入頁面</a>';
                exit;
            } else {
                if (!in_array($_POST['device'], array('ios', 'android'))) {
                    echo '<br>手機類別錯誤';
                    echo '<br><a href="index.php">回到登入頁面</a>';
                    exit;
                } else {
                    if (!isset($_POST['exp_type'])) {
                        echo '<br>實驗組別未選擇';
                        echo '<br><a href="index.php">回到登入頁面</a>';
                        exit;
                    } else {
                        if (!in_array($_POST['exp_type'], array('training_strategy', 'set_goal'))) {
                            echo '<br>實驗組別錯誤';
                            echo '<br><a href="index.php">回到登入頁面</a>';
                            exit;
                        } else {
                            $device = ($_POST['device'] == 'ios') ? 'I' : 'A';
                            $exp_type = ($_POST['exp_type'] == 'training_strategy') ? '1' : '2';
                            // 查詢最後一筆研究編號
                            $sth = $dbh->prepare("SELECT SUBSTR(`exp_id`, -2, 2) AS `exp_id_no` FROM `user` WHERE `exp_id` LIKE :exp_id ORDER BY `exp_id_no` DESC LIMIT 1;");
                            $sth->execute(array(
                                'exp_id' => "{$device}" . YEAR_NO . TEST_USER_DEFAULT_ADDICT . "{$exp_type}%"
                            ));
                            $last_exp_id_no = intval($sth->fetch(PDO::FETCH_COLUMN, 0));
                            $new_exp_id = "{$device}" . YEAR_NO . TEST_USER_DEFAULT_ADDICT . "{$exp_type}" . sprintf("%02d", ($last_exp_id_no + 1));
                            var_dump($new_exp_id);
                            $sth = $dbh->prepare('INSERT INTO `user` (`id`, `exp_id`, `exp_type`, `start_date`, `password`, `is_ios`, `test`) VALUES (NULL, :exp_id, :exp_type, :start_date, :password, :is_ios, :test);');
                            $sth->execute(array(
                                'exp_id' => $new_exp_id,
                                'exp_type' => $_POST['exp_type'],
                                'start_date' => DEFAULT_START_DATE,
                                'password' => '123456',
                                'is_ios' => ($_POST['device'] == 'ios') ? 1 : 0,
                                'test' => 1
                            ));
                            $u_id = $dbh->lastInsertId();
                            if ($u_id === false) {
                                echo '<br>新增使用者失敗';
                                echo '<br><a href="index.php">回到登入頁面</a>';
                                exit;
                            } else {
                                header("location: main_panel.php?u_id={$u_id}");
                            }
                        }
                    }
                }
            }
            break;
        default:
            echo 'login_type error';
            exit;
    }
}
?>