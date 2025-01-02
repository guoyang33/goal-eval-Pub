<?php
/**
 * 策略訓練 應用
 * Week 5~8
 * 第二招：好壞處分析(advantages)
 * 第4週第1次更新新增[更新時間: 2023-03-23 01:00~12:00]
 */
require_once 'html_head.php';
require_once 'get_user.php';
require_once 'training_strategy_apply_js.php';
?>
<form action="training_strategy_apply_form_submit.php" method="POST" autocomplete="off">
    <input type="hidden" name="u_id" value="<?php echo $user['id']; ?>">
    <input type="hidden" name="form_name" value="<?php echo FORM_NAME['training_strategy']['apply'][$week]; ?>">
    <input type="hidden" name="answer_sheet[apply_type]" value="advantages">
    <input type="hidden" name="answer_sheet[apply_count]" value="<?php echo ($apply_count + 1); /** $apply_count於redirect檔案宣告 */ ?>">
    <?php /** confirm_data用於提交時判定是否重複提交 */ ?>
    <input type="hidden" name="confirm_data[apply_count]" value="<?php echo ($apply_count + 1); ?>">
    <input type="hidden" name="confirm_data[week]" value="<?php echo $week; ?>">

    <!-- 第1階段 -->
    <div id="step_1" class="d-grid pt-3 px-2 form_group"></div>

    <!-- 第2階段 -->
    <div id="step_2" class="d-grid pt-3 px-2 d-none form_group">
    <div class="d-grid pt-5 pb-3 px-2">
            <h2>第二招：好壞處分析</h2>
        </div>
        <div class="d-grid pt-2 px-2">
            <span>
                大華面對
                <span class="text-danger">想玩手機遊戲的渴求時</span>
                ，會想一想玩遊戲有好壞處，好處是玩得高興、能逃避現實、紓解壓力、找到朋友、打發時間，但壞處是重要的事情沒做、可用時間變少、眼睛疲勞、久坐不動、肌肉痠痛、作息不正常、和親友相處時間減少．．．
            </span>
        </div>

        <h3 class="pt-3">面對想玩手機遊戲的渴望時，先思考玩或不玩的好壞處。</h3>

        <div id="alert_2" class="d-none alert alert-danger">請確實輸入以下項目～</div>
        
        <div class="d-grid gap-2">
            <div class="p-2 gap-2 border">
                <span class="pt-2">
                    您認為去玩遊戲有哪些
                    <span class="text-primary font-weight-bold">好處</span>
                    ?
                </span>
                <input type="text" class="form-control input-lg mt-1" name="answer_sheet[advantage][]" placeholder="好處1" required>
                <input type="text" class="form-control input-lg mt-1" name="answer_sheet[advantage][]" placeholder="好處2" required>
                <input type="text" class="form-control input-lg mt-1" name="answer_sheet[advantage][]" placeholder="好處3" required>
                <input type="text" class="form-control input-lg mt-1" name="answer_sheet[advantage][]" placeholder="好處4" required>
                <input type="text" class="form-control input-lg mt-1" name="answer_sheet[advantage][]" placeholder="好處5" required>
            </div>
            <div class="p-2 border">
                <span class="pt-2">
                    您認為去玩遊戲有哪些
                    <span class="text-danger font-weight-bold">壞處</span>
                    ?
                </span>
                <input type="text" class="form-control input-lg mt-1" name="answer_sheet[disadvantage][]" placeholder="壞處1" required>
                <input type="text" class="form-control input-lg mt-1" name="answer_sheet[disadvantage][]" placeholder="壞處2" required>
                <input type="text" class="form-control input-lg mt-1" name="answer_sheet[disadvantage][]" placeholder="壞處3" required>
                <input type="text" class="form-control input-lg mt-1" name="answer_sheet[disadvantage][]" placeholder="壞處4" required>
                <input type="text" class="form-control input-lg mt-1" name="answer_sheet[disadvantage][]" placeholder="壞處5" required>
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
            for (i=0; i<10; i++) {
                if ($(".form-control")[i].value.length == 0) {
                    $(`#alert_${(next_step_number - 1)}`).removeClass("d-none");
                    return false;
                }
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