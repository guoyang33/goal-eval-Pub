<?php
/**
 * 策略訓練 教學
 * Week 2
 * 第四招：正念數息(breathing)
 */
require_once 'html_head.php';
require_once 'get_user.php';
?>
<form action="training_strategy_form_target.php" method="POST">
    <input type="hidden" name="u_id" value="<?php echo $user['id']; ?>">
    <input type="hidden" name="form_name" value="training_strategy_breathing_tutorial_2">
    <input type="hidden" id="breathing_video_type" name="answer_sheet[breathing_video_type]" value="male">
    <input type="hidden" id="evaluate_1" name="answer_sheet[evaluate][1]">
    <input type="hidden" id="evaluate_2" name="answer_sheet[evaluate][2]">
    <input type="hidden" id="evaluate_3" name="answer_sheet[evaluate][3]">

    <!-- 第1階段 -->
    <div id="step_1" class="d-grid pt-3 px-2 form_group">
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
        
        <div id="alert_1" class="d-none alert alert-danger">請確實選取評量分數～</div>
        
        <div class="form-check">
            <input class="form-check-input radio_group_before" type="radio" name="answer_sheet[before]" id="radio_before_0">
            <label class="form-check-label" for="radio_before_0">
                (0) 完全沒有
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_before" type="radio" name="answer_sheet[before]" id="radio_before_1">
            <label class="form-check-label" for="radio_before_1">
                (1) 非常微弱
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_before" type="radio" name="answer_sheet[before]" id="radio_before_2">
            <label class="form-check-label" for="radio_before_2">
                (2) 輕微
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_before" type="radio" name="answer_sheet[before]" id="radio_before_3">
            <label class="form-check-label" for="radio_before_3">
                (3) 中等
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_before" type="radio" name="answer_sheet[before]" id="radio_before_4">
            <label class="form-check-label" for="radio_before_4">
                (4) 有點煩躁
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_before" type="radio" name="answer_sheet[before]" id="radio_before_5">
            <label class="form-check-label" for="radio_before_5">
                (5) 很煩躁
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_before" type="radio" name="answer_sheet[before]" id="radio_before_6">
            <label class="form-check-label" for="radio_before_6">
                (6) 非常煩躁
            </label>
        </div>

        <div class="d-grid pt-3 px-2">
            <button class="btn btn-primary" onclick="form_step(2); return false;">繼續</button>
        </div>
    </div>

    <!-- 第2階段 -->
    <div id="step_2" class="d-grid pt-3 px-2 d-none form_group">
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
                <button class="btn btn-primary mx-1" style="float:left; width:45%" onclick="breathingVideoMale(); return false;">男聲版</button>
            </div>
            <div class="col-md-6">
                <button class="btn btn-primary mx-1" style="width:45%" onclick="breathingVideoFemale(); return false;">女聲版</button>
            </div>
        </div>

        <div class="d-grid pt-3">
            <iframe id="breathing_video_frame" width="100%" height="315" src="https://www.youtube.com/embed/wbcJ60UvayY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>

        <div class="d-grid pt-3 px-2">
            <button class="btn btn-primary" onclick="form_step(3); return false;">繼續</button>
        </div>
    </div>

    <!-- 第3階段 -->
    <div id="step_3" class="d-grid pt-3 px-2 d-none form_group">
        <div class="d-grid pt-5 pb-3 px-2">
            <h2>第四招：正念數息</h2>
        </div>

        <h3>厲害喔，您已完正念數息的訓練了</h3>

        <p>請您再完成下方煩燥程度的評估～</p>

        <div id="alert_3" class="d-none alert alert-danger">請確實選取評量分數～</div>

        <div class="form-check">
            <input class="form-check-input radio_group_after" type="radio" name="answer_sheet[after]" id="radio_after_0">
            <label class="form-check-label" for="radio_after_0">
                (0) 完全沒有
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_after" type="radio" name="answer_sheet[after]" id="radio_after_1">
            <label class="form-check-label" for="radio_after_1">
                (1) 非常微弱
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_after" type="radio" name="answer_sheet[after]" id="radio_after_2">
            <label class="form-check-label" for="radio_after_2">
                (2) 輕微
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_after" type="radio" name="answer_sheet[after]" id="radio_after_3">
            <label class="form-check-label" for="radio_after_3">
                (3) 中等
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_after" type="radio" name="answer_sheet[after]" id="radio_after_4">
            <label class="form-check-label" for="radio_after_4">
                (4) 有點煩躁
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_after" type="radio" name="answer_sheet[after]" id="radio_after_5">
            <label class="form-check-label" for="radio_after_5">
                (5) 很煩躁
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input radio_group_after" type="radio" name="answer_sheet[after]" id="radio_after_6">
            <label class="form-check-label" for="radio_after_6">
                (6) 非常煩躁
            </label>
        </div>

        <div class="d-grid pt-3 px-2">
            <button class="btn btn-primary" onclick="form_step(4); return false;">繼續</button>
        </div>
    </div>

    <!-- 第4階段 -->
    <div id="step_4" class="d-grid pt-3 px-2 d-none form_group">
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
            <button class="btn border border-primary button_group_evaluate_1" onclick="form_step(5, false, 0); return false;">當有立即小獎賞和較慢的大獎賞時，可以選擇等待，讓自己得到大獎賞。</button>
        </div>
        <div class="d-grid pt-3 px-2">
            <button class="btn border border-primary button_group_evaluate_1" onclick="form_step(5, false, 1); return false;">研究給小朋友1顆棉花糖，等10分鐘不吃的話，可以獲得第2顆棉花糖，發現能夠選擇等待10分鐘的小朋友，其之後的成績、工作成就顯著比立即吃棉花糖的小朋友還要好。</button>
        </div>
        <div class="d-grid pt-3 px-2">
            <button class="btn border border-primary button_group_evaluate_1" onclick="form_step(5, false, 2); return false;">學會自控力也比較不會沈迷，更懂得拒絕誘惑。</button>
        </div>
        <div class="d-grid pt-3 px-2">
            <button class="btn border border-primary button_group_evaluate_1" onclick="form_step(5, true, 3); return false;">反正遲早都要做作業，先玩先爽再說。</button>
        </div>
    </div>

    <!-- 第5階段 -->
    <div id="step_5" class="d-grid pt-3 px-2 d-none form_group">
        <div class="d-grid pt-5 pb-3 px-2">
            <h2>認知策略實踐評量(共3題)</h2>
        </div>

        <h3 id="response_5_correct" class="pt-3 text-success">答對，您對自控力了解很多，請再接再厲!</h3>

        <button class="btn btn-primary" onclick="form_step(6); return false;">繼續</button>
    </div>

    <!-- 第6階段 -->
    <div id="step_6" class="d-grid pt-3 px-2 d-none form_group">
        <div class="d-grid pt-5 pb-3 px-2">
            <h2>認知策略實踐評量(共3題)</h2>
        </div>
        <h3 class="pt-3">為什麼自控力(延遲享樂)很重要?</h3>
        <div id="alert_evaluate_2_incorrect" class="d-none alert alert-danger">答錯，請您再想想看。</div>

        <div class="d-grid pt-3 px-2">
            <button class="btn border border-primary button_group_evaluate_2" onclick="form_step(7, true, 0); return false;">研究證實，自控力高未來的成就也高!</button>
        </div>
        <div class="d-grid pt-3 px-2">
            <button class="btn border border-primary button_group_evaluate_2" onclick="form_step(7, false, 1); return false;">研究證實，延遲享樂沒有好處。</button>
        </div>
        <div class="d-grid pt-3 px-2">
            <button class="btn border border-primary button_group_evaluate_2" onclick="form_step(7, false, 2); return false;">研究證實，自控力高未來的成就較低!</button>
        </div>
        <div class="d-grid pt-3 px-2">
            <button class="btn border border-primary button_group_evaluate_2" onclick="form_step(7, false, 3); return false;">研究證實，自控力跟未來成就無關。</button>
        </div>
    </div>

    <!-- 第7階段 -->
    <div id="step_7" class="d-grid pt-3 px-2 d-none form_group">
        <div class="d-grid pt-5 pb-3 px-2">
            <h2>認知策略實踐評量(共3題)</h2>
        </div>
        <h3 id="response_7_correct" class="pt-3 text-success">答對，您對自控力了解不錯，請再接再厲!</h3>

        <button class="btn btn-primary" onclick="form_step(8); return false;">繼續</button>
    </div>

    <!-- 第8階段 -->
    <div id="step_8" class="d-grid pt-3 px-2 d-none form_group">
        <div class="d-grid pt-5 pb-3 px-2">
            <h2>認知策略實踐評量(共3題)</h2>
        </div>
        <h3 id="alert_evaluate_3_incorrect" class="pt-3 text-danger d-none">答錯，請您再想想看。</h3>

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
            <button class="btn btn-primary" onclick="form_step(9); return false;">繼續</button>
        </div>
    </div>

    <!-- 第9階段 -->
    <div id="step_9" class="d-grid pt-3 px-2 d-none form_group">
        <div class="d-grid pt-5 pb-3 px-2">
            <h2>認知策略實踐評量(共3題)</h2>
        </div>

        <h3 id="response_9_correct" class="pt-3 text-success">答對，您的自控力表現出色!邁向自控力達人!</h3>

        <input type="submit" class="btn btn-primary" value="繼續">
    </div>
