<?php
/**
 * 第7週 第一次更新版本 新增
 * 高級權限 使用者開通狀態
 */

require_once './../../html_head.php';
require_once './../../connect_db.php';

define('SUPER_USER_TOKEN', 'superpermissionstoken');

// 驗證
if (array_key_exists('confirm_data', $_POST)) {
    if ($_POST['confirm_data']['super_user_token'] != SUPER_USER_TOKEN) {
        echo '您好像來錯地方囉，請聯絡相關人員<br>如果您是管理人員請重新登入';
        exit();
    }
} else {
    echo '您好像來錯地方囉，請聯絡相關人員<br>如果您是管理人員請重新登入';
    exit();
}

if (array_key_exists('user_passport_unlock', $_POST)) {
    if (array_key_exists('u_id', $_POST['user_passport_unlock'])) {
        $sth = $dbh->prepare("UPDATE `user` SET `password` = NULL WHERE `id` = :u_id;");
        $sth->execute(array(
            'u_id' => $_POST['user_passport_unlock']['u_id']
        ));
    }
}
if (array_key_exists('user_passport_lock', $_POST)) {
    if (array_key_exists('u_id', $_POST['user_passport_lock'])) {
        $sth = $dbh->prepare("UPDATE `user` SET `password` = :password WHERE `id` = :u_id;");
        $sth->execute(array(
            'password' => randomPassword(),
            'u_id' => $_POST['user_passport_lock']['u_id']
        ));
    }
}

if (array_key_exists('user_passport', $_POST)) {
    switch ($_POST['user_passport']['selected_option']) {
        case 'android':
            echo '<h1>安卓組</h1>';
            $selected_group = 'ANDROID_USER_PASSPORT';
            break;
        default:
            echo '<div class="d-grid pt-5 pb-3 px-2">
                <h2 class="text-danger">管理人員頁面選項錯誤，請聯絡相關人員</h2>
                <p>user_passport[selected_option]: ';
            var_dump($_POST['user_passport']['selected_option']);
            echo '</p></div>';
            exit();
    }
} else {
    echo '<script type="text/javascript">
            const FORM_TARGET_PREFIX = `USER_PASSPORT_WINDOW_${Date.now()}`;
            let children_window_counter = 0;
            function formSubmit() {
                const new_children_window_name = `${FORM_TARGET_PREFIX}_${children_window_counter}`;
                document.querySelector("#option_form").setAttribute("target", new_children_window_name);
                window.open("", new_children_window_name);
                children_window_counter++;
            }
        </script>
        <form id="option_form" action="' . basename(__FILE__) . '" method="POST" onsubmit="formSubmit();" target="">
            <input type="hidden" name="confirm_data[super_user_token]" value="' . SUPER_USER_TOKEN . '">
            <h1 class="d-flex justify-content-center">使用者開通狀態</h1>
            <h1 class="d-flex justify-content-center">請選擇要查看的組別</h1>
            <div class="d-flex justify-content-center">
                <span>
                    <label for="option_andriod" class="btn btn-outline-primary">安卓組</label>
                    <input type="submit" id="option_andriod" class="btn-check d-none" name="user_passport[selected_option]" value="android">
                </span>
                &nbsp;&nbsp;&nbsp;
                <!-- <span>
                    <label for="option_ios" class="btn btn-outline-primary">蘋果組</label>
                    <input type="submit" id="option_ios" class="btn-check d-none" name="user_passport[selected_option]" value="ios">
                </span> -->
            </div>
        </form>
    </div>
    </body>
    </html>';
    exit();
}

if ($selected_group == 'ANDROID_USER_PASSPORT') {
    $sth = $dbh->prepare("SELECT * FROM `user` WHERE SUBSTR(`exp_id`, 1, 1) = 'A' ORDER BY `exp_id` ASC;");
    $sth->execute();
    $users = $sth->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo '<div class="d-grid pt-5 pb-3 px-2">
        <h2 class="text-danger">管理人員頁面選項錯誤，請聯絡相關人員</h2>
        <p>selected_group: ';
    var_dump($selected_group);
    echo '</p></div>';
    exit();
}
echo '<form action="' . basename(__FILE__) . '" method="POST">
        <input type="hidden" name="confirm_data[super_user_token]" value="' . SUPER_USER_TOKEN . '">
        <input type="hidden" name="user_passport[selected_option]" value="' . $_POST['user_passport']['selected_option'] . '">
        <div class="d-grid pt-5 pb-3 px-2">
            <h1 class="text-center">使用者開通狀態</h1>
        </div>
        <div class="d-grid pt-5 pb-3 px-2">
            <span>
                <label for="button_reload" class="btn btn-outline-primary">重新整理</label>
                <input type="submit" id="button_reload" class="btn-check d-none">
            </span>
        </div>
        <div class="d-grid pt-5 pb-3 px-2">
            <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th scope="col">研究編號</th>
                        <th scope="col">組別</th>
                        <th scope="col">開通狀態</th>
                    </tr>
                </thead>
                <tbody>';
$row_counter = 0;
foreach ($users as $user) {
    if ($user['exp_type'] == 'training_strategy') {
        $exp_type = '策略組';
    } else if ($user['exp_type'] == 'set_goal') {
        $exp_type = '目標組';
    } else {
        $exp_type = '<span class="text-danger">未知</span>';
    }
    if ($user['password'] != null) {
        $user_status = '<span class="text-danger">已鎖定</span>';
        $reset_button = '<span>
            <label for="option_unlock_' . $row_counter . '" class="btn btn-outline-primary">解除鎖定</label>
            <input type="submit" id="option_unlock_' . $row_counter . '" class="btn-check d-none" name="user_passport_unlock[u_id]" value="' . $user['id'] . '">
        </span>';
    } else {
        $user_status = '<span class="text-success">已開通</span>';
        $reset_button = '<span>
            <label for="option_lock_' . $row_counter . '" class="btn btn-outline-primary">鎖定</label>
            <input type="submit" id="option_lock_' . $row_counter . '" class="btn-check d-none" name="user_passport_lock[u_id]" value="' . $user['id'] . '">
        </span>';
    }
    echo '<tr>
            <td>' . $user['exp_id'] . '</td>
            <td>' . $exp_type . '</td>
            <td>' . $user_status . '</td>
            <td>' . $reset_button . '</td>
            <td><a href="../../redirect.php?u_id=' . $user['id'] . '" target="_blank">使用者介面</a></td>
        </tr>';
    $row_counter++;
}
echo '</tbody>
</table>
</form>
</div>
</body>
</html>';