<?php

use Cyouliao\Goaleval\Log;

include_once './../vendor/autoload.php';
require_once 'connect_db.php';
require_once 'html_head.php';

session_start();

// 維修用
/*
echo '<h1>系統維修中，請稍候維修完成</h1>';
exit();
*/

$userId = $_SESSION['user']['id'] ?? null;
$stdId = $_SESSION['user']['std_id'] ?? null;
$expId = $_SESSION['user']['exp_id'] ?? null;

if ($userId != null) {
    $sth = $dbh->prepare("SELECT * FROM `user` WHERE `id` = :u_id LIMIT 1;");
    $sth->execute([ 
        'u_id' => $userId
    ]);
} else if ($stdId != null) {
    $sth = $dbh->prepare("SELECT * FROM `user` WHERE `std_id` = :std_id LIMIT 1;");
    $sth->execute([ 
        'std_id' => $stdId
    ]);
} else if ($expId != null) {
    $sth = $dbh->prepare("SELECT * FROM `user` WHERE `exp_id` = :exp_id LIMIT 1;");
    $sth->execute([ 
        'exp_id' => $expId
    ]);
} else {
    echo '
    <script type="text/javascript">
        window.alert("您尚未登入，將導向至登入頁面");
        window.location.href = "/login.php";
    </script>
    ';
    die();
    // 檢查HTTP Request parameter
    if (in_array('exp_id', array_keys($_GET))) {        // 以exp_id查詢
        $sth = $dbh->prepare("SELECT * FROM `user` WHERE `exp_id` = :exp_id LIMIT 1;");
        $sth->execute([ 
            'exp_id' => $_GET['exp_id'] 
        ]);
    } else if (in_array('exp_id', array_keys($_POST))) {       // 以exp_id查詢
        $sth = $dbh->prepare("SELECT * FROM `user` WHERE `exp_id` = :exp_id LIMIT 1;");
        $sth->execute([ 
            'exp_id' => $_POST['exp_id'] 
        ]);
    } else if (in_array('u_id', array_keys($_GET))) {       // 以u_id查詢
        $sth = $dbh->prepare("SELECT * FROM `user` WHERE `id` = :u_id LIMIT 1;");
        $sth->execute([ 
            'u_id' => $_GET['u_id'] 
        ]);
    } else if (in_array('u_id', array_keys($_POST))) {       // 以u_id查詢
        $sth = $dbh->prepare("SELECT * FROM `user` WHERE `id` = :u_id LIMIT 1;");
        $sth->execute([ 
            'u_id' => $_POST['u_id'] 
        ]);
    } else {        // 沒有相關參數
        echo '<div class="d-grid pt-5 pb-3 px-2">
        <h2 class="text-danger">未知的使用者或參與者編號格式有誤</h2>
        </div>';
        die();
    }
    // echo '您好像來錯地方囉，請聯絡相關人員';
    // die();
}


