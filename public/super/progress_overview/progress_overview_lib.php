<?php
/**
 * 第7週 第一次更新版本 新增
 * 高級權限 查看使用者進度 函式庫
 */

require_once './../../connect_db.php';


// sql查詢模板字串
define('SQL_CREATE_VIEW_STRING_TEMPLATE', 'CREATE OR REPLACE VIEW `valid_days_overview` AS (SELECT `u_id`, %1$s FROM `valid_days` WHERE %2$s %3$s);');
define('SQL_SELECT_STRING_TEMPLATE', 'SELECT (SELECT `user`.`exp_id` FROM `user` WHERE `user`.`id` = `valid_days_overview`.`u_id`) AS `exp_id`, `u_id`, %1$s FROM `valid_days_overview` GROUP BY `u_id`;');
define('SQL_DROP_STRING_TEMPLATE', 'DROP VIEW `valid_days_overview`;');

// 查詢條件模板字串
define('SQL_VIEW_CONDITION_STRING_TEMPLATE', '(CASE WHEN %1$s THEN %2$s END) AS `%3$s`');


// 根據不同裝置、組別所需要的進度，需照順序。
define('ANDROID_SET_GOAL_REQUIRE_PROGRESS', array(
    'beginning',
    'week_0_self_evaluate_android_set_goal_0',
    'week_1_set_goal_android',
    'week_1_self_evaluate_android_set_goal_1-8',
    'week_2_set_goal_android',
    'week_2_self_evaluate_android_set_goal_1-8',
    'week_3_set_goal_android',
    'week_3_self_evaluate_android_set_goal_1-8',
    'week_4_set_goal_android',
    'week_4_self_evaluate_android_set_goal_1-8',
    'week_5_set_goal_android',
    'week_5_self_evaluate_android_set_goal_1-8',
    'week_6_set_goal_android',
    'week_6_self_evaluate_android_set_goal_1-8',
    'week_7_set_goal_android',
    'week_7_self_evaluate_android_set_goal_1-8',
    'week_8_set_goal_android',
    'week_8_self_evaluate_android_set_goal_1-8',
    'week_9_self_evaluate_android_set_goal_9-24',
    'week_10_self_evaluate_android_set_goal_9-24',
    'week_11_self_evaluate_android_set_goal_9-24',
    'week_12_self_evaluate_android_set_goal_9-24',
    'week_13_self_evaluate_android_set_goal_9-24',
    'week_14_self_evaluate_android_set_goal_9-24',
    'week_15_self_evaluate_android_set_goal_9-24',
    'week_16_self_evaluate_android_set_goal_9-24',
    'week_17_self_evaluate_android_set_goal_9-24',
    'week_18_self_evaluate_android_set_goal_9-24',
    'week_19_self_evaluate_android_set_goal_9-24',
    'week_20_self_evaluate_android_set_goal_9-24',
    'week_21_self_evaluate_android_set_goal_9-24',
    'week_22_self_evaluate_android_set_goal_9-24',
    'week_23_self_evaluate_android_set_goal_9-24',
    'week_24_self_evaluate_android_set_goal_9-24'	
));
define('ANDROID_TRAINING_STRATEGY_REQUIRE_PROGRESS', array(
    'beginning',
    'week_0_self_evaluate_android_training_strategy_0',
    'week_1_set_goal_android',
    'high_risk_situation_form',
    'week_1_training_strategy_future_tutorial_1',
    'week_1_training_strategy_advantages_tutorial_1',
    'week_1_training_strategy_misdirection_tutorial_1',
    'week_1_training_strategy_breathing_tutorial_1',
    'week_1_self_evaluate_android_training_strategy_1-8',
    'week_2_set_goal_android',
    'week_2_training_strategy_future_tutorial_2',
    'week_2_training_strategy_advantages_tutorial_2',
    'week_2_training_strategy_misdirection_tutorial_2',
    'week_2_training_strategy_breathing_tutorial_2',
    'week_2_self_evaluate_android_training_strategy_1-8',
    'week_3_set_goal_android',
    'week_3_training_strategy_future_practice',
    'week_3_training_strategy_advantages_practice',
    'week_3_training_strategy_misdirection_practice',
    'week_3_training_strategy_breathing_practice',
    'week_3_self_evaluate_android_training_strategy_1-8',
    'week_4_set_goal_android',
    'week_4_training_strategy_future_practice',
    'week_4_training_strategy_advantages_practice',
    'week_4_training_strategy_misdirection_practice',
    'week_4_training_strategy_breathing_practice',
    'week_4_self_evaluate_android_training_strategy_1-8',
    'week_5_set_goal_android',
    'week_5_training_strategy_apply',
    'week_5_self_evaluate_android_training_strategy_1-8',
    'week_6_set_goal_android',
    'week_6_training_strategy_apply',
    'week_6_self_evaluate_android_training_strategy_1-8',
    'week_7_set_goal_android',
    'week_7_training_strategy_apply',
    'week_7_self_evaluate_android_training_strategy_1-8',
    'week_8_set_goal_android',
    'week_8_training_strategy_apply',
    'week_8_self_evaluate_android_training_strategy_1-8',
    'week_9_self_evaluate_android_training_strategy_9-24',
    'week_10_self_evaluate_android_training_strategy_9-24',
    'week_11_self_evaluate_android_training_strategy_9-24',
    'week_12_self_evaluate_android_training_strategy_9-24',
    'week_13_self_evaluate_android_training_strategy_9-24',
    'week_14_self_evaluate_android_training_strategy_9-24',
    'week_15_self_evaluate_android_training_strategy_9-24',
    'week_16_self_evaluate_android_training_strategy_9-24',
    'week_17_self_evaluate_android_training_strategy_9-24',
    'week_18_self_evaluate_android_training_strategy_9-24',
    'week_19_self_evaluate_android_training_strategy_9-24',
    'week_20_self_evaluate_android_training_strategy_9-24',
    'week_21_self_evaluate_android_training_strategy_9-24',
	'week_22_self_evaluate_android_training_strategy_9-24',
    'week_23_self_evaluate_android_training_strategy_9-24',
    'week_24_self_evaluate_android_training_strategy_9-24'	
	
));

