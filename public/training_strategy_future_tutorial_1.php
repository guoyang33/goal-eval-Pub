<?php
/**
 * 策略訓練 教學
 * Week 1
 * 第一招：設定目標前瞻未來(future)
 */
require_once 'html_head.php';
require_once 'get_user.php';
?>
<form action="training_strategy_form_target.php" method="POST">
    <input type="hidden" name="u_id" value="<?php echo $user['id']; ?>">
    <input type="hidden" name="form_name" value="training_strategy_future_tutorial_1">
    <!-- <input type="hidden" id="evaluate" name="answer_sheet[evaluate]"> -->

    <!-- 第1階段 -->
    <div id="step_1" class="d-grid pt-3 px-2 form_group">
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

        <div id="alert_1" class="d-none alert alert-danger">請確實輸入您的目標～</div>
        
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
            <button class="btn btn-primary" onclick="form_step(2); return false;">繼續</button>
        </div>
    </div>

    <!-- 第2階段 -->
    <div id="step_2" class="d-grid pt-3 px-2 d-none form_group">
        <div class="d-grid pt-5 pb-3 px-2">
            <h2>認知策略評量(共1題)</h2>
        </div>
        <h3 class="pt-3">遊戲好誘人，為了抵擋誘惑，您首先要怎麼做?</h3>

        <div class="d-grid pt-3 px-2">
            <button id="step_2_option_0" class="btn border border-primary" onclick="$('#response_3-1').removeClass('d-none'); $('#response_3-2').addClass('d-none'); $('#step_2_next_btn').addClass('d-none'); $('#step_2_option_0').addClass('btn-danger'); $('#step_2_option_1').removeClass('btn-info'); return false;">下次玩少一點，應該不會怎樣吧!</button>
        </div>
        <div class="d-grid pt-3 px-2">
            <button id="step_2_option_1" class="btn border border-primary" onclick="$('#response_3-2').removeClass('d-none'); $('#response_3-1').addClass('d-none'); $('#step_2_next_btn').removeClass('d-none'); $('#step_2_option_0').removeClass('btn-danger'); $('#step_2_option_1').addClass('btn-info'); return false;">設定短、中、長期目標，時時自我提醒!</button>
        </div>
        <div class="d-grid pt-3 px-2">
            <h3 id="response_3-1" class="pt-3 text-success d-none">你看來是百年難得一見的練武奇才！請給自己一個機會試看看我們的策略。（請重新選擇）</h3>
            <h3 id="response_3-2" class="pt-3 text-success d-none">您好有行動力!</h3>
            <button id="step_2_next_btn" class="btn btn-primary d-none" onclick="form_step(3); return false;">繼續</button>
        </div>
    </div>

    <!-- 第3階段 -->
    <div id="step_3" class="d-grid pt-3 px-2 d-none form_group">
        <h2>您已完成訓練！</h2>
        <input type="submit" class="btn btn-primary" value="完成">
    </div>
</form>
<script type="text/javascript">
    function form_step(step, evaluate_answer = null, button_index = null) {
        let last_step = $("#step_"+(step-1));
        let next_step = $("#step_"+step);
        if (step == 2) {
            if ($("#short_term").val().length == 0) {
                $("#alert_1").removeClass("d-none");
                return false;
            }
            if ($("#medium_term").val().length == 0) {
                $("#alert_1").removeClass("d-none");
                return false;
            }
            if ($("#long_term").val().length == 0) {
                $("#alert_1").removeClass("d-none");
                return false;
            }
            if ($("#short_term").val() != "" && $("#medium_term").val() != "" && $("#long_term").val() != "") {
            } else {
                $("#alert_1").removeClass("d-none");
            }
        }
        if (step == 3) {
            /*
            if (evaluate_answer) {
                $("#evaluate").val(1);
                if (Math.floor(Math.random()*2)) {      // 產生0或1
                    $("#response_3-1").removeClass("d-none");
                } else {
                    $("#response_3-2").removeClass("d-none");
                }
            } else {
                $("#evaluate").val(0);
            }
            */
        }
        $(".form_group").addClass("d-none");
        last_step.addClass("d-none");
        next_step.removeClass("d-none");
    }
</script>