$user = $sth->fetch(PDO::FETCH_ASSOC);
if ($user === false) {      // 沒資料
    echo '<div class="d-grid pt-5 pb-3 px-2">
        <h2 class="text-danger">未知的使用者或參與者編號格式有誤</h2>
        </div>';
    die();

    /*
    // 若是iOS組的則支援新增user資料
    // 檢查格式
    if ($exp_id[0] != 'I') {        // 前綴 手機
        echo '<div class="d-grid pt-5 pb-3 px-2">
        <h2 class="text-danger">參與者編號格式有誤</h2>
        <p>exp_id[0]: ';
        var_dump($exp_id[0]);
        echo '</p>
        </div>';
    } else {
        if (strlen($exp_id) != 8) {     // 長度
            echo '<div class="d-grid pt-5 pb-3 px-2">
            <h2 class="text-danger">參與者編號格式有誤</h2>
            <p>strlen: ';
            var_dump(strlen($exp_id));
            echo '</p>
            </div>';
        } else {
            if (substr($exp_id, 1, 3) != YEAR_NO) {     // 前綴 年
                echo '<div class="d-grid pt-5 pb-3 px-2">
                <h2 class="text-danger">參與者編號格式有誤</h2>
                <p>substr(1,3): ';
                var_dump(substr($exp_id, 1, 3));
                echo '</p>
                </div>';
            } else {
                if (!in_array(substr($exp_id, 4, 2), array('11', '12', '21', '22'))) {      // 成癮|非成癮 策略|目標
                    echo '<div class="d-grid pt-5 pb-3 px-2">
                    <h2 class="text-danger">參與者編號格式有誤</h2>
                    <p>substr(4,2): ';
                    var_dump(substr($exp_id, 4, 2));
                    echo '</p>
                    </div>';
                } else {
                    if (!is_numeric(substr($exp_id, 6, 2))) {
                        echo '<div class="d-grid pt-5 pb-3 px-2">
                        <h2 class="text-danger">參與者編號格式有誤</h2>
                        <p>substr(6,2): ';
                        var_dump(substr($exp_id, 6, 2));
                        echo '</p>
                        </div>';
                    } else {
                        // 新增至 user
                        $addiction = ($exp_id[4] == 1) ? 1 : 0;
                        $exp_type = ($exp_id[5] == 1) ? 'training_strategy' : 'set_goal';
                        $password = randomPassword();

                        $sql = "INSERT INTO user(id, exp_id, addiction, exp_type, `start_date`, `password`, `week`, score, is_ios, test)
                                VALUE
                                (NULL, :exp_id, :addiction, :exp_type, :start_date, :password, 0, 0, 1, 0)";
                        $sth = $dbh->prepare($sql);
                        $sth->execute([
                            'exp_id' =>     $exp_id,
                            'addiction' =>  $addiction,
                            'exp_type' =>   $exp_type,
                            'start_date' => DEFAULT_START_DATE,
                            'password' =>   $password
                        ]);
                        if ($dbh->lastInsertId() > 0) {
                            header("Location: redirect_ios.php?exp_id={$exp_id}");
                        }
                    }
                }
            }
        }
    }
    */
    
} else {                    // 有user資料
    if (is_null($user['password'])) {
        session_destroy();
        header('Location: /login.php');
    }
    echo '<p class="text-secondary">你的編號為:' . $user['exp_id'] . '</p>';

    // 計算日期
    // 與原本的start_date相減計算實際過了幾天
    $start_date = new DateTimeImmutable($user['start_date']);
    $today = new DateTimeImmutable(date('Y-m-d'));
    $real_date_interval = $start_date->diff($today);

    // 查詢有效天數來與實際日期比較
    $sth = $dbh->prepare('SELECT SUM(`provide_days`) AS valid_days FROM `valid_days` WHERE `u_id` = :u_id;');
    $sth->execute([ 'u_id' => $user['id'] ]);
    $valid_days = intval($sth->fetch(PDO::FETCH_COLUMN, 0));      // null將自動轉成0
    $valid_date_interval = $start_date->diff($start_date->modify("{$valid_days} day"));      // 有效時間與開始時間的差距期間

    if ($user['test'] == 1 || Log::isVerboseMode()) {
        echo '<h3>資料庫內資料：</h3>';
        echo "<h3>實際天數：{$real_date_interval->format('%r%a')}天</h3>";
        echo "<h3>有效天數：{$valid_date_interval->format('%r%a')}天</h3>";
    }

    $date_interval = $start_date->diff($start_date->modify((min(intval($real_date_interval->format('%r%a')), $valid_days)) . ' day'));

    if ($date_interval->invert == 1) {      // today < start_date
        $week = -1;
    } else {                                // today >= start_date
        /*
        // 從goal(設定目標)取得最新週數
        $sth = $dbh->prepare("SELECT MAX(week) FROM goal WHERE u_id = :u_id");
        $sth->execute([ 'u_id' => $user['id'] ]);
        $week = intval($sth->fetch(PDO::FETCH_COLUMN, 0));      // null將自動轉成0
        */

        if (gettype($date_interval->days / 7) == 'integer') {      // 第幾周
            $week = ($date_interval->days / 7) - 1;
        } else {
            $week = floor($date_interval->days / 7);
        }
        $progress_timeline_position = array(
            'week' => $week,
            'in_week_date_count' => (($date_interval->days % 7) ? ($date_interval->days % 7) : 7)      // 第幾天
        );
    }

    // 測試用
    if ($user['test'] == 1) {
        if (key_exists('today', $_GET) && key_exists('valid_days', $_GET)) {
            $today = new DateTimeImmutable($_GET['today']);
            $real_date_interval = $start_date->diff($today);

            $valid_days = intval($_GET['valid_days']);
            $valid_date_interval = $start_date->diff($start_date->modify("{$valid_days} day"));

            echo '<h3>測試模式資料：</h3>';
            echo "<h3>實際天數：{$real_date_interval->format('%r%a')}天</h3>";
            echo "<h3>有效天數：{$valid_date_interval->format('%r%a')}天</h3>";

            $date_interval = $start_date->diff($start_date->modify((min(intval($real_date_interval->format('%r%a')), $valid_days)) . ' day'));

            if ($date_interval->invert == 1) {      // today < start_date
                $week = -1;
            } else {                                // today >= start_date
                if (gettype($date_interval->days / 7) == 'integer') {      // 第幾周
                    $week = ($date_interval->days / 7) - 1;
                } else {
                    $week = floor($date_interval->days / 7);
                }
                $progress_timeline_position = array(
                    'week' => $week,
                    'in_week_date_count' => (($date_interval->days % 7) ? ($date_interval->days % 7) : 7)      // 第幾天
                );
            }
        }
    }
    
    if (!isset($progress_timeline_position)) {
        $progress_timeline_position = array(
            'week' => -1,
            'in_week_date_count' => 0
        );
    }
    echo '<p class="text-secondary">計畫開始第' . "{$date_interval->format('%r%a')}天(第{$progress_timeline_position['week']}週，第{$progress_timeline_position['in_week_date_count']}天)" . '</p>';
}

Log::d("week: $week");
