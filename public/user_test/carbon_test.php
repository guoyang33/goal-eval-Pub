<?php
/*
 * carbon_test.php
 * 依GET參數產生一個新的user資料
 * is_goal 為達標與否 1為達標 0為未達標
 * is_equal 為使用時間是否維持 1為維持 0為不維持
 * is_reduce 為使用時間是否減少 1為減少 0為增加
 * exp_type 為實驗類型 training_strategy為策略組 set_goal為目標組
 * 並直接將進度設至第2週的結算階段
 */
require_once '../connect_db.php';

$FORM_ID = array(
    'high_risk_situation' => 1,
    'self_evaluate_android_set_goal_0' => 6,
    'self_evaluate_android_set_goal_1-8' => 7,
    'self_evaluate_android_set_goal_9-24' => 8,
    'self_evaluate_android_training_strategy_0' => 9,
    'self_evaluate_android_training_strategy_1-8' => 10,
    'self_evaluate_android_training_strategy_9-24' => 11,
    'training_strategy_future_tutorial_1' => 17,
    'training_strategy_advantages_tutorial_1' => 20,
    'training_strategy_misdirection_tutorial_1' => 23,
    'training_strategy_breathing_tutorial_1' => 26
);
$FORM_ANSWER = array(
    1 => '{"1":{"situation":{"when":"\u65e9\u4e0a","where":"\u5bb6\u88e1","who":"\u81ea\u5df1","what":"\u767c\u5446"},"think":"\u60f3\u8d0f","feel":"\u8d0f\u4e86\u5f88\u723d\u3001\u8f38\u4e86\u5c31\u5225\u63d0\u4e86","level":"7"},"2":{"situation":{"when":"\u4e2d\u5348","where":"\u5bb6\u88e1","who":"\u81ea\u5df1","what":"\u5403\u98ef"},"think":"\u60f3\u8d0f","feel":"\u8d0f\u4e86\u4f77\u723d\u3001\u8f38\u4e86\u5c31\u5225\u63d0\u4e86","level":"7"},"3":{"situation":{"when":"\u665a\u4e0a","where":"\u5bb6\u88e1","who":"\u81ea\u5df1","what":"\u7761\u89ba"},"think":"\u60f3\u8d0f","feel":"\u8d0f\u4e86\u5f88\u723d\u3001\u8f38\u4e86\u5c31\u5225\u63d0\u4e86","level":"7"}}',
    6 => '{"self_evaluate":{"set_goal":{"usage_time":{"pc_game":{"workday":"0","holiday":"120"},"mobile_game":{"workday":"120","holiday":"210"},"pc_social":{"workday":"120","holiday":"120"},"mobile_social":{"workday":"360","holiday":"420"},"mobile_total":{"workday":"510","holiday":"540"},"pc_online":{"workday":"300","holiday":"360"},"mobile_online":{"workday":"480","holiday":"510"},"pc_video":{"workday":"300","holiday":"360"},"mobile_video":{"workday":"180","holiday":"240"},"pc_communication":{"workday":"0","holiday":"0"},"mobile_communication":{"workday":"120"}}}}}',
    9 => '{"self_evaluate":{"training_strategy":{"usage_time":{"pc_game":{"workday":"60","holiday":"60"},"mobile_game":{"workday":"60","holiday":"60"},"pc_social":{"workday":"60","holiday":"60"},"mobile_social":{"workday":"60","holiday":"60"},"mobile_total":{"workday":"300","holiday":"300"},"pc_online":{"workday":"60","holiday":"60"},"mobile_online":{"workday":"60","holiday":"60"},"pc_video":{"workday":"60","holiday":"60"},"mobile_video":{"workday":"60","holiday":"60"},"pc_communication":{"workday":"60","holiday":"60"},"mobile_communication":{"workday":"60"}}}}}',
    17 => '{"short_term":"\u5c11\u73a9\u904a\u6232","medium_term":"\u5c11\u73a9\u904a\u6232\u8ddf\u5c11\u885d\u6d6a","long_term":"\u5c11\u7528\u624b\u6a5f"}',
    20 => '{"advantage":["\u5c0d\u773c\u775b\u597d","\u66f4\u591a\u6642\u9593\u63d0\u5347\u81ea\u5df1","\u6c92\u6709\u58de\u8655","\u6c92\u6709\u58de\u8655","\u6c92\u6709\u58de\u8655"],"disadvantage":["\u53ef\u80fd\u5f71\u97ff\u597d\u5fc3\u60c5","\u6c92\u6709\u591a\u5c11\u597d\u8655","\u6c92\u6709\u591a\u5c11\u597d\u8655","\u6c92\u6709\u591a\u5c11\u597d\u8655","\u6c92\u6709\u591a\u5c11\u597d\u8655"]}',
    23 => '{"action":"\u770b\u52d5\u6f2b"}',
    26 => '{"breathing_video_type":"male","before":"3","after":"0"}'
);

