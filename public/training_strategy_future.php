<?php
/**
 * 第一招：設定目標前瞻未來(future)
 */
require_once 'connect_db.php';
require_once 'get_user.php';
require_once 'html_head.php';

if ($week == 1) {
    include 'training_strategy_future_tutorial_1.php';
} else if ($week == 2) {
    include 'training_strategy_future_tutorial_2.php';    
} else if ($week >= 3 && $week <= 4) {
    include 'training_strategy_future_practice.php';
} else {
    echo '<div class="d-grid pt-5 pb-3 px-2">
    <h2 class="text-danger">週數錯誤，請聯絡研究人員</h2>
    <p>week: ';
    var_dump($week);
    echo '</p>
    </div>';
}
?>