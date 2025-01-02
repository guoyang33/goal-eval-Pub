<?php
/**
 * 自我評估單 接收資料 iOS版
 */
require_once 'get_user.php';
require_once 'connect_db.php';
require_once 'html_head.php';

$fake_app_list = array(
    'game' => array(
        'package_name' => 'fake.ios.game',
        'app_category' => '遊戲'
    ),
    'social' => array(
        'package_name' => 'fake.ios.social',
        'app_category' => '社交'
    ),
    'entertainment' => array(
        'package_name' => 'fake.ios.entertainment',
        'app_category' => '娛樂'
    ),
);
$fake_app_other = array(
    'package_name' => 'fake.ios.other',
    'app_category' => '其他'
);

if (!key_exists('form_name', $_POST)) {
    echo 'form_name error';
} else {
    $form_name = $_POST['form_name'];
    if (!key_exists('answer_sheet', $_POST)) {
        echo 'answer_sheet error';
    } else {
        $answer_sheet = $_POST['answer_sheet'];

        // 查詢form資料
        $form = get_form($_POST['form_name']);

        if ($form === false) {
            echo 'form not exists';
        } else {
            // 查詢form_answer
            $sth = $dbh->prepare("SELECT * FROM form_answer WHERE u_id=:u_id AND week=:week AND f_id=:f_id LIMIT 1");
            $sth->execute([
                'u_id' => $user['id'],
                'week' => $week,
                'f_id' => $form['id']
            ]);
            $form_answer = $sth->fetch(PDO::FETCH_ASSOC);
            if ($form_answer === false) {        // form_answer已存在
                // 新增form_answer資料
                $sth = $dbh->prepare("INSERT INTO form_answer(id, f_id, u_id, `week`, answer_sheet, `date`) VALUE (NULL, :f_id, :u_id, :week, :answer_sheet, :date)");
                $sth->execute([
                    'f_id' => $form['id'],
                    'u_id' => $user['id'],
                    'week' => $week,
                    'answer_sheet' => json_encode($answer_sheet),
                    'date' => $today->format('Y-m-d')
                ]);

                if ($dbh->lastInsertId() === false) {     // insert失敗
                    echo '<div class="d-grid pt-5 pb-3 px-2">
                    <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO form_answer</h2>
                    <p>errorInfo: ';
                    var_dump($dbh->errorInfo());
                    echo '</p>
                    </div>';
                    die();
                } else {        // insert成功
                    $week_start_date = $start_date->add(new DateInterval('P'.($week*7).'D'));
                    $sth = $dbh->prepare("INSERT INTO app_usage(id, u_id, package_name, app_category, usage_time, `date`) VALUE (NULL, :u_id, :package_name, :app_category, :usage_time, :date)");
                    foreach ($answer_sheet['usage_time'] as $day => $usage_time) {
                        $date = $week_start_date->add(new DateInterval("P{$day}D"));
                        $usage_time_other = $usage_time['total']['hour'] * 3600 + $usage_time['total']['minute'] * 60;
                        foreach ($fake_app_list as $category => $fake_app) {
                            $usage_time_seconds = $usage_time[$category]['hour'] * 3600 + $usage_time[$category]['minute'] * 60;
                            if ($usage_time_seconds > 0) {
                                $usage_time_other = $usage_time_other - $usage_time_seconds;
                                $sth->execute([
                                    'u_id' => $user['id'],
                                    'package_name' => $fake_app['package_name'],
                                    'app_category' => $fake_app['app_category'],
                                    'usage_time' => $usage_time_seconds,
                                    'date' => $date->format('Y-m-d')
                                ]);
                                if ($dbh->lastInsertId() === false) {
                                    echo '<div class="d-grid pt-5 pb-3 px-2">
                                    <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO app_usage</h2>
                                    <p>errorInfo: ';
                                    var_dump($dbh->errorInfo());
                                    echo '</p>
                                    </div>';
                                    die();
                                }
                            }
                        }
                        if ($usage_time_other > 0) {
                            $sth->execute([
                                'u_id' => $user['id'],
                                'package_name' => $fake_app_other['package_name'],
                                'app_category' => $fake_app_other['app_category'],
                                'usage_time' => $usage_time_other,
                                'date' => $date->format('Y-m-d')
                            ]);
                            if ($dbh->lastInsertId() === false) {
                                echo '<div class="d-grid pt-5 pb-3 px-2">
                                <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO app_usage</h2>
                                <p>errorInfo: ';
                                var_dump($dbh->errorInfo());
                                echo '</p>
                                </div>';
                                die();
                            }
                        }
                    }
                }
            }

            echo '<div class="d-grid pt-5 pb-3 px-2">
            <h2>感謝！您的參與將會有助改善您的 3C 使用行為和對研究和人類有很大的貢獻！</h2>
            <p>請回到首頁，設定下週減量目標和檢查今天使用手機時間與排名！</p>
            <button type="button" class="btn btn-primary" onclick=location.href="redirect_ios.php?u_id='.$user['id'].'">
            返回主選單
            </button>
            </div>';

            $week++;
            include 'puzzle.php';

        }
    }
}
?>