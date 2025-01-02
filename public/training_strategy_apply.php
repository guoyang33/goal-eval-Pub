<?php
/**
 * 第4週 第一次更新版本 新增
 * 策略訓練 應用 轉址
 */
require_once 'connect_db.php';
require_once 'get_user.php';
require_once 'html_head.php';



if (array_key_exists('picked_training_strategy', $_POST)) {
    switch ($_POST['picked_training_strategy']) {
        case 'future':
            include_once 'training_strategy_future_apply.php';
            break;
        case 'advantages':
            include_once 'training_strategy_advantages_apply.php';
            break;
        case 'misdirection':
            include_once 'training_strategy_misdirection_apply.php';
            break;
        case 'breathing':
            include_once 'training_strategy_breathing_apply.php';
            break;
        default:
            echo '<div class="d-grid pt-5 pb-3 px-2">
                <h2 class="text-danger">策略訓練應用選項錯誤，請聯絡研究人員</h2>
                <p>picked_training_strategy: ';
            var_dump($_POST['picked_training_strategy']);
            echo '</p></div>';
    }
} else {
    echo '<form action="redirect' . ((OBJECT_DEVICE === '_ios') ? '_ios' : '') . '.php?u_id=' . $user['id'] . '" method="POST">
        <h1 class="d-flex justify-content-center">請選擇要進行的策略訓練</h1>
        <h3>本週已完成的策略訓練數量：' . $apply_count . '</h3>
        <div class="d-flex justify-content-center"><!-- 註[1] -->
            <span><!-- 註[2] -->
                <label for="option_future" class="btn btn-outline-primary">
                    設定目標前瞻未來
                    <!-- 註[3] <img src="" alt="設定目標前瞻未來"> -->
                </label>
                <input type="submit" id="option_future" class="btn-check d-none" name="picked_training_strategy" value="future">
            </span>
            &nbsp;&nbsp;&nbsp;
            <span>
                <label for="option_advantages" class="btn btn-outline-primary">
                    好壞處分析
                    <!-- <img src="" alt="好壞處分析"> -->
                </label>
                <input type="submit" id="option_advantages" class="btn-check d-none" name="picked_training_strategy" value="advantages">
            </span>
        </div>
        <br>
        <div class="d-flex justify-content-center">
            <span>
                <label for="option_misdirection" class="btn btn-outline-primary">
                    分散注意力
                    <!-- <img src="" alt="分散注意力"> -->
                </label>
                <input type="submit" id="option_misdirection" class="btn-check d-none" name="picked_training_strategy" value="misdirection">
            </span>
            &nbsp;&nbsp;&nbsp;
            <span>
                <label for="option_breathing" class="btn btn-outline-primary">
                    正念數息
                    <!-- <img src="" alt="正念數息"> -->
                </label>
                <input type="submit" id="option_breathing" class="btn-check d-none" name="picked_training_strategy" value="breathing">
            </span>
        </div>
    </form>';
}

/**
 * 註解
 * [1] : class="d-flex justify-content-center"為置中用
 * [2] : 以span包住才能使按鈕hover正常顯示，且input元素需在label之後
 * [3] : 可於之後加入圖片變成按鈕圖片
 */

?>