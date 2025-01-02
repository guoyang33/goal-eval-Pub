<?php
/**
 * 網頁版參與者登入
 * 已闗閉 2023-01-01: 第79-83行
 * 
 * 重啟給ios 2023-02-20: 第101~105行
 */
require_once 'connect_db.php';
require_once 'html_head.php';
require_once 'webpage_valid_exp_id_list.php';

$exp_id = $_POST['exp_id'];
$password = $_POST['password'];

if ($exp_id[0] === 'A') {
    echo '<h1>目前網頁版不開放Android編號登入請改用其他方式</h1>
    <button type="button" class="btn btn-primary" onclick="location.href = ' . "'user_login_webpage_index.php'" . ';">返回登入畫面</button>';
    exit();
    // 因不開放android登入，所以只會執行到這

    define('ACCOUNT_TYPE', '_android');
} elseif ($exp_id[0] === 'I') {
    define('ACCOUNT_TYPE', '_ios');
} else {
    echo '<h1>編號格式錯誤，請檢查編號格式</h1>
    <button type="button" class="btn btn-primary" onclick="location.href = ' . "'user_login_webpage_index.php'" . ';">返回登入畫面</button>';
    exit();
}

define('CRYPT_ARGUMENT', '$5$rounds=5000$app_3rd_2_temp$');      // 密碼加密參數:使用SHA-256加密
// define('START_DATE', '2023-02-12');

$sth = $dbh->prepare("SELECT * FROM `user` WHERE `exp_id` = :exp_id LIMIT 1");
$sth->execute([ 'exp_id' => $exp_id ]);
$user = $sth->fetch(PDO::FETCH_ASSOC);

