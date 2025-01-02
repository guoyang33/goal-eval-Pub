<?php
/**
 * 策略訓練 教學
 * Week 2
 * 第一招：設定目標前瞻未來(future)
 */
require_once 'html_head.php';
require_once 'get_user.php';
?>
<form action="training_strategy_form_target.php" method="POST">
    <input type="hidden" name="u_id" value="<?php echo $user['id']; ?>">
    <input type="hidden" name="form_name" value="training_strategy_future_tutorial_2">
    <input type="hidden" id="evaluate_1" name="answer_sheet[evaluate][1]">
    <input type="hidden" id="evaluate_2" name="answer_sheet[evaluate][2]">
    <input type="hidden" id="evaluate_3" name="answer_sheet[evaluate][3]">

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
            <h2>認知策略實踐評量(共3題)</h2>
        </div>
        <h3 class="pt-3">
            面對想玩遊戲的渴求時，自控力的力量是關鍵，以下哪一個
            <span class="text-danger font-weight-bold">不是</span>
            自控力(延遲享樂)?
        </h3>
        <div id="alert_evaluate_1_incorrect" class="d-none alert alert-danger">答錯，請您再想想看。</div>

        <div class="d-grid pt-3 px-2">
            <button class="btn border border-primary button_group_evaluate_1" onclick="form_step(3, false, 0); return false;">當有立即小獎賞和較慢的大獎賞時，可以選擇等待，讓自己得到大獎賞。</button>
        </div>
        <div class="d-grid pt-3 px-2">
            <button class="btn border border-primary button_group_evaluate_1" onclick="form_step(3, false, 1); return false;">研究給小朋友1顆棉花糖，等10分鐘不吃的話，可以獲得第2顆棉花糖，發現能夠選擇等待10分鐘的小朋友，其之後的成績、工作成就顯著比立即吃棉花糖的小朋友還要好。</button>
        </div>
        <div class="d-grid pt-3 px-2">
            <button class="btn border border-primary button_group_evaluate_1" onclick="form_step(3, false, 2); return false;">學會自控力也比較不會沈迷，更懂得拒絕誘惑。</button>
        </div>
        <div class="d-grid pt-3 px-2">
            <button class="btn border border-primary button_group_evaluate_1" onclick="form_step(3, true, 3); return false;">反正遲早都要做作業，先玩先爽再說。</button>
        </div>
    </div>

    <!-- 第3階段 -->
    <div id="step_3" class="d-grid pt-3 px-2 d-none form_group">
        <div class="d-grid pt-5 pb-3 px-2">
            <h2>認知策略實踐評量(共3題)</h2>
        </div>
        <h3 id="response_3_correct" class="pt-3 text-success">答對，您對自控力了解很多，請再接再厲!</h3>

        <button class="btn btn-primary" onclick="form_step(4); return false;">繼續</button>
    </div>

    <!-- 第4階段 -->
    <div id="step_4" class="d-grid pt-3 px-2 d-none form_group">
        <div class="d-grid pt-5 pb-3 px-2">
            <h2>認知策略實踐評量(共3題)</h2>
        </div>
        <h3 class="pt-3">為什麼自控力(延遲享樂)很重要?</h3>
        <div id="alert_evaluate_2_incorrect" class="d-none alert alert-danger">答錯，請您再想想看。</div>

        <div class="d-grid pt-3 px-2">
            <button class="btn border border-primary button_group_evaluate_2" onclick="form_step(5, true, 0); return false;">研究證實，自控力高未來的成就也高!</button>
        </div>
        <div class="d-grid pt-3 px-2">
            <button class="btn border border-primary button_group_evaluate_2" onclick="form_step(5, false, 1); return false;">研究證實，延遲享樂沒有好處。</button>
        </div>
        <div class="d-grid pt-3 px-2">
            <button class="btn border border-primary button_group_evaluate_2" onclick="form_step(5, false, 2); return false;">研究證實，自控力高未來的成就較低!</button>
        </div>
        <div class="d-grid pt-3 px-2">
            <button class="btn border border-primary button_group_evaluate_2" onclick="form_step(5, false, 3); return false;">研究證實，自控力跟未來成就無關。</button>
        </div>
    </div>

    <!-- 第5階段 -->
    <div id="step_5" class="d-grid pt-3 px-2 d-none form_group">
        <div class="d-grid pt-5 pb-3 px-2">
            <h2>認知策略實踐評量(共3題)</h2>
        </div>
        <h3 id="response_5_correct" class="pt-3 text-success">答對，您對自控力了解不錯，請再接再厲!</h3>

        <button class="btn btn-primary" onclick="form_step(6); return false;">繼續</button>
    </div>

    <!-- 第6階段 -->
    <div id="step_6" class="d-grid pt-3 px-2 d-none form_group">
        <div class="d-grid pt-5 pb-3 px-2">
            <h2>認知策略實踐評量(共3題)</h2>
        </div>
        <div id="alert_evaluate_3_incorrect" class="d-none alert alert-danger">答錯，請您再想想看。</div>

        <h3 class="pt-3">
            下列哪些是訓練自控力的策略?
            <span class="text-danger font-weight-bold">(複選題)</span>
        </h3>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="evaluate_3_check_1">
            <label class="form-check-label" for="evaluate_3_check_1">
                設定自我目標。
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="evaluate_3_check_2">
            <label class="form-check-label" for="evaluate_3_check_2">
                想一想玩遊戲的好壞處。
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="evaluate_3_check_3">
            <label class="form-check-label" for="evaluate_3_check_3">
                分散注意力。
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="evaluate_3_check_4">
            <label class="form-check-label" for="evaluate_3_check_4">
                練習正念數息。
            </label>
        </div>

        <div class="d-grid pt-3 px-2">
            <button class="btn btn-primary" onclick="form_step(7); return false;">繼續</button>
        </div>
    </div>

    <!-- 第7階段 -->
    <div id="step_7" class="d-grid pt-3 px-2 d-none form_group">
        <div class="d-grid pt-5 pb-3 px-2">
            <h2>認知策略實踐評量(共3題)</h2>
        </div>
        <h3 id="response_7_correct" class="pt-3 text-success">答對，您的自控力表現出色!邁向自控力達人!</h3>

        <input type="submit" class="btn btn-primary" value="繼續">
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
        } else if (step == 3) {
            if ($("#evaluate_1").val().length == 0) {
                if (evaluate_answer) {
                    $("#evaluate_1").val(1);
                } else {
                    $("#evaluate_1").val(0);
                }
            }
            if (evaluate_answer == false) {
                $("#alert_evaluate_1_incorrect").removeClass("d-none");
                $(".button_group_evaluate_1")[button_index].classList.remove("border-primary");
                $(".button_group_evaluate_1")[button_index].classList.add("border-secondary");
                $(".button_group_evaluate_1")[button_index].setAttribute("disabled", true);
                return false;
            }
        } else if (step == 5) {
            if ($("#evaluate_2").val().length == 0) {
                if (evaluate_answer) {
                    $("#evaluate_2").val(1);
                } else {
                    $("#evaluate_2").val(0);
                }
            }
            if (evaluate_answer == false) {
                $("#alert_evaluate_2_incorrect").removeClass("d-none");
                $(".button_group_evaluate_2")[button_index].classList.remove("border-primary");
                $(".button_group_evaluate_2")[button_index].classList.add("border-secondary");
                $(".button_group_evaluate_2")[button_index].setAttribute("disabled", true);
                return false;
            }
        } else if (step == 7) {
            if ($("#evaluate_3").val().length == 0) {
                if ($("#evaluate_3_check_1").is(":checked") && $("#evaluate_3_check_2").is(":checked") && $("#evaluate_3_check_3").is(":checked") && $("#evaluate_3_check_4").is(":checked")) {
                    $("#evaluate_3").val(1);
                } else {
                    $("#evaluate_3").val(0);
                }
            }
            if ($("#evaluate_3_check_1").is(":checked") && $("#evaluate_3_check_2").is(":checked") && $("#evaluate_3_check_3").is(":checked") && $("#evaluate_3_check_4").is(":checked")) {
            } else {
                $("#alert_evaluate_3_incorrect").removeClass("d-none");
                return false;
            }
        }
        $(".form_group").addClass("d-none");
        last_step.addClass("d-none");
        next_step.removeClass("d-none");
    }
</script>