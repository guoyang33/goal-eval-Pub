<?php
/*
 * 參與者登入API 112版
 * 僅限安卓版APP使用
 * 安卓手機參與者僅需輸入研究編號即可登入，登入後會根據情況回傳登入狀態碼(login_code)
 *  0: 登入失敗(研究編號格式錯誤或其他錯誤)
 *  1: 登入成功(包含研究編號不存在，新增使用者)
 *  2: 登入待確認(研究編號已存在，有可能是來自不同裝置的登入，需研究人員後台確認)，此登入狀態的手機APP會定時向伺服器發送登入狀態碼請求，直到狀態碼變為1
 */
require_once 'connect_db.php';
require_once 'json_header.php';

if (!key_exists('exp_id', $_GET)) {
    $response['headers']['status'] = 'EXP_ID_NOT_DEFINED';
    $response['headers']['error_msg'] = "登入失敗：研究編號不得為空";
} else {
    $exp_id = $_GET['exp_id'];
    // 檢查研究編號格式
    if (exp_id_format_check_android($exp_id) === false) {
        $response['headers']['status'] = 'EXP_ID_FORMAT_ERROR';
        $response['headers']['error_msg'] = "登入失敗：研究編號格式錯誤\n請聯絡研究人員\n(exp_id: ".$exp_id.")";
    } else {
        // 查詢研究編號是否存在
        $sth = $dbh->prepare("SELECT * FROM `user` WHERE `exp_id` = :exp_id LIMIT 1");
        $sth->execute(array('exp_id' => $exp_id));
        $user = $sth->fetch(PDO::FETCH_ASSOC);
        if ($user === false) {      // 研究編號尚未註冊
            // 新增user
            $sth = $dbh->prepare("INSERT INTO `user`(`id`, `exp_id`, `addiction`, `exp_type`, `start_date`, `password`, `week`, `score`, `is_ios`, `test`)
                                  VALUE
                                  (NULL, :exp_id, :addiction, :exp_type, :start_date, :password, 0, 0, 0, 0)");
            $sth->execute(array(
                'exp_id' => $exp_id,
                'addiction' => addiction_check($exp_id),
                'exp_type' => exp_type_check($exp_id),
                'start_date' => DEFAULT_START_DATE,
                'password' => randomPassword()
            ));
            $u_id = $dbh->lastInsertId();
            if ($u_id > 0) {        // 新增成功
                $sth = $dbh->prepare("SELECT * FROM `user` WHERE `id` = :u_id LIMIT 1");
                $sth->execute(array('u_id' => $u_id));
                $user = $sth->fetch(PDO::FETCH_ASSOC);
                if ($user === false) {      // 新增成功但查詢失敗
                    $response['headers']['status'] = 'INSERT_SELECT_ERROR';
                    $response['headers']['error_msg'] = "登入失敗：user 新增成功但查詢失敗\n請聯絡研究人員\n(u_id: ".$u_id.")";
                } else {
                    $response['contents']['login_code'] = 1;
                    $response['contents']['user'] = $user;
                }
            } else {        // 新增失敗
                $response['headers']['status'] = 'INSERT_ERROR';
                $response['headers']['error_msg'] = "登入失敗：user 新增失敗\n請聯絡研究人員\n(exp_id: ".$exp_id.")";
            }
        } else {        // 研究編號已存在
            // 檢查密碼
            if ($user['password'] != null) {        // 研究人員尚未確認
                $response['contents']['login_code'] = 2;
                $response['contents']['user'] = $user;
            } else {        // 研究人員已確認
                // 更新密碼
                $sth = $dbh->prepare("UPDATE `user` SET `password` = :password WHERE `id` = :u_id");
                $sth->execute(array(
                    'password' => randomPassword(),
                    'u_id' => $user['id']
                ));
                $response['contents']['login_code'] = 1;
                $response['contents']['user'] = $user;
            }
        }
    }
}
echo json_encode($response);
?>