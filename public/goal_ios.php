<?php
/**
 * v7_1新增
 * 查看擁有的獎勵拼圖 Ios
 */
require_once 'connect_db.php';
require_once 'get_user.php';
require_once 'html_head.php';

require_once 'goal_lib.php';



if ($user['exp_id'][0] == 'A') {
    header("Location: goal_android.php?u_id={$user['id']}");
    exit();
}

// 常數
if (!defined('OBJECT_DEVICE')) {
    define('OBJECT_DEVICE', '_ios');
}

echo '<div class="d-grid pt-3 pb-3 px-2">';

echo '<h2>自我評估單拼圖：</h2>';
foreach (get_goal_self_evaluate($dbh, OBJECT_DEVICE, $user['id'], $user['exp_type']) as $image) {
    echo '<img class="img-fluid" src="' . $image . '"><br>';
}
echo '<br>';

if ($user['exp_type'] == 'training_strategy') {
    echo '<h2>策略訓練成就：</h2>';
    foreach (get_goal_training_strategy($dbh, $user['id']) as $image) {
        echo '<div><img class="w-50" src="' . $image['img'] . '"><span class="fs-1">&nbsp;X&nbsp;' . $image['times'] . '</span></div><br>';
    }
    echo '<br>';
}

echo '<h2>各App類別拼圖：</h2>';
foreach (get_goal_category_score($dbh, OBJECT_DEVICE, $user['id']) as $image) {
    echo '<h3>' . $image['name_zh'] . '：</h3><img class="img-fluid" src="' . $image['img'] . '"><br>';
}
echo '<br>';

echo '<br><button type="button" class="btn btn-primary" onclick="window.location.replace(' . "'redirect_ios.php?u_id={$user['id']}'" . ');">
返回主選單
</button>';

echo '</div>';
?>