define('IOS_SET_GOAL_REQUIRE_PROGRESS', array(
    'beginning',
    'week_0_self_evaluate_ios_set_goal_0',
    'week_1_set_goal_ios',
    'week_1_self_evaluate_ios_set_goal_1-8',
    'week_2_set_goal_ios',
    'week_2_self_evaluate_ios_set_goal_1-8',
    'week_3_set_goal_ios',
    'week_3_self_evaluate_ios_set_goal_1-8',
    'week_4_set_goal_ios',
    'week_4_self_evaluate_ios_set_goal_1-8',
    'week_5_set_goal_ios',
    'week_5_self_evaluate_ios_set_goal_1-8',
    'week_6_set_goal_ios',
    'week_6_self_evaluate_ios_set_goal_1-8',
    'week_7_set_goal_ios',
    'week_7_self_evaluate_ios_set_goal_1-8',
    'week_8_set_goal_ios',
    'week_8_self_evaluate_ios_set_goal_1-8',
    'week_9_self_evaluate_ios_set_goal_9-24',
    'week_10_self_evaluate_ios_set_goal_9-24',
    'week_11_self_evaluate_ios_set_goal_9-24',
    'week_12_self_evaluate_ios_set_goal_9-24',
    'week_13_self_evaluate_ios_set_goal_9-24',
    'week_14_self_evaluate_ios_set_goal_9-24',
    'week_15_self_evaluate_ios_set_goal_9-24',
    'week_16_self_evaluate_ios_set_goal_9-24',
    'week_17_self_evaluate_ios_set_goal_9-24',
    'week_18_self_evaluate_ios_set_goal_9-24',
    'week_19_self_evaluate_ios_set_goal_9-24',
    'week_20_self_evaluate_ios_set_goal_9-24',
    'week_21_self_evaluate_ios_set_goal_9-24',
    'week_22_self_evaluate_ios_set_goal_9-24',
    'week_23_self_evaluate_ios_set_goal_9-24',
    'week_24_self_evaluate_ios_set_goal_9-24'	
	
));
define('IOS_TRAINING_STRATEGY_REQUIRE_PROGRESS', array(
    'beginning',
    'week_0_self_evaluate_ios_training_strategy_0',
    'week_1_set_goal_ios',
    'high_risk_situation_form',
    'week_1_training_strategy_future_tutorial_1',
    'week_1_training_strategy_advantages_tutorial_1',
    'week_1_training_strategy_misdirection_tutorial_1',
    'week_1_training_strategy_breathing_tutorial_1',
    'week_1_self_evaluate_ios_training_strategy_1-8',
    'week_2_set_goal_ios',
    'week_2_training_strategy_future_tutorial_2',
    'week_2_training_strategy_advantages_tutorial_2',
    'week_2_training_strategy_misdirection_tutorial_2',
    'week_2_training_strategy_breathing_tutorial_2',
    'week_2_self_evaluate_ios_training_strategy_1-8',
    'week_3_set_goal_ios',
    'week_3_training_strategy_future_practice',
    'week_3_training_strategy_advantages_practice',
    'week_3_training_strategy_misdirection_practice',
    'week_3_training_strategy_breathing_practice',
    'week_3_self_evaluate_ios_training_strategy_1-8',
    'week_4_set_goal_ios',
    'week_4_training_strategy_future_practice',
    'week_4_training_strategy_advantages_practice',
    'week_4_training_strategy_misdirection_practice',
    'week_4_training_strategy_breathing_practice',
    'week_4_self_evaluate_ios_training_strategy_1-8',
    'week_5_set_goal_ios',
    'week_5_training_strategy_apply',
    'week_5_self_evaluate_ios_training_strategy_1-8',
    'week_6_set_goal_ios',
    'week_6_training_strategy_apply',
    'week_6_self_evaluate_ios_training_strategy_1-8',
    'week_7_set_goal_ios',
    'week_7_training_strategy_apply',
    'week_7_self_evaluate_ios_training_strategy_1-8',
    'week_8_set_goal_ios',
    'week_8_training_strategy_apply',
    'week_8_self_evaluate_ios_training_strategy_1-8',
    'week_9_self_evaluate_ios_training_strategy_9-24',
    'week_10_self_evaluate_ios_training_strategy_9-24',
    'week_11_self_evaluate_ios_training_strategy_9-24',
    'week_12_self_evaluate_ios_training_strategy_9-24',
    'week_13_self_evaluate_ios_training_strategy_9-24',
    'week_14_self_evaluate_ios_training_strategy_9-24',
	'week_15_self_evaluate_ios_training_strategy_9-24',
	'week_16_self_evaluate_ios_training_strategy_9-24',
    'week_17_self_evaluate_ios_training_strategy_9-24',
    'week_18_self_evaluate_ios_training_strategy_9-24',
    'week_19_self_evaluate_ios_training_strategy_9-24',
    'week_20_self_evaluate_ios_training_strategy_9-24',
    'week_21_self_evaluate_ios_training_strategy_9-24',
    'week_22_self_evaluate_ios_training_strategy_9-24',
    'week_23_self_evaluate_ios_training_strategy_9-24',
    'week_24_self_evaluate_ios_training_strategy_9-24'	
	
));


