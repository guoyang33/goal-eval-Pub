<?php
/**
 * 自我評估單
 */

use Cyouliao\Goaleval\Log;

include_once './../vendor/autoload.php';
require_once 'connect_db.php';
require_once 'get_user.php';

$week = $week - 1;      // 因為新版是在一週第一天填上一週的評估單
Log::d('week: ' . var_export($week, true));
Log::d('exp type: ' . $user['exp_type']);

if ($user['exp_type'] == 'set_goal') {        // 目標調控組
    if ($week == 0) {       // 第0週
        $form_name = FORM_NAME['self_evaluate']['android'][$user['exp_type']][$week];
        include 'form/self_evaluate/self_evaluate_android_set_goal_0.php';
        
    } else if ($week >= 1 && $week <= 8) {        // 第1-8週
        $form_name = FORM_NAME['self_evaluate']['android'][$user['exp_type']][$week];

        Log::d('include: form/self_evaluate/self_evaluate_android_set_goal_1-8.php');
        include 'form/self_evaluate/self_evaluate_android_set_goal_1-8.php';

    } else if ($week >= 9 && $week <= 24) {        // 第9-24週
        $form_name = FORM_NAME['self_evaluate']['android'][$user['exp_type']][$week];
        include 'form/self_evaluate/self_evaluate_android_set_goal_9-24.php';

    } else {
        echo '<div class="d-grid pt-5 pb-3 px-2">
        <h2 class="text-danger">週數錯誤，請聯絡研究人員</h2>
        <p>week: ';
        var_dump($week);
        echo '</p>
        </div>';
    }
} else if ($user['exp_type'] == 'training_strategy') {      // 策略調控組
    if ($week == 0) {       // 第0週
        $form_name = FORM_NAME['self_evaluate']['android'][$user['exp_type']][$week];
        include 'form/self_evaluate/self_evaluate_android_training_strategy_0.php';

    } else if ($week >= 1 && $week <= 8) {        // 第1-8週
        $form_name = FORM_NAME['self_evaluate']['android'][$user['exp_type']][$week];

        Log::d('include: form/self_evaluate/self_evaluate_android_training_strategy_1-8.php');
        include 'form/self_evaluate/self_evaluate_android_training_strategy_1-8.php';

    } else if ($week >= 9 && $week <= 24) {        // 第9-24週
        $form_name = FORM_NAME['self_evaluate']['android'][$user['exp_type']][$week];
        include 'form/self_evaluate/self_evaluate_android_training_strategy_9-24.php';

    } else {
        echo '<div class="d-grid pt-5 pb-3 px-2">
        <h2 class="text-danger">週數錯誤，請聯絡研究人員</h2>
        <p>week: ';
        var_dump($week);
        echo '</p>
        </div>';
    }
    
} else {
    echo '<div class="d-grid pt-5 pb-3 px-2">
    <h2 class="text-danger">組別錯誤，請聯絡研究人員</h2>
    <p>exp_type: ';
    var_dump($user['exp_type']);
    echo '</p>
    </div>';
}
?>