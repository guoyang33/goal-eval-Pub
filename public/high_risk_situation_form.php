<?php
/*
 * 高風險誘惑情境
 */
require_once 'connect_db.php';
require_once 'get_user.php';
require_once 'html_head.php';

define('EAGER_LEVEL_CHINESE', [
    '完全沒有',
    '非常微弱',
    '輕微',
    '中等',
    '有點強，但我能輕鬆自控',
    '很強，我難以自控',
    '超強，我控制不住我的手手了'
]);

// 查詢填寫記錄
$form_answer = get_form_answer($user['id'], 'high_risk_situation');
if ($form_answer === false ) {      // 沒有記錄
    $answer_sheet = array(
        1 => array('situation' => array('when' => '', 'where' => '', 'who' => '', 'what' => ''), 'think' => '', 'feel' => '', 'level' => ''),
        2 => array('situation' => array('when' => '', 'where' => '', 'who' => '', 'what' => ''), 'think' => '', 'feel' => '', 'level' => ''),
        3 => array('situation' => array('when' => '', 'where' => '', 'who' => '', 'what' => ''), 'think' => '', 'feel' => '', 'level' => '')
    );
} else {
    $fa_id = $form_answer['id'];
    $answer_sheet = json_decode($form_answer['answer_sheet'], true);
}
?>

<h2>自控力很重要，影響您未來的成就!</h2>
<ul>
<li>大華是大學生，很喜歡玩手機遊戲，即使要考試了，仍玩到三更半夜，大華知道應該先念書，才能去玩手遊，但是大華就是無法克制玩遊戲的欲望，導致精神不佳，成績變差，有什麼方法能幫助大華呢?</li>
<li>答案是學習自控力武功秘訣!</li>
<li>為什麼自控力學習秘訣很重要嗎? 很重要!因為研究證實，自控力高未來的成就高!</li>
<li>自控力是指當您有立即的小獎賞和要等待比較久的大獎賞可選擇時，您可以克制慾望，</li>
選擇等待，好讓自己得到比較久的大獎賞。
<li>證據是，史丹福大學心理學家 Walter Mischel 進行了一系列著名的「棉花糖」實驗，(給小朋友一顆棉花糖延宕不吃，等15分鐘之後，不吃的小朋友可以得到第2顆棉花糖)來考驗孩子的延遲享樂的能力。14年追蹤研究發現，選擇等待並得到兩顆棉花糖的小朋友，之後的學業成績、工作成就顯著比選擇立即獎賞的小朋友還要好，也比較不會沈迷，更懂得拒絕誘惑。延遲享樂的能力，是決定孩子今後成就的一個關鍵因素。而且後續研究還發現，自控力是可以被教導和學習的。原文網址：
<a href="https://kknews.cc/baby/pngxvre.html" target="_blank">https://kknews.cc/baby/pngxvre.html</a>
</li>
<li>在開始學習自控力之前請您先填寫：</li>
1. 您的高風險誘惑情境
</ul>

<h2>您的高風險誘惑情境</h2>
<p>請問您常常想玩遊戲是出現在什麼時候、在哪裡、和誰再一起？當時您想到什麼?（要填完三個情境）</p>
<p>範例：<strong>大華下課回到宿舍和室友在一起，一想到後天有報告要交，感到一陣心煩，不想做報告，此時很想打一場傳說．．．</strong></p>
<?php
echo '<form action="high_risk_situation_form_target.php?u_id='.$user['id'].'" method="POST">';
echo '<div class="d-grid pt-5 px-2">
        <h3>範例情境(大華)</h3>
        <span class="pt-2">1.</span>
        <div class="input-group">
            <span class="ms-4 pt-2">是什麼時候？</span>
            <input type="text" class="form-control ms-1" value="星期一下午六點" disabled>
        </div>
        <div class="input-group">
            <span class="ms-4 pt-2">在哪裡？&emsp;&emsp;</span>
            <input type="text" class="form-control ms-1" value="在宿舍" disabled>
        </div>
        <div class="input-group">
            <span class="ms-4 pt-2">跟誰在一起？</span>
            <input type="text" class="form-control ms-1" value="跟室友" disabled>
        </div>
        <div class="input-group">
            <span class="ms-4 pt-2">做什麼事情？</span>
            <input type="text" class="form-control ms-1" value="想到報告期限" disabled>
        </div>
        <span class="pt-2">2.在玩的時候有想到什麼嗎？</span>
        <input type="text" class="form-control input-lg" value="寫報告好累，好想打一場傳說放鬆一下。" disabled>
        <span class="pt-2">3.感受</span>
        <input type="text" class="form-control input-lg" value="煩躁不安" disabled>
        <span class="pt-2">4.玩遊戲的渴求程度</span>
        <div class="float-start ms-4">
            <input id="example" class="form-check-input p-3 mx-3" type="radio" checked disabled>
            <label for="example" class="mt-2 pe-5">超強，我控制不住我的手手了</label>
        </div>
    </div>';

for ($i=1;$i<=3;$i++) {
    echo '<div class="d-grid pt-5 px-2">
        <h3>情境 '.$i.'</h3>
        <span class="pt-2">1.</span>
        <div class="input-group">
            <span class="ms-4 pt-2">是什麼時候？</span>
            <input type="text" class="form-control ms-1" name="answer_sheet['.$i.'][situation][when]" value="'.$answer_sheet[$i]['situation']['when'].'" required>
        </div>
        <div class="input-group">
            <span class="ms-4 pt-2">在哪裡？&emsp;&emsp;</span>
            <input type="text" class="form-control ms-1" name="answer_sheet['.$i.'][situation][where]" value="'.$answer_sheet[$i]['situation']['where'].'" required>
        </div>
        <div class="input-group">
            <span class="ms-4 pt-2">跟誰在一起？</span>
            <input type="text" class="form-control ms-1" name="answer_sheet['.$i.'][situation][who]" value="'.$answer_sheet[$i]['situation']['who'].'" required>
        </div>
        <div class="input-group">
            <span class="ms-4 pt-2">做什麼事情？</span>
            <input type="text" class="form-control ms-1" name="answer_sheet['.$i.'][situation][what]" value="'.$answer_sheet[$i]['situation']['what'].'" required>
        </div>
        <span class="pt-2">2.在玩的時候有想到什麼嗎？</span>
        <input type="text" class="form-control input-lg" name="answer_sheet['.$i.'][think]" value="'.$answer_sheet[$i]['think'].'" required>
        <span class="pt-2">3.感受</span>
        <input type="text" class="form-control input-lg" name="answer_sheet['.$i.'][feel]" value="'.$answer_sheet[$i]['feel'].'" required>
        <span class="pt-2">4.玩遊戲的渴求程度</span>';

    for ($j=1;$j<=7;$j++) {
        $situation_level_checked = '';
        if ($j == $answer_sheet[$i]['level']) {
            $situation_level_checked = ' checked';
        }
        echo '<div class="float-start ms-4">
                <input id="s'.$i.'_4_'.$j.'" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet['.$i.'][level]" value="'.$j.'"'.$situation_level_checked.' required>
                <label for="s'.$i.'_4_'.$j.'" class="mt-2 pe-5">'.EAGER_LEVEL_CHINESE[$j - 1].'</label>
            </div>';
    }
    echo '</div>';
}
?>
<div class="d-grid pt-5 px-2">
<input type="submit" class="btn btn-primary">
</div>
</form>
<!-- 返回按鈕 -->
<!-- <div class="d-grid pt-5 px-2"> -->
<!-- <button class="btn btn-secondary" onclick=location.href="redirect"> -->