<?php
/**
 * v7_1新增
 * 顯示/取得成就(拼圖) 函式庫
 */



define('APP_CATEGORY', array(
    '_android' => array(
        'game',
        'video',
        'social',
        'communication',
        'total'
    ),
    '_ios' => array(
        'game',
        'social',
        'entertainment',
        'total'
    )
));

define('APP_CATEGORY_INFORMATION', array(
    'game' => array('name_zh' => '遊戲'),
    'video' => array('name_zh' => '影音播放與編輯'),
    'social' => array('name_zh' => '社交'),
    'communication' => array('name_zh' => '通訊'),
    'entertainment' => array('name_zh' => '娛樂'),
    'total' => array('name_zh' => '總使用時間')
));

define('GET_SELF_EVALUATE_FORM_ID_SQL_TEMPLATE', 'SELECT `id` FROM `form` WHERE `name` LIKE "self_evaluate%1$s_%2$s%%";');
define('GET_SELF_EVALUATE_COMPLETED_COUNT_SQL_TEMPLATE', 'SELECT COUNT(*) FROM `form_answer` WHERE `u_id` = :u_id AND `f_id` IN (%1$s);');

define('GET_TRAINING_STRATEGY_FORM_ID_SQL_TEMPLATE', 'SELECT `id` FROM `form` WHERE `name` LIKE "training_strategy_%%";');
define('GET_TRAINING_STRATEGY_COMPLETED_COUNT_SQL_TEMPLATE', 'SELECT COUNT(*) FROM `form_answer` WHERE `u_id` = :u_id AND `f_id` IN (%1$s);');

function get_goal_self_evaluate($dbh, $object_device, $u_id, $exp_type) {
    $puzzle_name = array('self_evaluate_0-7', 'self_evaluate_9-16', 'self_evaluate_17-24');
    $got_puzzle = array();

    // 先以該使用者的裝置組及調控組別來取得會填的表單id
    $sth = $dbh->prepare(sprintf(GET_SELF_EVALUATE_FORM_ID_SQL_TEMPLATE, $object_device, $exp_type));
    $sth->execute();
    $form_ids = implode(',', $sth->fetchAll(PDO::FETCH_COLUMN, 0));

    // 再以取得的ID來取得填完多少個自我評估單
    $sth = $dbh->prepare(sprintf(GET_SELF_EVALUATE_COMPLETED_COUNT_SQL_TEMPLATE, $form_ids));
    $sth->execute(['u_id' => $u_id]);
    $completed_forms = intval($sth->fetch(PDO::FETCH_COLUMN, 0));

    $puzzle_counter = 0;
    while ($completed_forms > 0) {
        if ($completed_forms >= 8) {
            array_push($got_puzzle, "img/{$puzzle_name[$puzzle_counter]}.jpg");
        } else {
            array_push($got_puzzle, "img/{$puzzle_name[$puzzle_counter]}_{$completed_forms}.jpg");
        }

        $completed_forms-=8;
        $puzzle_counter++;
    }

    return $got_puzzle;
}

function get_goal_training_strategy($dbh, $u_id) {
    $got_goal = array();

    // 先取得所有策略訓練表單的id
    $sth = $dbh->prepare(GET_TRAINING_STRATEGY_FORM_ID_SQL_TEMPLATE);
    $sth->execute();
    $form_ids = implode(',', $sth->fetchAll(PDO::FETCH_COLUMN, 0));

    // 再以取得的ID來取得完成的策略訓練數量
    $sth = $dbh->prepare(sprintf(GET_TRAINING_STRATEGY_COMPLETED_COUNT_SQL_TEMPLATE, $form_ids));
    $sth->execute(['u_id' => $u_id]);
    $completed_forms = intval($sth->fetch(PDO::FETCH_COLUMN, 0));

    $completed_rounds = floor($completed_forms / 4);
    $completed_extra = $completed_forms % 4;
    for ($img_counter = 1 ; $img_counter < 5 ; $img_counter++) {
        array_push($got_goal, array('img' => "img/training_strategy_finish_{$img_counter}.png", 'times' => (($img_counter <= $completed_extra) ? ($completed_rounds + 1) : $completed_rounds)));
    }

    return $got_goal;
}

function get_goal_category_score($dbh, $object_device, $u_id) {
    $category_goal_srting_array = array();
    $got_puzzle = array();

    foreach (APP_CATEGORY[$object_device] as $category) {
        array_push($category_goal_srting_array, "FLOOR((SUM(`score_{$category}`) + SUM(IFNULL(`score_save_{$category}`, 0))) / 100) AS goal_{$category}");    
    }

    $sth = $dbh->prepare('SELECT ' . implode(',', $category_goal_srting_array) . ' FROM `goal` WHERE `u_id` = :u_id;');
    $sth->execute(['u_id' => $u_id]);
    $category_goal_result = $sth->fetch(PDO::FETCH_ASSOC);
    foreach (APP_CATEGORY[$object_device] as $category) {
        array_push($got_puzzle, array('img' => 'img/goal_' . $category . '_' . intval($category_goal_result["goal_{$category}"]) . '.jpg', 'name_zh' => APP_CATEGORY_INFORMATION[$category]['name_zh']));
    }

    return $got_puzzle;
}
?>