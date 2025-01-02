<?php
/**
 * 策略訓練 挑戰
 * Week 3, 4
 * 第一招：設定目標前瞻未來(future)
 * 第3週更新新增[更新時間: 2023-03-11 01:00~12:00]
 */
require_once 'html_head.php';
require_once 'get_user.php';
require_once 'training_strategy_practice_js.php';
?>
<form action="training_strategy_form_target.php" method="POST" autocomplete="off">
    <input type="hidden" name="u_id" value="<?php echo $user['id']; ?>">
    <input type="hidden" name="form_name" value="<?php echo FORM_NAME['training_strategy']['future'][$week]; ?>">

    <!-- 第1階段 -->
    <div id="step_1" class="d-grid pt-3 px-2 form_group"></div>

    <!-- 第2階段 -->
    <div id="step_2" class="d-grid pt-3 px-2 d-none form_group">
        <div class="d-grid pt-5 pb-3 px-2">
            <h2>第一招：設定目標前瞻未來</h2>
        </div>
        <div class="d-grid pt-2 px-2">
            <span>
                當大華
                <span class="text-danger">想玩遊戲的渴求</span>
                來的時候，會想想自己未來的目標，短期(這禮拜減少時間)、中期(這學期成績要80分)、長期(順利畢業成為工程師)的目標。
            </span>
        </div>

        <h3 class="pt-3">面對想玩手機遊戲的渴求時，先設定自己的目標。</h3>

        <div id="alert_2" class="d-none alert alert-danger">請確實輸入您的目標～</div>
        
        <div class="d-grid gap-2">
            <div class="p-2 border">
                <span class="pt-2">
                    您的
                    <span class="text-danger">短期</span>
                    目標?
                </span>
                <input id="short_term" type="text" class="form-control input-lg" name="answer_sheet[short_term]" required>
            </div>
            <div class="p-2 border">
                <span class="pt-2">
                    您的
                    <span class="text-danger">中期</span>
                    目標?
                </span>
                <input id="medium_term" type="text" class="form-control input-lg" name="answer_sheet[medium_term]" required>
            </div>
            <div class="p-2 border">
                <span class="pt-2">
                    您的
                    <span class="text-danger">長期</span>
                    目標?
                </span>
                <input id="long_term" type="text" class="form-control input-lg" name="answer_sheet[long_term]" required>
            </div>
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
            if ($("#short_term").val().length == 0) {
                $(`#alert_${(next_step_number - 1)}`).removeClass("d-none");
                return false;
            }
            if ($("#medium_term").val().length == 0) {
                $(`#alert_${(next_step_number - 1)}`).removeClass("d-none");
                return false;
            }
            if ($("#long_term").val().length == 0) {
                $(`#alert_${(next_step_number - 1)}`).removeClass("d-none");
                return false;
            }
            if ($("#short_term").val() != "" && $("#medium_term").val() != "" && $("#long_term").val() != "") {
                // 如果都有效
                startIntoGameADSFinalEvaluateStep(() => {form_step(4);});
            } else {
                $(`#alert_${(next_step_number - 1)}`).removeClass("d-none");
                return false;
            }
        } else if (next_step_number == 4) {/** pass */}
        $(".form_group").addClass("d-none");
        last_step.addClass("d-none");
        next_step.removeClass("d-none");
    }
</script>
<?php echo TRAINING_STRATEGY_PRACTICE_JS; ?>