<?php
/**
 * 參與者登入
 * 已闗閉 2023-01-01: 第79-83行
 */
require_once 'connect_db.php';
require_once 'json_header.php';

const YEAR_NO = 113;

$exp_id = $_POST['exp_id'];
$password = $_POST['password'];

$sth = $dbh->prepare("SELECT * FROM `user` WHERE `exp_id` = :exp_id LIMIT 1");
$sth->execute([ 'exp_id' => $exp_id ]);
$user = $sth->fetch(PDO::FETCH_ASSOC);

// exp_id已存在
if ($user !== false) {
    // 檢查密碼
    if ($password == $user['password']) {       // 密碼正確
        // 更新密碼
        while (true) {
            $new_password = randomPassword();
            if ($new_password != $password) {
                break;
            }
        }
        // 更新資料庫
        $sth = $dbh->prepare("UPDATE `user` SET `password` = :password WHERE `id` = :u_id");
        $sth->execute([
            'password' => $new_password,
            'u_id' => $user['id']
        ]);
        // 回傳user資訊
        $user['password'] = $new_password;
        $response['contents']['user'] = $user;
    } else {
        $response['headers']['status'] = 'PASSWORD_ERROR';
        $response['headers']['error_msg'] = 'Password does not match to user with exp_id('.$exp_id.').';
    }
} else {        // exp_id沒重複
    // 檢查編號格式
    if (strlen($exp_id) != 8) {
        $response['headers']['status'] = 'EXP_ID_FORMAT_ERROR';
        $response['headers']['error_msg'] = 'exp_id got bad format: length.';
    } else {
        if (!in_array($exp_id[0], ['A', 'I'])) {    // 首字
            $response['headers']['status'] = 'EXP_ID_FORMAT_ERROR';
            $response['headers']['error_msg'] = 'exp_id got bad format: 1st character.';
        } else {
            if (substr($exp_id, 1, 3) != YEAR_NO) {     // 年份
                $response['headers']['status'] = 'EXP_ID_FORMAT_ERROR';
                $response['headers']['error_msg'] = 'exp_id got bad format: 2-4 characters.';
            } else {
                if (!in_array($exp_id[4], [1,2])) {        // 1:成癮 | 2:非成癮
                    $response['headers']['status'] = 'EXP_ID_FORMAT_ERROR';
                    $response['headers']['error_msg'] = 'exp_id got bad format: 5th character.';
                } else {
                    if (!in_array($exp_id[5], [1,2])) {     // 1:策略調控組 | 2:目標調控組
                        $response['headers']['status'] = 'EXP_ID_FORMAT_ERROR';
                        $response['headers']['error_msg'] = 'exp_id got bad format: 6th character.';
                    } else {
                        if (!is_numeric(substr($exp_id, 6, 2))) {       // 流水號
                            $response['headers']['status'] = 'EXP_ID_FORMAT_ERROR';
                            $response['headers']['error_msg'] = 'exp_id got bad format: 7-8 characters.';
                        } else {        
                            // 建立user
                            $addiction = ($exp_id[4] == 1) ? 1 : 0;
                            $exp_type = ($exp_id[5] == 1) ? 'training_strategy' : 'set_goal';
                            $password = randomPassword();
                            $start_date = '2022-12-26';
                            $sth = $dbh->prepare("INSERT INTO user(id, exp_id, addiction, exp_type, `start_date`, `password`, week, score, is_ios, test)
                                                VALUE
                                                (NULL, :exp_id, :addiction, :exp_type, :start_date, :password, 0, 0, 0, 0)");
                            $sth->bindParam('exp_id', $exp_id, PDO::PARAM_STR);
                            $sth->bindParam('addiction', $addiction, PDO::PARAM_STR);
                            $sth->bindParam('exp_type', $exp_type, PDO::PARAM_STR);
                            $sth->bindParam('start_date', $start_date, PDO::PARAM_STR);
                            $sth->bindParam('password', $password, PDO::PARAM_STR);
                            // $sth->execute();

                            // $u_id = $dbh->lastInsertId();

                            $u_id = 0;      // 登入系統關閉 2023-01-01
                            if ($u_id > 0) {
                                $sth = $dbh->prepare("SELECT * FROM `user` WHERE `id` = :u_id LIMIT 1");
                                $sth->execute([ 'u_id' => $u_id ]);
                                $user = $sth->fetch(PDO::FETCH_ASSOC);
                                if ($user === false) {
                                    $response['headers']['status'] = 'INSERT_SELECT_ERROR';
                                    $response['headers']['error_msg'] = 'Cannot find user by last insert id: '.$u_id;
                                } else {
                                    $response['contents']['user'] = $user;
                                }
                            } else {
                                $response['headers']['status'] = 'INSERT_ERROR';
                                $response['headers']['error_msg'] = 'Failed to insert user.';
                            }
                        }
                    }
                }
            }
        }
    }
}
// $response['contents'] = $_POST;
echo json_encode($response);
?>