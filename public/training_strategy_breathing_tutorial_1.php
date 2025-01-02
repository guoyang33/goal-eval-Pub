<?php
/**
 * 策略訓練 教學
 * Week 1
 * 第四招：正念數息(breathing)
 */
require_once 'html_head.php';
require_once 'get_user.php';
?>
<form action="training_strategy_form_target.php" method="POST">
    <input type="hidden" name="u_id" value="<?php echo $user['id']; ?>">
    <input type="hidden" name="form_name" value="training_strategy_breathing_tutorial_1">
    <input type="hidden" id="breathing_video_type" name="answer_sheet[breathing_video_type]" value="male">
    <!-- <input type="hidden" id="evaluate" name="answer_sheet[evaluate]"> -->

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
            <button class="btn btn-primary" onclick="form_step(4); return false;">繼續</button>
        </div>
    </div>

    <!-- 第4階段 -->
    <div id="step_4" class="d-grid pt-3 px-2 d-none form_group">
        <div class="d-grid pt-5 pb-3 px-2">
            <h2>認知策略評量(共1題)</h2>
        </div>
        <h3 class="pt-3">為了避免沉迷於遊戲，您還可以怎麼做?</h3>

        <div class="d-grid pt-3 px-2">
            <button id="step_4_option_0" class="btn border border-primary" onclick="$('#response_5-1').removeClass('d-none'); $('#response_5-2').addClass('d-none'); $('#step_4_next_btn').addClass('d-none'); $('#step_4_option_0').addClass('btn-danger'); $('#step_4_option_1').removeClass('btn-info'); return false;">先打一場遊戲，其他的事情拋在腦後。</button>
        </div>
        <div class="d-grid pt-3 px-2">
            <button id="step_4_option_1" class="btn border border-primary" onclick="$('#response_5-2').removeClass('d-none'); $('#response_5-1').addClass('d-none'); $('#step_4_next_btn').removeClass('d-none'); $('#step_4_option_0').removeClass('btn-danger'); $('#step_4_option_1').addClass('btn-info'); return false;">馬上練習腹式呼吸，想玩的念頭會漸漸消失！</button>
        </div>
        <div class="d-grid pt-3 px-2">
            <h3 id="response_5-1" class="pt-3 text-success d-none">你看來是百年難得一見的練武奇才！請給自己一個機會試看看我們的策略。（請重新選擇）</h3>
            <h3 id="response_5-2" class="pt-3 text-success d-none">您好有行動力!</h3>
            <button id="step_4_next_btn" class="btn btn-primary d-none" onclick="form_step(5); return false;">繼續</button>
        </div>
    </div>

    <!-- 第5階段 -->
    <div id="step_5" class="d-grid pt-3 px-2 d-none form_group">
        <h2>您已完成訓練！</h2>
        <input type="submit" class="btn btn-primary" value="完成">
    </div>
</form>
<script type="text/javascript">
    function form_step(step, evaluate_answer = null, button_index = null) {
        let last_step = $("#step_"+(step-1));
        let next_step = $("#step_"+step);
        if (step == 2) {
            for (i=0; i<7; i++) {
                if ($(".radio_group_before")[i].checked) {
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
                    last_step.addClass("d-none");
                    next_step.removeClass("d-none");
                    return false;
                }
            }
            $("#alert_1").removeClass("d-none");
            return false
        } else if (step == 5) {
            /*
            if (evaluate_answer) {
                $("#evaluate").val(1);
                if (Math.floor(Math.random()*2)) {      // 產生0或1
                    $("#response_5-1").removeClass("d-none");
                } else {
                    $("#response_5-2").removeClass("d-none");
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

    function breathingVideoMale() {
        $("#breathing_video_type").val("male");
        $("#breathing_video_frame").attr("src", "https://www.youtube.com/embed/wbcJ60UvayY");
    }
    
    function breathingVideoFemale() {
        $("#breathing_video_type").val("female");
        $("#breathing_video_frame").attr("src", "https://www.youtube.com/embed/yFipE1QIuOI");
    }

</script>