</form>
<script type="text/javascript">
    function form_step(step, evaluate_answer = null, button_index = null) {
        let last_step = $("#step_"+(step-1));
        let next_step = $("#step_"+step);
        if (step == 2) {
            for (i=0; i<7; i++) {
                if ($(".radio_group_before")[i].checked) {
                    $(".form_group").addClass("d-none");
                    last_step.addClass("d-none");
                    next_step.removeClass("d-none");
                    return false;
                }
            }
            $("#alert_1").removeClass("d-none");
            return false
        } else if (step == 4) {
            for (i=0; i<7; i++) {
                if ($(".radio_group_after")[i].checked) {
                    $(".form_group").addClass("d-none");
                    last_step.addClass("d-none");
                    next_step.removeClass("d-none");
                    return false;
                }
            }
            $("#alert_3").removeClass("d-none");
            return false
        } else if (step == 5) {
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
        } else if (step == 7) {
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
        } else if (step == 9) {
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

    function breathingVideoMale() {
        $("#breathing_video_type").val("male");
        $("#breathing_video_frame").attr("src", "https://www.youtube.com/embed/wbcJ60UvayY");
    }
    
    function breathingVideoFemale() {
        $("#breathing_video_type").val("female");
        $("#breathing_video_frame").attr("src", "https://www.youtube.com/embed/yFipE1QIuOI");
    }

</script>