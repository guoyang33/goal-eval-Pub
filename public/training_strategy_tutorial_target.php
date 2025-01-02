<?php

require_once 'get_user.php';
require_once 'connect_db.php';
require_once 'html_head.php';

$f_id = $_POST['f_id'];
$answer_sheet = $_POST['answer_sheet'];

$sth = $dbh->prepare("INSERT INTO `form_answer` (id, `f_id`, `u_id`, `answer_sheet`, `week`, `date`) VALUES (NULL, :f_id, :u_id, :answer_sheet, :week, :date);");
$sth->execute([
    'f_id' => $f_id,
    'u_id' => $user['id'],
    'answer_sheet' => json_encode($answer_sheet),
    'week' => $week,
    'date' => $today->format('Y-m-d')
]);

if ($dbh->lastInsertId() > 0) {
    // 顯示鼓勵小語
    // 查詢表單
    $sth = $dbh->prepare("SELECT * FROM form WHERE id = :f_id LIMIT 1");
    $sth->execute([ 'f_id' => $f_id ]);
    $form = $sth->fetch(PDO::FETCH_ASSOC);
    switch ($form['name']) {
        case 'training_strategy_tutorial_future':
            $encourage_slogan = array(
                '恭喜您完成自我目標設定，朝向目標勇往直前吧！'
            );
            break;
        case 'training_strategy_tutorial_advantages':
            $encourage_slogan = array(
                '您很用心思考怎樣對自己才是好的，很不容易！',
                '您能了解自己，知道自己需要什麼，很棒！',
                '您越來越會想後果，並且學會克制自己！'
            );
            break;
        case 'training_strategy_tutorial_misdirection':
            $encourage_slogan = array(
                '哇! 您好有執行力，真不簡單！',
                '酷欸! 您行動力好強，真有魄力！',
                '您找到方法，克服了玩遊戲的渴求！'
            );
            break;
        case 'training_strategy_tutorial_breathing':
            $encourage_slogan = array('');
            break;
        default:
    }
    echo '<div class="d-grid pt-5 px-2">
    <p>提交成功</p>
    <p class="h3">'.$encourage_slogan[rand(0, count($encourage_slogan)-1)].'</p>';
} else {
    echo '<div class="d-grid pt-5 px-2">
    <p>提交失敗，請聯絡相關人員</p>';
}
echo '<button type="button" class="btn btn-primary" onclick=location.href="redirect.php?u_id='.$user['id'].'">
返回主選單
</button>
</div>
</div>';
?>