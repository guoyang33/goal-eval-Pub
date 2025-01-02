<?php
/**
 * 策略訓練 挑戰
 * Week 3, 4
 * 第四招：正念數息(breathing)
 * 第3週更新新增[更新時間: 2023-03-11 01:00~12:00]
 */
require_once 'html_head.php';
require_once 'get_user.php';
require_once 'training_strategy_practice_js.php';
?>
<form action="training_strategy_form_target.php" method="POST" autocomplete="off">
    <input type="hidden" name="u_id" value="<?php echo $user['id']; ?>">
    <input type="hidden" name="form_name" value="<?php echo FORM_NAME['training_strategy']['breathing'][$week]; ?>">
    <input type="hidden" id="breathing_video_type" name="answer_sheet[breathing_video_type]" value="male">

    <!-- 第1階段 -->
    <div id="step_1" class="d-grid pt-3 px-2 form_group"></div>

    <!-- 第2階段 -->
    <div id="step_2" class="d-grid pt-3 px-2 d-none form_group">
        <div class="d-grid pt-5 pb-3 px-2">
            <h2>第四招：正念數息</h2>
        </div>

        <div class="d-grid pt-2 px-2">
            <span>
                大華面對
                <span class="text-danger">想玩手機遊戲的渴求</span>
                時，會練習正念數息，透過去注意自己的呼吸和觀察想玩遊戲的慾望，練習維持專注在深呼吸上，試著放鬆。
            </span>
        </div>

        <h3 class="pt-3">我們等等要開始進行正念數息練習，請您先評估目前感覺煩躁的程度。</h3>

        <h3 class="pt-3">請問您現在煩躁的程度有多高?</h3>
        
        <div id="alert_2" class="d-none alert alert-danger">請確實選取評量分數～</div>
        
        <div class="form-check">
            <input class="form-check-input radio_group_before" type="radio" name="answer_sheet[before]" id="radio_before_0" value="0" required>
            <label class="form-check-label" for="radio_before_0">
                (1) 完全沒有
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_before" type="radio" name="answer_sheet[before]" id="radio_before_1" value="1">
            <label class="form-check-label" for="radio_before_1">
                (2) 非常微弱
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_before" type="radio" name="answer_sheet[before]" id="radio_before_2" value="2">
            <label class="form-check-label" for="radio_before_2">
                (3) 輕微
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_before" type="radio" name="answer_sheet[before]" id="radio_before_3" value="3">
            <label class="form-check-label" for="radio_before_3">
                (4) 中等
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_before" type="radio" name="answer_sheet[before]" id="radio_before_4" value="4">
            <label class="form-check-label" for="radio_before_4">
                (5) 有點煩躁
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_before" type="radio" name="answer_sheet[before]" id="radio_before_5" value="5">
            <label class="form-check-label" for="radio_before_5">
                (6) 很煩躁
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_before" type="radio" name="answer_sheet[before]" id="radio_before_6" value="6">
            <label class="form-check-label" for="radio_before_6">
                (7) 非常煩躁
            </label>
        </div>

        <div class="d-grid pt-3 px-2">
            <button type="button" class="btn btn-primary" onclick="form_step(3); return false;">繼續</button>
        </div>
    </div>

    <!-- 第3階段 -->
    <div id="step_3" class="d-grid pt-3 px-2 d-none form_group">
        <div class="d-grid pt-5 pb-3 px-2">
            <h2>第四招：正念數息</h2>
        </div>

        <h3>我們要開始正念數息囉</h3>

        <p>請跟著影片中的指示完成正念數息的訓練，完成後請點擊下方「
            <span class="text-primary">繼續</span>
            」按鈕
        </p>

        <div class="px-5">
            <div class="col-md-6">
                <button type="button" class="btn btn-primary mx-1" style="float:left; width:45%" onclick="breathingVideoMale(); return false;">男聲版</button>
            </div>
            <div class="col-md-6">
                <button type="button" class="btn btn-primary mx-1" style="width:45%" onclick="breathingVideoFemale(); return false;">女聲版</button>
            </div>
        </div>

        <div class="d-grid pt-3">
            <iframe id="breathing_video_frame" width="100%" height="315" src="https://www.youtube.com/embed/wbcJ60UvayY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>

        <div class="d-grid pt-3 px-2">
            <button type="button" class="btn btn-primary" onclick="form_step(4); return false;">繼續</button>
        </div>
    </div>

    <!-- 第4階段 -->
    <div id="step_4" class="d-grid pt-3 px-2 d-none form_group">
        <div class="d-grid pt-5 pb-3 px-2">
            <h2>第四招：正念數息</h2>
        </div>

        <h3>厲害喔，您已完正念數息的訓練了</h3>

        <p>請您再完成下方煩燥程度的評估～</p>

        <div id="alert_4" class="d-none alert alert-danger">請確實選取評量分數～</div>

        <div class="form-check">
            <input class="form-check-input radio_group_after" type="radio" name="answer_sheet[after]" id="radio_after_0" value="0" required>
            <label class="form-check-label" for="radio_after_0">
                (1) 完全沒有
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_after" type="radio" name="answer_sheet[after]" id="radio_after_1" value="1">
            <label class="form-check-label" for="radio_after_1">
                (2) 非常微弱
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_after" type="radio" name="answer_sheet[after]" id="radio_after_2" value="2">
            <label class="form-check-label" for="radio_after_2">
                (3) 輕微
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_after" type="radio" name="answer_sheet[after]" id="radio_after_3" value="3">
            <label class="form-check-label" for="radio_after_3">
                (4) 中等
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_after" type="radio" name="answer_sheet[after]" id="radio_after_4" value="4">
            <label class="form-check-label" for="radio_after_4">
                (5) 有點煩躁
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_after" type="radio" name="answer_sheet[after]" id="radio_after_5" value="5">
            <label class="form-check-label" for="radio_after_5">
                (6) 很煩躁
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_after" type="radio" name="answer_sheet[after]" id="radio_after_6" value="6">
            <label class="form-check-label" for="radio_after_6">
                (7) 非常煩躁
            </label>
        </div>

        <div class="d-grid pt-3 px-2">
            <button type="button" class="btn btn-primary" onclick="form_step(5); return false;">繼續</button>
        </div>
    </div>

    <!-- 第5階段 -->
    <div id="step_5" class="d-grid pt-3 px-2 d-none form_group"></div>

    <!-- 第6階段 -->
    <div id="step_6" class="d-grid pt-3 px-2 d-none form_group">
        <h2>您已完成訓練！</h2>
        <input type="submit" class="btn btn-primary" value="完成">
    </div>
