<?php

require_once 'connect_db.php';
require_once 'get_user.php';
require_once 'html_head.php';

$form = get_form('high_risk_situation');
$answer_sheet = $_POST['answer_sheet'];

$sth = $dbh->prepare("INSERT INTO `form_answer` (id, `f_id`, `u_id`, `answer_sheet`) VALUES (NULL, :f_id, :u_id, :answer_sheet);");
$sth->execute([
    'f_id' => $form['id'],
    'u_id' => $user['id'],
    'answer_sheet' => json_encode($answer_sheet)
]);

if ($dbh->lastInsertId() > 0) {
    echo '<div class="d-grid pt-5 px-2">
    <p>提交成功</p>';
} else {
    echo '<div class="d-grid pt-5 px-2">
    <p>提交失敗，請聯絡相關人員</p>';
}

// 新增進度(有效天數)
$insert_valid_days_status = insert_provide_valid_days($user['id'], PROVIDE_VALID_DAYS[$user['exp_type']]['high_risk_situation_form'], basename(__FILE__), 'high_risk_situation_form', $week);
if ($insert_valid_days_status['error']) {
    echo '<div class="d-grid pt-5 pb-3 px-2">
        <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO valid_days</h2>
        <p>errorInfo: ';
        var_dump($insert_valid_days_status['content']);
        echo '</p>
        </div>';
    exit();
}

echo '<button type="button" class="btn btn-primary" onclick=location.href="redirect.php?u_id='.$user['id'].'">
返回主選單
</button>
</div>
</div>';
?>