// exp_id已存在
if ($user !== false) {
    // 檢查密碼
    if (str_replace(CRYPT_ARGUMENT, '', crypt($password, CRYPT_ARGUMENT)) == $user['password']) {       // 密碼正確
        if (in_array('change_password', array_keys($_POST))) {      // 設定新密碼
            $new_password = $_POST['new_password'];
            $check_password = $_POST['check_password'];

            if ($new_password != $check_password) {
                echo '<h3><strong class="text-danger">確認密碼與新密碼不相符，請重新輸入</strong></h3>';
                include 'user_login_webpage_change_password.php';
                exit();
                if ($new_password == '') {
                    echo '<h3><strong class="text-danger">新密碼不可為空，請重新輸入</strong></h3>';
                    include 'user_login_webpage_change_password.php';
                    exit();
                }
            }

            $sth = $dbh->prepare('UPDATE `user` SET `password` = :new_password WHERE `id` = :u_id AND `exp_id` = :exp_id AND `password` = :password LIMIT 1;');
            $sth->execute([
                'new_password' => str_replace(CRYPT_ARGUMENT, '', crypt($new_password, CRYPT_ARGUMENT)),
                'u_id' => $user['id'],
                'exp_id' => $user['exp_id'],
                'password' => $user['password']
            ]);
            if ($dbh->lastInsertId() === false) {
                echo '<div class="d-grid pt-5 pb-3 px-2">
                <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: UPDATE user password</h2>
                <p>errorInfo: ';
                var_dump($dbh->errorInfo());
                echo '</p>
                </div>';
            } else {
                echo '<h1>更新密碼成功，請回到登入頁面重新登入</h1>
                <button type="button" class="btn btn-primary" onclick="location.href = ' . "'user_login_webpage_index.php'" . ';">返回登入畫面</button>';
            }
            exit();
        } else {
            if (in_array($exp_id, array_keys(VALID_EXP_ID_LIST))) {
                if ($password == VALID_EXP_ID_LIST[$exp_id]['default_password']) {      // 如果用的是預設密碼
                    include 'user_login_webpage_change_password.php';
                    exit();
                }
            }
            // 自動轉址到對應的redirect.php
            echo '<script type="text/javascript">
                window.addEventListener("load", () => {
                    document.querySelector("#form").submit();
                });
            </script>
            <h1>登入中請稍候...</h1>
            <form id="form" action="redirect' . ACCOUNT_TYPE . '.php?u_id=' . $user['id'] . '" method="POST"></form>';
            exit();
        }
    } else {
        $response['headers']['status'] = 'EXP_ID_OR_PASSWORD_INVALID_ERROR';
        $response['headers']['error_msg'] = 'exp_id or password invalid:<br>編號或密碼錯誤，請檢查後重新登入';
    }
} else {        // exp_id沒重複
    if (strlen($exp_id) < 8) {      // 檢查編號格式
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
                            if (!in_array($exp_id, array_keys(VALID_EXP_ID_LIST))) {
                                $response['headers']['status'] = 'EXP_ID_INVALID_ERROR';
                                $response['headers']['error_msg'] = 'exp_id is not in valid exp_id list.<br>編號錯誤，請重新確認編號是否輸入正確';
                            } else {
                                if ($password != VALID_EXP_ID_LIST[$exp_id]['default_password']) {
                                    $response['headers']['status'] = 'EXP_ID_OR_PASSWORD_INVALID_ERROR';
                                    $response['headers']['error_msg'] = 'exp_id or password invalid:<br>編號或密碼錯誤，請檢查後重新登入';
                                } else {      // 建立user
                                    $addiction = ($exp_id[4] == 1) ? 1 : 0;
                                    $exp_type = ($exp_id[5] == 1) ? 'training_strategy' : 'set_goal';
                                    $password = str_replace(CRYPT_ARGUMENT, '', crypt(VALID_EXP_ID_LIST[$exp_id]['default_password'], CRYPT_ARGUMENT));
                                    $start_date = DEFAULT_START_DATE;
                                    $is_ios = (ACCOUNT_TYPE == '_ios') ? 1 : 0;;
                                    $sth = $dbh->prepare("INSERT INTO user(id, exp_id, addiction, exp_type, `start_date`, `password`, week, score, is_ios, test)
                                                        VALUE
                                                        (NULL, :exp_id, :addiction, :exp_type, :start_date, :password, 0, 0, :is_ios, 0)");
                                    $sth->bindParam('exp_id', $exp_id, PDO::PARAM_STR);
                                    $sth->bindParam('addiction', $addiction, PDO::PARAM_STR);
                                    $sth->bindParam('exp_type', $exp_type, PDO::PARAM_STR);
                                    $sth->bindParam('start_date', $start_date, PDO::PARAM_STR);
                                    $sth->bindParam('password', $password, PDO::PARAM_STR);
                                    $sth->bindParam('is_ios', $is_ios, PDO::PARAM_INT);

                                    $sth->execute();
                                    $u_id = $dbh->lastInsertId();      // 登入系統重啟給ios 2023-02-20

                                    // $u_id = 0;      // 登入系統關閉 2023-01-01
                                    if ($u_id > 0) {
                                        $sth = $dbh->prepare("SELECT * FROM `user` WHERE `id` = :u_id LIMIT 1");
                                        $sth->execute([ 'u_id' => $u_id ]);
                                        $user = $sth->fetch(PDO::FETCH_ASSOC);
                                        if ($user === false) {
                                            $response['headers']['status'] = 'INSERT_SELECT_ERROR';
                                            $response['headers']['error_msg'] = 'Cannot find user by last insert id: '.$u_id;
                                        } else {
                                            // 新增進度(有效天數)
                                            $insert_valid_days_status = insert_provide_valid_days($user['id'], 8, basename(__FILE__), '{"REASON":"PROVIDE_BEGINNING_PROGRESS_IOS", "THROUGH":"user_login_webpage.php---for_ios"}', 0);
                                            if ($insert_valid_days_status['error']) {
                                                echo '<div class="d-grid pt-5 pb-3 px-2">
                                                    <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO valid_days</h2>
                                                    <p>errorInfo: ';
                                                    var_dump($insert_valid_days_status['content']);
                                                    echo '</p>
                                                    </div>';
                                                exit();
                                            }

                                            echo '<h1>註冊完成，請繼續前往更新密碼</h1>
                                            <button type="button" class="btn btn-primary" onclick="location.href = ' . "'user_login_webpage_register.php?u_id=" . $user['id'] . "&password=" . VALID_EXP_ID_LIST[$exp_id]['default_password'] . "'" . ';">更新密碼</button>';
                                            exit();
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
    }
}
if (!empty($response['headers']['status'])) {
    echo "<h1>發生錯誤<h1><h5>Status: {$response['headers']['status']}</h5><h3>Error&nbsp;Message: {$response['headers']['error_msg']}</h3>";
    echo '<h3>如為無法解決之錯誤請聯繫研究人員</h3>';
    echo '<button type="button" class="btn btn-primary" onclick="location.href = ' . "'user_login_webpage_index.php'" . ';">返回登入畫面</button>';
}
?>