</form>
<script type="text/javascript">
    const WATCH_GAME_ADS_STEP_NUMBER = 1;
    const WATCH_GAME_ADS_FINAL_EVALUATE_STEP_NUMBER = 5;

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
            for (i=0; i<7; i++) {
                if ($(".radio_group_before")[i].checked) {
                    last_step.addClass("d-none");
                    next_step.removeClass("d-none");
                    return false;
                }
            }
            $(`#alert_${(next_step_number - 1)}`).removeClass("d-none");
            return false
        } else if (next_step_number == 4) {
            // pass
        } else if (next_step_number == 5) {
            for (i=0; i<7; i++) {
                if ($(".radio_group_after")[i].checked) {
                    last_step.addClass("d-none");
                    next_step.removeClass("d-none");

                    // 如果都有效
                    startIntoGameADSFinalEvaluateStep(() => {form_step(6);});

                    return false;
                }
            }
            $(`#alert_${(next_step_number - 1)}`).removeClass("d-none");
            return false
        } else if (next_step_number == 6) {/** pass */}
        $(".form_group").addClass("d-none");
        last_step.addClass("d-none");
        next_step.removeClass("d-none");
    }

    function breathingVideoMale() {
        $("#breathing_video_type").val("male");
        $("#breathing_video_frame").attr("src", "https://www.youtube.com/embed/wbcJ60UvayY");
    }
    
    function breathingVideoFemale() {
        $("#breathing_video_type").val("female");
        $("#breathing_video_frame").attr("src", "https://www.youtube.com/embed/yFipE1QIuOI");
    }

</script>
<?php echo TRAINING_STRATEGY_PRACTICE_JS; ?>