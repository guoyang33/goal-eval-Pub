<?php
/**
 * 顯示拼圖
 * 把新到舊的拼圖一一展示
 */
require_once 'connect_db.php';
require_once 'get_user.php';
require_once 'html_head.php';

if ($user['test'] == 1) {
    if (key_exists('week', $_GET)) {
        $week = $_GET['week'];
    }
}

// 自我評估單拼圖
/**
 * 拼圖檔的命名為
 * self_evaluate_0-7.jpg    
 * self_evaluate_8.jpg
 * self_evaluate_9-16.jpg
 * self_evaluate_17-24.jpg
 * 第一組數字為評估單週數，所以要轉換成顯示的對應週數
 */
if ($week >= 1 && $week <= 7) {                 // 第0-6週結束，第1-7週顯示未完成拼圖
    echo '<img class="img-fluid" src="img/self_evaluate_0-7_'.($week).'.jpg">';
} else if ($week == 8) {                        // 第7週結束，第8週顯示第一張完整拼圖
    echo '<img class="img-fluid" src="img/self_evaluate_0-7.jpg">';
} else if ($week == 9) {                        // 第8週結束，第9週顯示獨立完整拼圖
    echo '<img class="img-fluid" src="img/self_evaluate_8.jpg">';
} else if ($week >= 10 && $week <= 15) {         // 第9-14週結束，第10-15週顯示未完成拼圖
    echo '<img class="img-fluid" src="img/self_evaluate_9-16_'.($week-9).'.jpg">';
} else if ($week == 16) {                       // 第15週結束，第16週顯示第一張完整拼圖
    echo '<img class="img-fluid" src="img/self_evaluate_9-16.jpg">';
} else if ($week >= 17 && $week <= 23) {        // 第16-22週結束，第17-23週顯示未完成拼圖
    echo '<img class="img-fluid" src="img/self_evaluate_17-24_'.($week-16).'.jpg">';
} else if ($week >= 24) {                       // 第23週結束，第24+週顯示第一張完整拼圖
    echo '<img class="img-fluid" src="img/self_evaluate_17-24.jpg">';
}
?>