// 檢查GET參數
if (!isset($_GET['is_goal']) || !isset($_GET['is_equal']) || !isset($_GET['is_reduce']) || !isset($_GET['exp_type'])) {
    die('參數錯誤');
}

// 查詢同組別使用者遞增的id
$sth = $dbh->prepare('SELECT SUBSTRING(`exp_id`, -2) AS `id` FROM `user` WHERE `exp_id` LIKE :exp_id_like ORDER BY `id` DESC LIMIT 1;');
$sth->execute(array(
    'exp_id_like' => 'A1132' . (($_GET['exp_type'] == 'training_strategy') ? '1' : '2') . '%'
));
$exp_id_no = intval($sth->fetch(PDO::FETCH_ASSOC)['id']);

// echo $exp_id_no, '<br>';
// echo str_pad($exp_id_no + 1, 2, '0', STR_PAD_LEFT);
// die();

// 建立使用者
$sth = $dbh->prepare('INSERT INTO user (id, exp_id, exp_type, start_date, password, is_ios, test) VALUES (NULL, :exp_id, :exp_type, :start_date, :password, :is_ios, :test);');
$sth->execute(array(
    'exp_id' => 'A1132' . (($_GET['exp_type'] == 'training_strategy') ? '1' : '2') . str_pad($exp_id_no + 1, 2, '0', STR_PAD_LEFT),
    'exp_type' => $_GET['exp_type'],
    'start_date' => '2023-09-01',
    'password' => '123456',
    'is_ios' => 0,
    'test' => 1
));
$u_id = $dbh->lastInsertId();

