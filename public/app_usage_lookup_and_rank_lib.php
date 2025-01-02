<?php
/**
 * v7_1新增
 * 顯示使用時間及排名 函式庫
 */



define('APP_CATEGORY_INFORMATION', array(
    'game' => array('name_zh' => '遊戲'),
    'video' => array('name_zh' => '影音播放與編輯'),
    'social' => array('name_zh' => '社交'),
    'communication' => array('name_zh' => '通訊'),
    'entertainment' => array('name_zh' => '娛樂'),
    'total' => array('name_zh' => '總使用時間')
));

define('GET_CATEGORY_USAGE_WEEKLY_SQL_TEMPLATE', 'SELECT `u_id`, `usage_time` FROM `category_usage_manual%1$s` WHERE `category` = "%2$s" AND `week` = :week ORDER BY `usage_time`;');

define('GET_CATEGORY_USAGE_SAME_PROGRESS_USERS_SQL_TEMPLATE', 'SELECT `u_id` FROM `category_usage_manual%1$s` WHERE `category` = "%2$s" AND `week` = :same_week;');
define('GET_CATEGORY_USAGE_SUM_SQL_TEMPLATE', 'SELECT `u_id`, SUM(`usage_time`) AS `total_usage_time` FROM `category_usage_manual%1$s` WHERE `category` = "%2$s" AND `u_id` IN (%3$s) AND `week` < :week_limit GROUP BY `u_id` ORDER BY `total_usage_time`;');

function get_category_usage_weekly($dbh, $object_device, $category, $u_id, $week) {
    $usage_time_data = array();

    $sth = $dbh->prepare(sprintf(GET_CATEGORY_USAGE_WEEKLY_SQL_TEMPLATE, $object_device, $category));
    for ($week_counter = 0 ; $week_counter < $week ; $week_counter++) {
        $sth->execute(['week' => $week_counter]);
        $result = $sth->fetchAll();
        
        for ($rank_counter = 0 ; $rank_counter < count($result) ; $rank_counter++) {
            if ($result[$rank_counter]['u_id'] == $u_id) {
                array_push($usage_time_data, array(
                    'usage_time' => $result[$rank_counter]['usage_time'],
                    'total_data' => count($result),
                    'rank' => ($rank_counter + 1)
                ));

                break;
            }
        }
    }

    return $usage_time_data;
}

function get_category_usage_sum($dbh, $object_device, $category, $u_id, $week) {
    //找出擁有相同或大於進度的使用者
    $usage_time_data = array();

    $sth = $dbh->prepare(sprintf(GET_CATEGORY_USAGE_SAME_PROGRESS_USERS_SQL_TEMPLATE, $object_device, $category));
    $sth->execute(['same_week' => ($week - 1)]);
    $same_progress_users_result = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
    $number_of_same_progress_users = count($same_progress_users_result);
    $same_progress_users = implode(',', $same_progress_users_result);

    $sth = $dbh->prepare(sprintf(GET_CATEGORY_USAGE_SUM_SQL_TEMPLATE, $object_device, $category, $same_progress_users));
    $sth->execute(['week_limit' => $week]);
    $result = $sth->fetchAll();
    for ($rank_counter = 0 ; $rank_counter < count($result) ; $rank_counter++) {
        if ($result[$rank_counter]['u_id'] == $u_id) {
            $usage_time_data = array(
                'total_usage_time' => $result[$rank_counter]['total_usage_time'],
                'total_data' => count($result),
                'rank' => ($rank_counter + 1)
            );

            break;
        }
    }

    return $usage_time_data;
}
?>