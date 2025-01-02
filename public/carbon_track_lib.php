<?php
/*
 * carbon_track_lib.php
 * carbon tracking library
 * 碳足跡函式庫
 * 上週使用時間與上上週使用時間的差值SQL模板函數 init_define_SELECT_USAGE_CURRENT_LAST_SQL_TEMPLATE()->(const String)SQL模板
 * 宣告碳足跡小語常數陣列 CARBON_TRACK_SLOGAN[week]->(Array)[類別{達標|未達標|失敗}]->(String)小語
 */

// 宣告：查詢上週使用時間與上上週使用時間的SQL模板
// PDO 參數: u_id, last_week, current_week, category
// 查詢結果：table(last_week, current_week)
function init_define_SELECT_USAGE_TIME_CURRENT_LAST_SQL_TEMPLATE($object_device) {
    if (!defined('SELECT_USAGE_TIME_CURRENT_LAST_SQL_TEMPLATE')) {
        define('SELECT_USAGE_TIME_CURRENT_LAST_SQL_TEMPLATE',
            'SELECT LAST_WEEK.usage_time AS last_week, CURRENT_WEEK.usage_time AS current_week
            FROM (
                SELECT usage_time
                FROM category_usage_manual' . $object_device . '
                WHERE u_id = :u_id AND week = :last_week AND category = :category
                GROUP BY u_id
            ) LAST_WEEK,
            (
                SELECT usage_time
                FROM category_usage_manual' . $object_device . '
                WHERE u_id = :u_id AND week = :current_week AND category = :category
                GROUP BY u_id
            ) CURRENT_WEEK'
        );
    }
}

// 宣告碳足跡小語常數陣列
function init_define_CARBON_TRACK_SLOGAN() {
    if (!defined('CARBON_TRACK_SLOGAN')) {
        /*
         * goal-less: 達標 比上週減少使用時間
         * goal-more: 達標 比上週增加使用時間
         * fail-less: 失敗 比上週減少使用時間
         * fail-more: 失敗 比上週增加使用時間
         */
        define('CARBON_TRACK_SLOGAN', array(
            1 => array(
                'goal-less' => '<span class="text-success">我很棒！減少使用和碳排量，我也為地球永續發展盡一份心力！</span>',
                'goal-more' => '<span class="text-success">我達標很棒！<span class="text-danger">不再等待，節能減碳從現在開始！></span>',
                'fail-less' => '<span class="text-danger">雖有挫折，我再努力！ <span class="text-success">我減少使用，節能減碳盡心力！</span></span>',
                'fail-more' => '<span class="text-danger">雖有挫折，我再努力！ 不再等待，節能減碳從現在開始！</span>'
            ),
            2 => array(
                'goal-less' => '<span class="text-success">我執行力強！減少耗能及碳排放，我用行動愛護地球！</span>',
                'goal-more' => '<span class="text-success">我執行力強！<span class="text-danger">節能減碳的小改變，可以造成大影響！></span>',
                'fail-less' => '<span class="text-danger">我會愈挫愈勇，繼續努力！ <span class="text-success">我減少使用，節能減碳愛地球！</span></span>',
                'fail-more' => '<span class="text-danger">我會愈挫愈勇，繼續努力！ 節能減碳的小改變，可以造成大影響！</span>'
            ),
            3 => array(
                'goal-less' => '<span class="text-success">我懂得拒絕誘惑！節能減碳，我創造更美好的世界！</span>',
                'goal-more' => '<span class="text-success">我懂得拒絕誘惑！<span class="text-danger">肯定自己，相信我也可以減碳愛地球！></span>',
                'fail-less' => '<span class="text-danger">只是一時挫折，再努力會達標！ <span class="text-success">我減少使用，節能減碳美好世界！</span></span>',
                'fail-more' => '<span class="text-danger">只是一時挫折，再努力會達標！ 肯定自己，相信我也可以減碳愛地球！</span>'
            ),
            4 => array(
                'goal-less' => '<span class="text-success">我很會時間管理！節約用電，我讓地球展現微笑！</span>',
                'goal-more' => '<span class="text-success">我很會時間管理！<span class="text-danger">現在開始減碳，是為未來種下幸福的種子！></span>',
                'fail-less' => '<span class="text-danger">面對誘惑，找出方法，我會克服！ <span class="text-success">我減少使用，節能減碳護環境！</span></span>',
                'fail-more' => '<span class="text-danger">面對誘惑，找出方法，我會克服！ 現在開始減碳，是為未來種下幸福的種子！</span>'
            ),
            5 => array(
                'goal-less' => '<span class="text-success">我很有自控力！節能有功，減碳有情，我愛護地球無價！</span>',
                'goal-more' => '<span class="text-success">我很有自控力！<span class="text-danger">一起攜手減碳，守護地球家園！></span>',
                'fail-less' => '<span class="text-danger">失敗是成功之母，找方法再努力，我會成功！ <span class="text-success">我減少使用，減碳做環保！</span></span>',
                'fail-more' => '<span class="text-danger">失敗是成功之母，找方法再努力，我會成功！ 一起攜手減碳，守護地球家園！</span>'
            ),
            6 => array(
                'goal-less' => '<span class="text-success">我是問題解決高手！節能減碳表現出我對地球的愛！</span>',
                'goal-more' => '<span class="text-success">我是問題解決高手！<span class="text-danger">及時用心節能減碳，共創環保未來！></span>',
                'fail-less' => '<span class="text-danger">只要我堅持努力，我會成功！ <span class="text-success">我減少使用，節能減碳更幸福！</span></span>',
                'fail-more' => '<span class="text-danger">只要我堅持努力，我會成功！ 及時用心節能減碳，共創環保未來！</span>'
            ),
            7 => array(
                'goal-less' => '<span class="text-success">我很有策略達成目標！減少碳足跡，讓我增加幸福指數！</span>',
                'goal-more' => '<span class="text-success">我很有策略達成目標！<span class="text-danger">減少碳足跡，小小行動，大大改變！></span>',
                'fail-less' => '<span class="text-danger">挫折只是一時的，不放棄的我會成功！ <span class="text-success">我減少使用，節能減碳為未來！</span></span>',
                'fail-more' => '<span class="text-danger">挫折只是一時，不放棄的我會成功！ 減少碳足跡，小小行動，大大改變！</span>'
            ),
            8 => array(
                'goal-less' => '<span class="text-success">我自制、我能堅持到底！每次的節能減碳，都是為永續未來的著想！</span>',
                'goal-more' => '<span class="text-success">我自制、我能堅持到底！<span class="text-danger">為了未來，開始節能減碳，守護地球！></span>',
                'fail-less' => '<span class="text-danger">我從挫折中學習成長，會越做越好！ <span class="text-success">我減少使用，節能減碳護家園！</span></span>',
                'fail-more' => '<span class="text-danger">我從挫折中學習成長，會越做越好！ 為了未來，開始節能減碳，守護地球！</span>'
            ),
        ));
    }
}