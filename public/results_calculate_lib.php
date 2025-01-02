<?php
/**
 * 定義結算分數用的常量跟函式
 */
define('REACH_GOAL_SCORE', 100);      // 達標給予的獎勵分數
define('REACH_GOAL_SCORE_LIMIT', 69);      // 達標分數的底線，用於判斷最近一次達標(因原本是1天達標得10分，7天達標就是70分)

// 用於更新指定類別看是否達標來給分
function init_define_UPDATE_CATEGORY_SCORE_CALCULATE_SQL_STRING_TEMPLATE($object_device) {
    if (!defined('UPDATE_CATEGORY_SCORE_CALCULATE_SQL_STRING_TEMPLATE')) {
        define('UPDATE_CATEGORY_SCORE_CALCULATE_SQL_STRING_TEMPLATE',
            '`score_%1$s` = (
                SELECT (
                    (((EXP(SUM(LOG(1 - (`reduce_%1$s` * 0.01))))) * (
                        SELECT `usage_time` FROM `category_usage_manual' . $object_device . '` WHERE `u_id` = :u_id AND `category` = "%1$s" AND `week` = 0 LIMIT 1
                    )) >= (
                        SELECT `usage_time` FROM `category_usage_manual' . $object_device . '` WHERE `u_id` = :u_id AND `category` = "%1$s" AND `week` = :week LIMIT 1
                    )) * ' . REACH_GOAL_SCORE . '
                ) AS `update_score_%1$s` FROM `goal` WHERE (`u_id` = :u_id AND `score_%1$s` > ' . REACH_GOAL_SCORE_LIMIT . ') OR (`u_id` = :u_id AND `week` = :week)
            )'
        );
    }
}

// 算出指定類別該週設定減量目標的使用時間基準
function init_define_SELECT_CATEGORY_SCORE_CALCULATE_SQL_STRING_TEMPLATE($object_device) {
    if (!defined('SELECT_CATEGORY_SCORE_CALCULATE_SQL_STRING_TEMPLATE')) {
        define('SELECT_CATEGORY_SCORE_CALCULATE_SQL_STRING_TEMPLATE',
            'SELECT (
                IFNULL((EXP(SUM(LOG(1 - (`reduce_%1$s` * 0.01))))), 1) * (
                    SELECT `usage_time` FROM `category_usage_manual' . $object_device . '` WHERE `u_id` = :u_id AND `category` = "%1$s" AND `week` = 0 LIMIT 1
                )
            ) AS `usage_time` FROM `goal` WHERE `u_id` = :u_id AND `score_%1$s` > ' . REACH_GOAL_SCORE_LIMIT . ' AND `week` < :week;'
        );
    }
}

function init_define_UPDATE_CATEGORY_SCORE_SAVE_CALCULATE_SQL_STRING_TEMPLATE($object_device) {
    if (!defined('UPDATE_CATEGORY_SCORE_SAVE_CALCULATE_SQL_STRING_TEMPLATE')) {
        define('UPDATE_CATEGORY_SCORE_SAVE_CALCULATE_SQL_STRING_TEMPLATE',
            '`score_save_%1$s` = (
                SELECT (
                    (GREATEST((((EXP(SUM(LOG(1 - (`reduce_%1$s` * 0.01))))) * (
                        SELECT `usage_time` FROM `category_usage_manual' . $object_device . '` WHERE `u_id` = :u_id AND `category` = "%1$s" AND `week` = 0 LIMIT 1
                    )) - (
                        SELECT (
                            ((
                                SELECT `usage_time` FROM `category_usage_manual' . $object_device . '` WHERE `u_id` = :u_id AND `category` = "%1$s" AND `week` = (:week - 1) LIMIT 1
                            ) - (IFNULL((EXP(SUM(LOG(1 - (`reduce_%1$s` * 0.01))))), 1) * (
                                SELECT `usage_time` FROM `category_usage_manual' . $object_device . '` WHERE `u_id` = :u_id AND `category` = "%1$s" AND `week` = 0 LIMIT 1
                            )))
                        ) FROM `goal` WHERE (`u_id` = :u_id AND `score_%1$s` > ' . REACH_GOAL_SCORE_LIMIT . ' AND `week` < :week) OR (`u_id` = :u_id AND `week` = (:week - 1))
                    )), 0) >= (
                        SELECT `usage_time` FROM `category_usage_manual' . $object_device . '` WHERE `u_id` = :u_id AND `category` = "%1$s" AND `week` = :week LIMIT 1
                    )) * ' . REACH_GOAL_SCORE . '
                ) AS `update_score_save_%1$s` FROM `goal` WHERE (`u_id` = :u_id AND `score_%1$s` > ' . REACH_GOAL_SCORE_LIMIT . ') OR (`u_id` = :u_id AND `week` = :week)
            )'
        );
    }
}