/**
 * 結構說明:
 * '進度名稱' => array(
 *     '輸出時中文欄位名稱' => '第0週',
 *     '該進度有效的可出現次數' => array(1),
 *     '搜尋條件sql語法' => '`source_object` LIKE "%PROVIDE_BEGINNING_PROGRESS_ANDROID%"',
 *     '搜尋後的替代值' => '1'
 * )
 */
define('PROGRESS_LIB_ANDROID', array(
    'cluster_sql_condition_string' => '`u_id` IN (SELECT `id` FROM `user` WHERE `exp_id` LIKE "A' . YEAR_NO . '%")',
    'column' => array(
        'beginning' => array(
            'name_zh' => '第0週',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` LIKE "%PROVIDE_BEGINNING_PROGRESS_ANDROID%"',
            'sql_result' => '1'
        ),
        'high_risk_situation_form' => array(
            'name_zh' => '高風險誘惑情境',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "high_risk_situation_form"',
            'sql_result' => '1'
        ),
        'week_0_self_evaluate_android_set_goal_0' => array(
            'name_zh' => '第0週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_0" AND `week` = 0',
            'sql_result' => '1'
        ),
        'week_1_self_evaluate_android_set_goal_1-8' => array(
            'name_zh' => '第1週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_1-8" AND `week` = 1',
            'sql_result' => '1'
        ),
        'week_2_self_evaluate_android_set_goal_1-8' => array(
            'name_zh' => '第2週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_1-8" AND `week` = 2',
            'sql_result' => '1'
        ),
        'week_3_self_evaluate_android_set_goal_1-8' => array(
            'name_zh' => '第3週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_1-8" AND `week` = 3',
            'sql_result' => '1'
        ),
        'week_4_self_evaluate_android_set_goal_1-8' => array(
            'name_zh' => '第4週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_1-8" AND `week` = 4',
            'sql_result' => '1'
        ),
        'week_5_self_evaluate_android_set_goal_1-8' => array(
            'name_zh' => '第5週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_1-8" AND `week` = 5',
            'sql_result' => '1'
        ),
        'week_6_self_evaluate_android_set_goal_1-8' => array(
            'name_zh' => '第6週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_1-8" AND `week` = 6',
            'sql_result' => '1'
        ),
        'week_7_self_evaluate_android_set_goal_1-8' => array(
            'name_zh' => '第7週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_1-8" AND `week` = 7',
            'sql_result' => '1'
        ),
        'week_8_self_evaluate_android_set_goal_1-8' => array(
            'name_zh' => '第8週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_1-8" AND `week` = 8',
            'sql_result' => '1'
        ),
        'week_9_self_evaluate_android_set_goal_9-24' => array(
            'name_zh' => '第9週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_9-24" AND `week` = 9',
            'sql_result' => '1'
        ),
        'week_10_self_evaluate_android_set_goal_9-24' => array(
            'name_zh' => '第10週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_9-24" AND `week` = 10',
            'sql_result' => '1'
        ),
        'week_11_self_evaluate_android_set_goal_9-24' => array(
            'name_zh' => '第11週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_9-24" AND `week` = 11',
            'sql_result' => '1'
        ),
'week_12_self_evaluate_android_set_goal_9-24' => array(
            'name_zh' => '第12週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_9-24" AND `week` = 12',
            'sql_result' => '1'
        ),
        'week_13_self_evaluate_android_set_goal_9-24' => array(
            'name_zh' => '第13週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_9-24" AND `week` = 13',
            'sql_result' => '1'
        ),
        'week_14_self_evaluate_android_set_goal_9-24' => array(
            'name_zh' => '第14週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_9-24" AND `week` = 14',
            'sql_result' => '1'
        ),
        'week_15_self_evaluate_android_set_goal_9-24' => array(
            'name_zh' => '第15週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_9-24" AND `week` = 15',
            'sql_result' => '1'
        ),
        'week_16_self_evaluate_android_set_goal_9-24' => array(
            'name_zh' => '第16週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_9-24" AND `week` = 16',
            'sql_result' => '1'
        ),
        'week_17_self_evaluate_android_set_goal_9-24' => array(
            'name_zh' => '第17週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_9-24" AND `week` = 17',
            'sql_result' => '1'
        ),
        'week_18_self_evaluate_android_set_goal_9-24' => array(
            'name_zh' => '第18週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_9-24" AND `week` = 18',
            'sql_result' => '1'
        ),
        'week_19_self_evaluate_android_set_goal_9-24' => array(
            'name_zh' => '第19週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_9-24" AND `week` = 19',
            'sql_result' => '1'
        ),
        'week_20_self_evaluate_android_set_goal_9-24' => array(
            'name_zh' => '第20週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_9-24" AND `week` = 20',
            'sql_result' => '1'
        ),
        'week_21_self_evaluate_android_set_goal_9-24' => array(
            'name_zh' => '第21週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_9-24" AND `week` = 21',
            'sql_result' => '1'
        ),
        'week_22_self_evaluate_android_set_goal_9-24' => array(
            'name_zh' => '第22週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_9-24" AND `week` = 22',
            'sql_result' => '1'
        ),
        'week_23_self_evaluate_android_set_goal_9-24' => array(
            'name_zh' => '第23週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_9-24" AND `week` = 23',
            'sql_result' => '1'
        ),
        'week_24_self_evaluate_android_set_goal_9-24' => array(
            'name_zh' => '第24週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_set_goal_9-24" AND `week` = 24',
            'sql_result' => '1'
        ),



        'week_0_self_evaluate_android_training_strategy_0' => array(
            'name_zh' => '第0週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_0" AND `week` = 0',
            'sql_result' => '1'
        ),
        'week_1_self_evaluate_android_training_strategy_1-8' => array(
            'name_zh' => '第1週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_1-8" AND `week` = 1',
            'sql_result' => '1'
        ),
        'week_2_self_evaluate_android_training_strategy_1-8' => array(
            'name_zh' => '第2週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_1-8" AND `week` = 2',
            'sql_result' => '1'
        ),
        'week_3_self_evaluate_android_training_strategy_1-8' => array(
            'name_zh' => '第3週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_1-8" AND `week` = 3',
            'sql_result' => '1'
        ),
        'week_4_self_evaluate_android_training_strategy_1-8' => array(
            'name_zh' => '第4週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_1-8" AND `week` = 4',
            'sql_result' => '1'
        ),
        'week_5_self_evaluate_android_training_strategy_1-8' => array(
            'name_zh' => '第5週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_1-8" AND `week` = 5',
            'sql_result' => '1'
        ),
        'week_6_self_evaluate_android_training_strategy_1-8' => array(
            'name_zh' => '第6週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_1-8" AND `week` = 6',
            'sql_result' => '1'
        ),
        'week_7_self_evaluate_android_training_strategy_1-8' => array(
            'name_zh' => '第7週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_1-8" AND `week` = 7',
            'sql_result' => '1'
        ),
        'week_8_self_evaluate_android_training_strategy_1-8' => array(
            'name_zh' => '第8週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_1-8" AND `week` = 8',
            'sql_result' => '1'
        ),
        'week_9_self_evaluate_android_training_strategy_9-24' => array(
            'name_zh' => '第9週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_9-24" AND `week` = 9',
            'sql_result' => '1'
        ),
        'week_10_self_evaluate_android_training_strategy_9-24' => array(
            'name_zh' => '第10週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_9-24" AND `week` = 10',
            'sql_result' => '1'
        ),
        'week_11_self_evaluate_android_training_strategy_9-24' => array(
            'name_zh' => '第11週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_9-24" AND `week` = 11',
            'sql_result' => '1'
        ),
'week_12_self_evaluate_android_training_strategy_9-24' => array(
            'name_zh' => '第12週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_9-24" AND `week` = 12',
            'sql_result' => '1'
        ),
        'week_13_self_evaluate_android_training_strategy_9-24' => array(
            'name_zh' => '第13週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_9-24" AND `week` = 13',
            'sql_result' => '1'
        ),
        'week_14_self_evaluate_android_training_strategy_9-24' => array(
            'name_zh' => '第14週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_9-24" AND `week` = 14',
            'sql_result' => '1'
        ),
        'week_15_self_evaluate_android_training_strategy_9-24' => array(
            'name_zh' => '第15週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_9-24" AND `week` = 15',
            'sql_result' => '1'
        ),
        'week_16_self_evaluate_android_training_strategy_9-24' => array(
            'name_zh' => '第16週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_9-24" AND `week` = 16',
            'sql_result' => '1'
        ),        
		'week_17_self_evaluate_android_training_strategy_9-24' => array(
            'name_zh' => '第17週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_9-24" AND `week` = 17',
            'sql_result' => '1'
        ),		
        'week_18_self_evaluate_android_training_strategy_9-24' => array(
            'name_zh' => '第18週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_9-24" AND `week` = 18',
            'sql_result' => '1'
        ),
        'week_19_self_evaluate_android_training_strategy_9-24' => array(
            'name_zh' => '第19週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_9-24" AND `week` = 19',
            'sql_result' => '1'
        ),
        'week_20_self_evaluate_android_training_strategy_9-24' => array(
            'name_zh' => '第20週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_9-24" AND `week` = 20',
            'sql_result' => '1'
        ),
        'week_21_self_evaluate_android_training_strategy_9-24' => array(
            'name_zh' => '第21週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_9-24" AND `week` = 21',
            'sql_result' => '1'
        ),
        'week_22_self_evaluate_android_training_strategy_9-24' => array(
            'name_zh' => '第22週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_9-24" AND `week` = 22',
            'sql_result' => '1'
        ),
        'week_23_self_evaluate_android_training_strategy_9-24' => array(
            'name_zh' => '第23週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_9-24" AND `week` = 23',
            'sql_result' => '1'
        ),
        'week_24_self_evaluate_android_training_strategy_9-24' => array(
            'name_zh' => '第24週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_android_training_strategy_9-24" AND `week` = 24',
            'sql_result' => '1'
        ),		





        'week_1_set_goal_android' => array(
            'name_zh' => '第1週設定目標',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "set_goal_android" AND `week` = 1',
            'sql_result' => '1'
        ),
        'week_2_set_goal_android' => array(
            'name_zh' => '第2週設定目標',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "set_goal_android" AND `week` = 2',
            'sql_result' => '1'
        ),
        'week_3_set_goal_android' => array(
            'name_zh' => '第3週設定目標',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "set_goal_android" AND `week` = 3',
            'sql_result' => '1'
        ),
        'week_4_set_goal_android' => array(
            'name_zh' => '第4週設定目標',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "set_goal_android" AND `week` = 4',
            'sql_result' => '1'
        ),
        'week_5_set_goal_android' => array(
            'name_zh' => '第5週設定目標',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "set_goal_android" AND `week` = 5',
            'sql_result' => '1'
        ),
        'week_6_set_goal_android' => array(
            'name_zh' => '第6週設定目標',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "set_goal_android" AND `week` = 6',
            'sql_result' => '1'
        ),
        'week_7_set_goal_android' => array(
            'name_zh' => '第7週設定目標',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "set_goal_android" AND `week` = 7',
            'sql_result' => '1'
        ),
        'week_8_set_goal_android' => array(
            'name_zh' => '第8週設定目標',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "set_goal_android" AND `week` = 8',
            'sql_result' => '1'
        ),



        'week_1_training_strategy_future_tutorial_1' => array(
            'name_zh' => '第1週設定目標前瞻未來教學',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_future_tutorial_1" AND `week` = 1',
            'sql_result' => '1'
        ),
        'week_1_training_strategy_advantages_tutorial_1' => array(
            'name_zh' => '第1週好壞處分析教學',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_advantages_tutorial_1" AND `week` = 1',
            'sql_result' => '1'
        ),
        'week_1_training_strategy_misdirection_tutorial_1' => array(
            'name_zh' => '第1週分散注意力教學',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_misdirection_tutorial_1" AND `week` = 1',
            'sql_result' => '1'
        ),
        'week_1_training_strategy_breathing_tutorial_1' => array(
            'name_zh' => '第1週正念數息教學',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_breathing_tutorial_1" AND `week` = 1',
            'sql_result' => '1'
        ),

        'week_2_training_strategy_future_tutorial_2' => array(
            'name_zh' => '第2週設定目標前瞻未來教學',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_future_tutorial_2" AND `week` = 2',
            'sql_result' => '1'
        ),
        'week_2_training_strategy_advantages_tutorial_2' => array(
            'name_zh' => '第2週好壞處分析教學',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_advantages_tutorial_2" AND `week` = 2',
            'sql_result' => '1'
        ),
        'week_2_training_strategy_misdirection_tutorial_2' => array(
            'name_zh' => '第2週分散注意力教學',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_misdirection_tutorial_2" AND `week` = 2',
            'sql_result' => '1'
        ),
        'week_2_training_strategy_breathing_tutorial_2' => array(
            'name_zh' => '第2週正念數息教學',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_breathing_tutorial_2" AND `week` = 2',
            'sql_result' => '1'
        ),

        'week_3_training_strategy_future_practice' => array(
            'name_zh' => '第3週設定目標前瞻未來挑戰',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_future_practice" AND `week` = 3',
            'sql_result' => '1'
        ),
        'week_3_training_strategy_advantages_practice' => array(
            'name_zh' => '第3週好壞處分析挑戰',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_advantages_practice" AND `week` = 3',
            'sql_result' => '1'
        ),
        'week_3_training_strategy_misdirection_practice' => array(
            'name_zh' => '第3週分散注意力挑戰',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_misdirection_practice" AND `week` = 3',
            'sql_result' => '1'
        ),
        'week_3_training_strategy_breathing_practice' => array(
            'name_zh' => '第3週正念數息挑戰',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_breathing_practice" AND `week` = 3',
            'sql_result' => '1'
        ),

        'week_4_training_strategy_future_practice' => array(
            'name_zh' => '第4週設定目標前瞻未來挑戰',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_future_practice" AND `week` = 4',
            'sql_result' => '1'
        ),
        'week_4_training_strategy_advantages_practice' => array(
            'name_zh' => '第4週好壞處分析挑戰',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_advantages_practice" AND `week` = 4',
            'sql_result' => '1'
        ),
        'week_4_training_strategy_misdirection_practice' => array(
            'name_zh' => '第4週分散注意力挑戰',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_misdirection_practice" AND `week` = 4',
            'sql_result' => '1'
        ),
        'week_4_training_strategy_breathing_practice' => array(
            'name_zh' => '第4週正念數息挑戰',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_breathing_practice" AND `week` = 4',
            'sql_result' => '1'
        ),

        'week_5_training_strategy_apply' => array(
            'name_zh' => '第5週策略訓練應用',
            'valid_times' => array(1, 2, 3, 4),
            'sql_condition_string' => '`source_object` = "training_strategy_apply" AND `week` = 5',
            'sql_result' => '1'
        ),
        'week_6_training_strategy_apply' => array(
            'name_zh' => '第6週策略訓練應用',
            'valid_times' => array(1, 2, 3, 4),
            'sql_condition_string' => '`source_object` = "training_strategy_apply" AND `week` = 6',
            'sql_result' => '1'
        ),
        'week_7_training_strategy_apply' => array(
            'name_zh' => '第7週策略訓練應用',
            'valid_times' => array(1, 2, 3, 4),
            'sql_condition_string' => '`source_object` = "training_strategy_apply" AND `week` = 7',
            'sql_result' => '1'
        ),
        'week_8_training_strategy_apply' => array(
            'name_zh' => '第8週策略訓練應用',
            'valid_times' => array(1, 2, 3, 4),
            'sql_condition_string' => '`source_object` = "training_strategy_apply" AND `week` = 8',
            'sql_result' => '1'
        )
    )
));





define('PROGRESS_LIB_IOS', array(
    'cluster_sql_condition_string' => '`u_id` IN (SELECT `id` FROM `user` WHERE `exp_id` LIKE "I' . YEAR_NO . '%")',
    'column' => array(
        'beginning' => array(
            'name_zh' => '第0週',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` LIKE "%PROVIDE_BEGINNING_PROGRESS_IOS%"',
            'sql_result' => '1'
        ),
        'high_risk_situation_form' => array(
            'name_zh' => '高風險誘惑情境',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "high_risk_situation_form"',
            'sql_result' => '1'
        ),
        'week_0_self_evaluate_ios_set_goal_0' => array(
            'name_zh' => '第0週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_0" AND `week` = 0',
            'sql_result' => '1'
        ),
        'week_1_self_evaluate_ios_set_goal_1-8' => array(
            'name_zh' => '第1週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_1-8" AND `week` = 1',
            'sql_result' => '1'
        ),
        'week_2_self_evaluate_ios_set_goal_1-8' => array(
            'name_zh' => '第2週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_1-8" AND `week` = 2',
            'sql_result' => '1'
        ),
        'week_3_self_evaluate_ios_set_goal_1-8' => array(
            'name_zh' => '第3週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_1-8" AND `week` = 3',
            'sql_result' => '1'
        ),
        'week_4_self_evaluate_ios_set_goal_1-8' => array(
            'name_zh' => '第4週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_1-8" AND `week` = 4',
            'sql_result' => '1'
        ),
        'week_5_self_evaluate_ios_set_goal_1-8' => array(
            'name_zh' => '第5週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_1-8" AND `week` = 5',
            'sql_result' => '1'
        ),
        'week_6_self_evaluate_ios_set_goal_1-8' => array(
            'name_zh' => '第6週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_1-8" AND `week` = 6',
            'sql_result' => '1'
        ),
        'week_7_self_evaluate_ios_set_goal_1-8' => array(
            'name_zh' => '第7週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_1-8" AND `week` = 7',
            'sql_result' => '1'
        ),
        'week_8_self_evaluate_ios_set_goal_1-8' => array(
            'name_zh' => '第8週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_1-8" AND `week` = 8',
            'sql_result' => '1'
        ),
        'week_9_self_evaluate_ios_set_goal_9-24' => array(
            'name_zh' => '第9週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_9-24" AND `week` = 9',
            'sql_result' => '1'
        ),
        'week_10_self_evaluate_ios_set_goal_9-24' => array(
            'name_zh' => '第10週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_9-24" AND `week` = 10',
            'sql_result' => '1'
        ),
        'week_11_self_evaluate_ios_set_goal_9-24' => array(
            'name_zh' => '第11週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_9-24" AND `week` = 11',
            'sql_result' => '1'
        ),
'week_12_self_evaluate_ios_set_goal_9-24' => array(
            'name_zh' => '第12週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_9-24" AND `week` = 12',
            'sql_result' => '1'
        ),
        'week_13_self_evaluate_ios_set_goal_9-24' => array(
            'name_zh' => '第13週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_9-24" AND `week` = 13',
            'sql_result' => '1'
        ),
        'week_14_self_evaluate_ios_set_goal_9-24' => array(
            'name_zh' => '第14週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_9-24" AND `week` = 14',
            'sql_result' => '1'
        ),
        'week_15_self_evaluate_ios_set_goal_9-24' => array(
            'name_zh' => '第15週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_9-24" AND `week` = 15',
            'sql_result' => '1'
        ),
        'week_16_self_evaluate_ios_set_goal_9-24' => array(
            'name_zh' => '第16週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_9-24" AND `week` = 16',
            'sql_result' => '1'
        ),
        'week_17_self_evaluate_ios_set_goal_9-24' => array(
            'name_zh' => '第17週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_9-24" AND `week` = 17',
            'sql_result' => '1'
        ),
        'week_18_self_evaluate_ios_set_goal_9-24' => array(
            'name_zh' => '第18週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_9-24" AND `week` = 18',
            'sql_result' => '1'
        ),
        'week_19_self_evaluate_ios_set_goal_9-24' => array(
            'name_zh' => '第19週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_9-24" AND `week` = 19',
            'sql_result' => '1'
        ),
        'week_20_self_evaluate_ios_set_goal_9-24' => array(
            'name_zh' => '第20週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_9-24" AND `week` = 20',
            'sql_result' => '1'
        ),
        'week_21_self_evaluate_ios_set_goal_9-24' => array(
            'name_zh' => '第21週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_9-24" AND `week` = 21',
            'sql_result' => '1'
        ),
        'week_22_self_evaluate_ios_set_goal_9-24' => array(
            'name_zh' => '第22週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_9-24" AND `week` = 22',
            'sql_result' => '1'
        ),
        'week_23_self_evaluate_ios_set_goal_9-24' => array(
            'name_zh' => '第23週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_9-24" AND `week` = 23',
            'sql_result' => '1'
        ),
        'week_24_self_evaluate_ios_set_goal_9-24' => array(
            'name_zh' => '第24週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_set_goal_9-24" AND `week` = 24',
            'sql_result' => '1'
        ),




        'week_0_self_evaluate_ios_training_strategy_0' => array(
            'name_zh' => '第0週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_0" AND `week` = 0',
            'sql_result' => '1'
        ),
        'week_1_self_evaluate_ios_training_strategy_1-8' => array(
            'name_zh' => '第1週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_1-8" AND `week` = 1',
            'sql_result' => '1'
        ),
        'week_2_self_evaluate_ios_training_strategy_1-8' => array(
            'name_zh' => '第2週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_1-8" AND `week` = 2',
            'sql_result' => '1'
        ),
        'week_3_self_evaluate_ios_training_strategy_1-8' => array(
            'name_zh' => '第3週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_1-8" AND `week` = 3',
            'sql_result' => '1'
        ),
        'week_4_self_evaluate_ios_training_strategy_1-8' => array(
            'name_zh' => '第4週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_1-8" AND `week` = 4',
            'sql_result' => '1'
        ),
        'week_5_self_evaluate_ios_training_strategy_1-8' => array(
            'name_zh' => '第5週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_1-8" AND `week` = 5',
            'sql_result' => '1'
        ),
        'week_6_self_evaluate_ios_training_strategy_1-8' => array(
            'name_zh' => '第6週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_1-8" AND `week` = 6',
            'sql_result' => '1'
        ),
        'week_7_self_evaluate_ios_training_strategy_1-8' => array(
            'name_zh' => '第7週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_1-8" AND `week` = 7',
            'sql_result' => '1'
        ),
        'week_8_self_evaluate_ios_training_strategy_1-8' => array(
            'name_zh' => '第8週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_1-8" AND `week` = 8',
            'sql_result' => '1'
        ),
        'week_9_self_evaluate_ios_training_strategy_9-24' => array(
            'name_zh' => '第9週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_9-24" AND `week` = 9',
            'sql_result' => '1'
        ),
        'week_10_self_evaluate_ios_training_strategy_9-24' => array(
            'name_zh' => '第10週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_9-24" AND `week` = 10',
            'sql_result' => '1'
        ),
        'week_11_self_evaluate_ios_training_strategy_9-24' => array(
            'name_zh' => '第11週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_9-24" AND `week` = 11',
            'sql_result' => '1'
        ),
'week_12_self_evaluate_ios_training_strategy_9-24' => array(
            'name_zh' => '第12週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_9-24" AND `week` = 12',
            'sql_result' => '1'
        ),
        'week_13_self_evaluate_ios_training_strategy_9-24' => array(
            'name_zh' => '第13週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_9-24" AND `week` = 13',
            'sql_result' => '1'
        ),
        'week_14_self_evaluate_ios_training_strategy_9-24' => array(
            'name_zh' => '第14週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_9-24" AND `week` = 14',
            'sql_result' => '1'
        ),
        'week_15_self_evaluate_ios_training_strategy_9-24' => array(
            'name_zh' => '第15週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_9-24" AND `week` = 15',
            'sql_result' => '1'
        ),
        'week_16_self_evaluate_ios_training_strategy_9-24' => array(
            'name_zh' => '第16週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_9-24" AND `week` = 16',
            'sql_result' => '1'
        ),
        'week_17_self_evaluate_ios_training_strategy_9-24' => array(
            'name_zh' => '第17週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_9-24" AND `week` = 17',
            'sql_result' => '1'
        ),
        'week_18_self_evaluate_ios_training_strategy_9-24' => array(
            'name_zh' => '第18週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_9-24" AND `week` = 18',
            'sql_result' => '1'
        ),
        'week_19_self_evaluate_ios_training_strategy_9-24' => array(
            'name_zh' => '第19週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_9-24" AND `week` = 19',
            'sql_result' => '1'
        ),
        'week_20_self_evaluate_ios_training_strategy_9-24' => array(
            'name_zh' => '第20週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_9-24" AND `week` = 20',
            'sql_result' => '1'
        ),
        'week_21_self_evaluate_ios_training_strategy_9-24' => array(
            'name_zh' => '第21週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_9-24" AND `week` = 21',
            'sql_result' => '1'
        ),
        'week_22_self_evaluate_ios_training_strategy_9-24' => array(
            'name_zh' => '第22週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_9-24" AND `week` = 22',
            'sql_result' => '1'
        ),
        'week_23_self_evaluate_ios_training_strategy_9-24' => array(
            'name_zh' => '第23週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_9-24" AND `week` = 23',
            'sql_result' => '1'
        ),
        'week_24_self_evaluate_ios_training_strategy_9-24' => array(
            'name_zh' => '第24週自我評估單',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "self_evaluate_ios_training_strategy_9-24" AND `week` = 24',
            'sql_result' => '1'
        ),




        'week_1_set_goal_ios' => array(
            'name_zh' => '第1週設定目標',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "set_goal_ios" AND `week` = 1',
            'sql_result' => '1'
        ),
        'week_2_set_goal_ios' => array(
            'name_zh' => '第2週設定目標',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "set_goal_ios" AND `week` = 2',
            'sql_result' => '1'
        ),
        'week_3_set_goal_ios' => array(
            'name_zh' => '第3週設定目標',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "set_goal_ios" AND `week` = 3',
            'sql_result' => '1'
        ),
        'week_4_set_goal_ios' => array(
            'name_zh' => '第4週設定目標',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "set_goal_ios" AND `week` = 4',
            'sql_result' => '1'
        ),
        'week_5_set_goal_ios' => array(
            'name_zh' => '第5週設定目標',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "set_goal_ios" AND `week` = 5',
            'sql_result' => '1'
        ),
        'week_6_set_goal_ios' => array(
            'name_zh' => '第6週設定目標',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "set_goal_ios" AND `week` = 6',
            'sql_result' => '1'
        ),
        'week_7_set_goal_ios' => array(
            'name_zh' => '第7週設定目標',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "set_goal_ios" AND `week` = 7',
            'sql_result' => '1'
        ),
        'week_8_set_goal_ios' => array(
            'name_zh' => '第8週設定目標',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "set_goal_ios" AND `week` = 8',
            'sql_result' => '1'
        ),



        'week_1_training_strategy_future_tutorial_1' => array(
            'name_zh' => '第1週設定目標前瞻未來教學',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_future_tutorial_1" AND `week` = 1',
            'sql_result' => '1'
        ),
        'week_1_training_strategy_advantages_tutorial_1' => array(
            'name_zh' => '第1週好壞處分析教學',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_advantages_tutorial_1" AND `week` = 1',
            'sql_result' => '1'
        ),
        'week_1_training_strategy_misdirection_tutorial_1' => array(
            'name_zh' => '第1週分散注意力教學',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_misdirection_tutorial_1" AND `week` = 1',
            'sql_result' => '1'
        ),
        'week_1_training_strategy_breathing_tutorial_1' => array(
            'name_zh' => '第1週正念數息教學',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_breathing_tutorial_1" AND `week` = 1',
            'sql_result' => '1'
        ),

        'week_2_training_strategy_future_tutorial_2' => array(
            'name_zh' => '第2週設定目標前瞻未來教學',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_future_tutorial_2" AND `week` = 2',
            'sql_result' => '1'
        ),
        'week_2_training_strategy_advantages_tutorial_2' => array(
            'name_zh' => '第2週好壞處分析教學',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_advantages_tutorial_2" AND `week` = 2',
            'sql_result' => '1'
        ),
        'week_2_training_strategy_misdirection_tutorial_2' => array(
            'name_zh' => '第2週分散注意力教學',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_misdirection_tutorial_2" AND `week` = 2',
            'sql_result' => '1'
        ),
        'week_2_training_strategy_breathing_tutorial_2' => array(
            'name_zh' => '第2週正念數息教學',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_breathing_tutorial_2" AND `week` = 2',
            'sql_result' => '1'
        ),

        'week_3_training_strategy_future_practice' => array(
            'name_zh' => '第3週設定目標前瞻未來挑戰',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_future_practice" AND `week` = 3',
            'sql_result' => '1'
        ),
        'week_3_training_strategy_advantages_practice' => array(
            'name_zh' => '第3週好壞處分析挑戰',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_advantages_practice" AND `week` = 3',
            'sql_result' => '1'
        ),
        'week_3_training_strategy_misdirection_practice' => array(
            'name_zh' => '第3週分散注意力挑戰',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_misdirection_practice" AND `week` = 3',
            'sql_result' => '1'
        ),
        'week_3_training_strategy_breathing_practice' => array(
            'name_zh' => '第3週正念數息挑戰',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_breathing_practice" AND `week` = 3',
            'sql_result' => '1'
        ),

        'week_4_training_strategy_future_practice' => array(
            'name_zh' => '第4週設定目標前瞻未來挑戰',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_future_practice" AND `week` = 4',
            'sql_result' => '1'
        ),
        'week_4_training_strategy_advantages_practice' => array(
            'name_zh' => '第4週好壞處分析挑戰',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_advantages_practice" AND `week` = 4',
            'sql_result' => '1'
        ),
        'week_4_training_strategy_misdirection_practice' => array(
            'name_zh' => '第4週分散注意力挑戰',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_misdirection_practice" AND `week` = 4',
            'sql_result' => '1'
        ),
        'week_4_training_strategy_breathing_practice' => array(
            'name_zh' => '第4週正念數息挑戰',
            'valid_times' => array(1),
            'sql_condition_string' => '`source_object` = "training_strategy_breathing_practice" AND `week` = 4',
            'sql_result' => '1'
        ),

        'week_5_training_strategy_apply' => array(
            'name_zh' => '第5週策略訓練應用',
            'valid_times' => array(1, 2, 3, 4),
            'sql_condition_string' => '`source_object` = "training_strategy_apply" AND `week` = 5',
            'sql_result' => '1'
        ),
        'week_6_training_strategy_apply' => array(
            'name_zh' => '第6週策略訓練應用',
            'valid_times' => array(1, 2, 3, 4),
            'sql_condition_string' => '`source_object` = "training_strategy_apply" AND `week` = 6',
            'sql_result' => '1'
        ),
        'week_7_training_strategy_apply' => array(
            'name_zh' => '第7週策略訓練應用',
            'valid_times' => array(1, 2, 3, 4),
            'sql_condition_string' => '`source_object` = "training_strategy_apply" AND `week` = 7',
            'sql_result' => '1'
        ),
        'week_8_training_strategy_apply' => array(
            'name_zh' => '第8週策略訓練應用',
            'valid_times' => array(1, 2, 3, 4),
            'sql_condition_string' => '`source_object` = "training_strategy_apply" AND `week` = 8',
            'sql_result' => '1'
        )
    )
));

?>