// 輸入第0週第1週使用時間
$sth = $dbh->prepare("INSERT INTO category_usage_manual_android (id, u_id, category, usage_time, week)
                    VALUES
                        (NULL, :u_id, 'game', 420, 0),
                        (NULL, :u_id, 'social', 420, 0),
                        (NULL, :u_id, 'total', 2100, 0),
                        (NULL, :u_id, 'online', 420, 0),
                        (NULL, :u_id, 'video', 420, 0),
                        (NULL, :u_id, 'communication', 420, 0),
                        (NULL, :u_id, 'game', :usage_time_2, 1),
                        (NULL, :u_id, 'social', :usage_time_2, 1),
                        (NULL, :u_id, 'total', :usage_time_total_2, 1),
                        (NULL, :u_id, 'online', :usage_time_2, 1),
                        (NULL, :u_id, 'video', :usage_time_2, 1),
                        (NULL, :u_id, 'communication', :usage_time_2, 1)");
$sth->execute(array(
    'u_id' => $u_id,
    'usage_time_2' => ($_GET['is_goal'] == 1) ? 385 : (($_GET['is_equal'] == 1) ? 420 : (($_GET['is_reduce'] == 1) ? 415 : 630)),
    'usage_time_total_2' => ($_GET['is_goal'] == 1) ? 1925 : ((($_GET['is_equal'] == 1) ? 2100 : (($_GET['is_reduce'] == 1) ? 2075 : 3150)))
));

// 輸入第0週自我評估單
$form_id_self_evaluate = $FORM_ID[($_GET['exp_type'] == 'set_goal') ? 'self_evaluate_android_set_goal_0' : 'self_evaluate_android_training_strategy_0'];
$sth = $dbh->prepare("INSERT INTO form_answer (id, f_id, u_id, answer_sheet, week) VALUES (NULL, :f_id, :u_id, :answer_sheet, 0)");
$sth->execute(array(
    'f_id' => $form_id_self_evaluate,
    'u_id' => $u_id,
    'answer_sheet' => $FORM_ANSWER[$form_id_self_evaluate]
));

// 高風險情境
if ($_GET['exp_type'] == 'training_strategy') {
    $form_id_high_risk = $FORM_ID['high_risk_situation'];
    $sth = $dbh->prepare("INSERT INTO form_answer (id, f_id, u_id, answer_sheet, week) VALUES (NULL, :f_id, :u_id, :answer_sheet, NULL)");
    $sth->execute(array(
        'f_id' => $form_id_high_risk,
        'u_id' => $u_id,
        'answer_sheet' => $FORM_ANSWER[$form_id_high_risk]
    ));
}

// 設定目標
$sth = $dbh->prepare("INSERT INTO goal (id, u_id, week, start_date, reduce_game, reduce_video, reduce_social, reduce_communication, reduce_total)
    VALUES
        (NULL, :u_id, 1, '2023-09-08', 5, 5, 5, 5, 5)");
$sth->execute(array(
    'u_id' => $u_id
));

// 第1週策略教學
if ($_GET['exp_type'] == 'training_strategy') {
    $form_id_tutorial_future = $FORM_ID['training_strategy_future_tutorial_1'];
    $form_id_tutorial_advantages = $FORM_ID['training_strategy_advantages_tutorial_1'];
    $form_id_tutorial_misdirection = $FORM_ID['training_strategy_misdirection_tutorial_1'];
    $form_id_tutorial_breathing = $FORM_ID['training_strategy_breathing_tutorial_1'];
    $sth = $dbh->prepare("INSERT INTO form_answer (id, f_id, u_id, answer_sheet, week) VALUES
        (NULL, :f_id_future, :u_id, :answer_sheet_future, 1),
        (NULL, :f_id_advantages, :u_id, :answer_sheet_advantages, 1),
        (NULL, :f_id_misdirection, :u_id, :answer_sheet_misdirection, 1),
        (NULL, :f_id_breathing, :u_id, :answer_sheet_breathing, 1)");
    $sth->execute(array(
        'u_id' =>                           $u_id,
        'f_id_future' =>                    $form_id_tutorial_future,
        'answer_sheet_future' =>            $FORM_ANSWER[$form_id_tutorial_future],
        'f_id_advantages' =>                $form_id_tutorial_advantages,
        'answer_sheet_advantages' =>        $FORM_ANSWER[$form_id_tutorial_advantages],
        'f_id_misdirection' =>              $form_id_tutorial_misdirection,
        'answer_sheet_misdirection' =>      $FORM_ANSWER[$form_id_tutorial_misdirection],
        'f_id_breathing' =>                 $form_id_tutorial_breathing,
        'answer_sheet_breathing' =>         $FORM_ANSWER[$form_id_tutorial_breathing]
    ));
}

// 設定有效天數
// 先刪除舊的
$sth = $dbh->prepare("DELETE FROM valid_days WHERE u_id = :u_id;
INSERT INTO valid_days (id, u_id, provide_days, source_file, source_object) VALUES (NULL, :u_id, 15, 'test', 'test')");
$sth->execute(array(
    'u_id' => $u_id
));

// 跳轉畫面
header("location: ../redirect.php?u_id={$u_id}");
?>