function init_define_SELECT_CATEGORY_SCORE_SAVE_CALCULATE_SQL_STRING_TEMPLATE($object_device) {
    if (!defined('SELECT_CATEGORY_SCORE_SAVE_CALCULATE_SQL_STRING_TEMPLATE')) {
        define('SELECT_CATEGORY_SCORE_SAVE_CALCULATE_SQL_STRING_TEMPLATE',
            'SELECT (
                ((
                    SELECT `usage_time` FROM `category_usage_manual' . $object_device . '` WHERE `u_id` = :u_id AND `category` = "%1$s" AND `week` = (:week - 1) LIMIT 1
                ) - (IFNULL((EXP(SUM(LOG(1 - (`reduce_%1$s` * 0.01))))), 1) * (
                    SELECT `usage_time` FROM `category_usage_manual' . $object_device . '` WHERE `u_id` = :u_id AND `category` = "%1$s" AND `week` = 0 LIMIT 1
                )))
            ) AS `score_save_%1$s_time` FROM `goal` WHERE (`u_id` = :u_id AND `score_%1$s` > ' . REACH_GOAL_SCORE_LIMIT . ' AND `week` < :week) OR (`u_id` = :u_id AND `week` = (:week - 1));'
        );
    }
}

function init_define_IS_REACH_GOAL_SQL_STRING_TEMPLATE() {
    if (!defined('IS_REACH_GOAL_SQL_STRING_TEMPLATE')) {
        define('IS_REACH_GOAL_SQL_STRING_TEMPLATE',
            '(`score_%1$s` > ' . REACH_GOAL_SCORE_LIMIT . ') AS `reach_goal_%1$s`'
        );
    }
}

function init_define_IS_SAVE_GOAL_SQL_STRING_TEMPLATE() {
    if (!defined('IS_SAVE_GOAL_SQL_STRING_TEMPLATE')) {
        define('IS_SAVE_GOAL_SQL_STRING_TEMPLATE',
            '(`score_save_%1$s` > ' . REACH_GOAL_SCORE_LIMIT . ') AS `is_save_%1$s`'
        );
    }
}

// 取得當週的基準使用時間
function init_define_GET_NEW_BASELINE_USAGE_SQL_STRING_TEMPLATE($object_device) {
    if (!defined('GET_NEW_BASELINE_USAGE_SQL_STRING_TEMPLATE')) {
        define('GET_NEW_BASELINE_USAGE_SQL_STRING_TEMPLATE',
            'SELECT
                usage_time * (1 - (0.01 * SUM(goal.reduce_%1$s)))
            FROM
                goal,
                category_usage_manual' . $object_device . '
            WHERE
                goal.u_id = :u_id AND goal.score_%1$s > ' . REACH_GOAL_SCORE_LIMIT . '
                AND
                category_usage_manual' . $object_device . '.u_id = :u_id AND category_usage_manual' . $object_device . '.week = 0 AND category_usage_manual' . $object_device . '.category = "%1$s"');
    }
}

?>