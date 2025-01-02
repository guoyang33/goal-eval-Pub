<?php
/**
 * 策略訓練 教學
 * Week 1
 * 第三招：分散注意力(misdirection)
 */
require_once 'html_head.php';
require_once 'get_user.php';
?>
<form action="training_strategy_form_target.php" method="POST">
    <input type="hidden" name="u_id" value="<?php echo $user['id']; ?>">
    <input type="hidden" name="form_name" value="training_strategy_misdirection_tutorial_1">
    <!-- <input type="hidden" id="evaluate" name="answer_sheet[evaluate]"> -->

    <!-- 第1階段 -->
    <div id="step_1" class="d-grid pt-3 px-2 form_group">
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

        <div id="alert_1" class="d-none alert alert-danger">請確實輸入以下項目～</div>
        
        <div class="d-grid">
            <p>我們暫時放下遊戲，來做點不一樣的事吧！</p>
            <input id="input_action" type="text" class="form-control input-lg mt-1" name="answer_sheet[action]" placeholder="您會去做什麼呢?">
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
        <h3 class="pt-3">如果同學常常邀您玩手機遊戲，您會怎麼做?</h3>

        <div class="d-grid pt-3 px-2">
            <button id="step_2_option_0" class="btn border border-primary" onclick="$('#response_3-1').removeClass('d-none'); $('#response_3-2').addClass('d-none'); $('#step_2_next_btn').addClass('d-none'); $('#step_2_option_0').addClass('btn-danger'); $('#step_2_option_1').removeClass('btn-info'); return false;">加入同學一起玩，不然會被排擠呀!</button>
        </div>
        <div class="d-grid pt-3 px-2">
            <button id="step_2_option_1" class="btn border border-primary" onclick="$('#response_3-2').removeClass('d-none'); $('#response_3-1').addClass('d-none'); $('#step_2_next_btn').removeClass('d-none'); $('#step_2_option_0').removeClass('btn-danger'); $('#step_2_option_1').addClass('btn-info'); return false;">轉移注意力，邀同學做其他有興趣的事!</button>
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
            if ($("#input_action").val() == '') {
                $("#alert_1").removeClass("d-none");
                return false;
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