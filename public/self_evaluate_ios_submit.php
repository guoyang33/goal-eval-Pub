<?php
/**
 * ios 自我評估單 接收資料
 */
require_once 'get_user.php';
require_once 'connect_db.php';
require_once 'html_head.php';
require_once './utils.php';

logPostData($user['exp_id']);

$week = $week - 1;      // 因為新版是在一週第一天填上一週的評估單

// 檢查是否重複

$form = get_form($_POST['form_name']);
// var_dump($form);
$sth = $dbh->prepare("SELECT * FROM form_answer WHERE u_id = :u_id AND week = :week AND f_id = :f_id LIMIT 1");
$sth->execute([
    'u_id' => $user['id'],
    'week' => $week,
    'f_id' => $form['id']
]);
$form_answer = $sth->fetch(PDO::FETCH_ASSOC);
if ($form_answer === false) {
    $sth = $dbh->prepare("INSERT INTO form_answer (`id`, `f_id`, `u_id`, `week`, `answer_sheet`, `date`) VALUE (NULL, :f_id, :u_id, :week, :answer_sheet, :date)");
    $sth->execute([
        'f_id' => $form['id'],
        'u_id' => $user['id'],
        'week' => $week,
        'answer_sheet' => json_encode($_POST['answer_sheet']),
        'date' => $today->format('Y-m-d')
    ]);
    if ($dbh->lastInsertId() === false) {
        echo '<div class="d-grid pt-5 px-2">
        <p>提交失敗，請聯絡相關人員</p>';
        echo '<button type="button" class="btn btn-primary" onclick=location.href="redirect_ios.php?u_id='.$user['id'].'">
        返回主選單
        </button>
        </div>';
        die();
    }

    // 寫入Ios手動填入使用時間的資料表
    $sql_value_string_array = array();
    $sql_value_array = array();
    foreach ($_POST['answer_sheet']['self_evaluate'][$user['exp_type']]['usage_time'] as $category => $day_in_week) {
        if (strpos($category, 'mobile_') === 0) {
            $week_usage_time = 0;
            foreach ($day_in_week as $day_usage_time) {
                $week_usage_time += $day_usage_time;
            }
            array_push($sql_value_string_array, '(NULL,?,?,?,?,?,NULL)');
            array_push($sql_value_array, $user['id'], str_replace('mobile_', '', $category), $week_usage_time, $today->format('Y-m-d'), $week);
        }
    }
    $sth = $dbh->prepare("INSERT INTO `category_usage_manual_ios` (`id`, `u_id`, `category`, `usage_time`, `submit_date`, `week`, `makeup`) VALUE " . implode(',', $sql_value_string_array));
    $sth->execute($sql_value_array);
    if ($dbh->lastInsertId() === false) {
        echo '<div class="d-grid pt-5 px-2">
        <p>提交失敗，請聯絡相關人員</p>';
        echo '<button type="button" class="btn btn-primary" onclick=location.href="redirect_ios.php?u_id='.$user['id'].'">
        返回主選單
        </button>
        </div>';
        die();
    }
	
	// 新增進度(有效天數)                                               // v7_1新增:如果所填的評估單為第8週的，將複寫PROVIDE_VALID_DAYS的數值從0變為7
	$insert_valid_days_status = insert_provide_valid_days($user['id'], (($week == 8) ? 7 : PROVIDE_VALID_DAYS[$user['exp_type']][$_POST['form_name']]), basename(__FILE__), $_POST['form_name'], $week);
	if ($insert_valid_days_status['error']) {
		echo '<div class="d-grid pt-5 pb-3 px-2">
			<h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO valid_days</h2>
			<p>errorInfo: ';
			var_dump($insert_valid_days_status['content']);
			echo '</p>
			</div>';
		exit();
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