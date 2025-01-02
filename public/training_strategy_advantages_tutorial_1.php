<?php
/**
 * 策略訓練 教學
 * Week 1
 * 第二招：好壞處分析(advantages)
 */
require_once 'html_head.php';
require_once 'get_user.php';
?>
<form action="training_strategy_form_target.php" method="POST">
    <input type="hidden" name="u_id" value="<?php echo $user['id']; ?>">
    <input type="hidden" name="form_name" value="training_strategy_advantages_tutorial_1">
    <!-- <input type="hidden" id="evaluate" name="answer_sheet[evaluate]"> -->

    <!-- 第1階段 -->
    <div id="step_1" class="d-grid pt-3 px-2 form_group">
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

        <div id="alert_1" class="d-none alert alert-danger">請確實輸入以下項目～</div>
        
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
            <button class="btn btn-primary" onclick="form_step(2); return false;">繼續</button>
        </div>
    </div>

    <!-- 第2階段 -->
    <div id="step_2" class="d-grid pt-3 px-2 d-none form_group">
        <div class="d-grid pt-5 pb-3 px-2">
            <h2>認知策略評量(共1題)</h2>
        </div>
        <h3 class="pt-3">為了避免沉迷於遊戲，您還可以怎麼做?</h3>

        <div class="d-grid pt-3 px-2">
            <button id="step_2_option_0" class="btn border border-primary" onclick="$('#response_3-1').removeClass('d-none'); $('#response_3-2').addClass('d-none'); $('#step_2_next_btn').addClass('d-none'); $('#step_2_option_0').addClass('btn-danger'); $('#step_2_option_1').removeClass('btn-info'); return false;">現在就是要玩，以後的事情以後再說!</button>
        </div>
        <div class="d-grid pt-3 px-2">
            <button id="step_2_option_1" class="btn border border-primary" onclick="$('#response_3-2').removeClass('d-none'); $('#response_3-1').addClass('d-none'); $('#step_2_next_btn').removeClass('d-none'); $('#step_2_option_0').removeClass('btn-danger'); $('#step_2_option_1').addClass('btn-info'); return false;">預想或寫下玩遊戲的好壞處，想像結果!</button>
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
            for (i=0; i<10; i++) {
                if ($(".form-control")[i].value.length == 0) {
                    $("#alert_1").removeClass("d-none");
                    return false;
                }
            }
        } else if (step == 3) {
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