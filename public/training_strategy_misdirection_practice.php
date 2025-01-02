<?php
/**
 * 策略訓練 挑戰
 * Week 3, 4
 * 第三招：分散注意力(misdirection)
 * 第3週更新新增[更新時間: 2023-03-11 01:00~12:00]
 */
require_once 'html_head.php';
require_once 'get_user.php';
require_once 'training_strategy_practice_js.php';
?>
<form action="training_strategy_form_target.php" method="POST" autocomplete="off">
    <input type="hidden" name="u_id" value="<?php echo $user['id']; ?>">
    <input type="hidden" name="form_name" value="<?php echo FORM_NAME['training_strategy']['misdirection'][$week]; ?>">

    <!-- 第1階段 -->
    <div id="step_1" class="d-grid pt-3 px-2 form_group"></div>

    <!-- 第2階段 -->
    <div id="step_2" class="d-grid pt-3 px-2 d-none form_group">
        <div class="d-grid pt-5 pb-3 px-2">
            <h2>第三招：分散注意力</h2>
        </div>
        <div class="d-grid pt-2 px-2">
            <span>
                大華面對
                <span class="text-danger">想玩手機遊戲的渴求時</span>
                ，會去做其他有興趣的事情，例如五感安撫法：放一首自己喜愛的歌跟著哼、喝杯喜歡的茶、按摩肌肉、點香氛蠟燭、看遠處的風景，立刻就放鬆了下來，或是可以約朋友出來運動，也可以整理家裡的環境，生活過得更充實。
            </span>
        </div>

        <h3 class="pt-3">面對想玩手機遊戲的渴望時，立刻做其他有興趣的事情分散注意力。</h3>

        <div id="alert_2" class="d-none alert alert-danger">請確實輸入以下項目～</div>
        
        <div class="d-grid">
            <p>我們暫時放下遊戲，來做點不一樣的事吧！</p>
            <input id="input_action" type="text" class="form-control input-lg mt-1" name="answer_sheet[action]" placeholder="您會去做什麼呢?">
        </div>
        
        <div class="d-grid pt-3 px-2">
            <button type="button" class="btn btn-primary" onclick="form_step(3); return false;">繼續</button>
        </div>
    </div>

    <!-- 第3階段 -->
    <div id="step_3" class="d-grid pt-3 px-2 d-none form_group"></div>

    <!-- 第4階段 -->
    <div id="step_4" class="d-grid pt-3 px-2 d-none form_group">
        <h2>您已完成訓練！</h2>
        <input type="submit" class="btn btn-primary" value="完成">
    </div>
</form>
<script type="text/javascript">
    const WATCH_GAME_ADS_STEP_NUMBER = 1;
    const WATCH_GAME_ADS_FINAL_EVALUATE_STEP_NUMBER = 3;

    window.addEventListener("load", () => {
        initGameADSWatchStepBase();
        initGameADSWatchStep_1();
        initGameADSFinalEvaluateStep();
        
        // 傳入callback當該步驟完成會呼叫
        startIntoGameADSWatchStep(() => {form_step(2);});
    });

    function form_step(next_step_number, evaluate_answer = null, button_index = null) {
        let last_step = $("#step_" + (next_step_number - 1));
        let next_step = $("#step_" + next_step_number);
        if (next_step_number == 2) {
            // pass
        } else if (next_step_number == 3) {
            if ($("#input_action").val() == '') {
                $(`#alert_${(next_step_number - 1)}`).removeClass("d-none");
                return false;
            }

            // 如果都有效
            startIntoGameADSFinalEvaluateStep(() => {form_step(4);});
        } else if (next_step_number == 4) {/** pass */}
        $(".form_group").addClass("d-none");
        last_step.addClass("d-none");
        next_step.removeClass("d-none");
    }
</script>
<?php echo TRAINING_STRATEGY_PRACTICE_JS; ?>