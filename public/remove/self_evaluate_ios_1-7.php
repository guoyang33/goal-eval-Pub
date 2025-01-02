<?php
/**
 * 自我評估單 iOS版
 * Week 1-7
 */
require_once 'connect_db.php';
require_once 'get_user.php';
require_once 'html_head.php';

$form_name = FORM_NAME['self_evaluate']['ios'][$user['exp_type']][$week];
?>
<div class="d-grid pt-5 pb-3 px-2">
    <h2>自我評估單(第<?php echo $week; ?>週)</h2>
</div>
<form action="self_evaluate_ios_target.php" method="POST">
    <input type="hidden" name="u_id" value="<?php echo $user['id']; ?>">
    <input type="hidden" name="form_name" value="<?php echo $form_name; ?>">

    <div class="d-grid pt-2 px-2">
        <h3>
            第一部分
            上週使用時間和使用行為評量
        </h3>
    </div>
    <div class="d-grid pt-2 px-2">
        <h4>一、上一週自律訓練過程健康上網表單回饋的功能是否讓我...</h4>
    </div>
    <div class="d-grid pt-3 px-2">
        <table class="table">
            <tr>
                <td class="h5" colspan=7>1. 是否每天知道手機各類型 App 使用時間，知道的是第幾天? (複選)</td>
            </tr>
            <tr>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][1][]" value=1 id="q1_1_1">
                        <label class="form-check-label" for="q1_1_1">
                            第1天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][1][]" value=2 id="q1_1_2">
                        <label class="form-check-label" for="q1_1_2">
                            第2天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][1][]" value=3 id="q1_1_3">
                        <label class="form-check-label" for="q1_1_3">
                            第3天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][1][]" value=4 id="q1_1_4">
                        <label class="form-check-label" for="q1_1_4">
                            第4天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][1][]" value=5 id="q1_1_5">
                        <label class="form-check-label" for="q1_1_5">
                            第5天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][1][]" value=6 id="q1_1_6">
                        <label class="form-check-label" for="q1_1_6">
                            第6天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][1][]" value=7 id="q1_1_7">
                        <label class="form-check-label" for="q1_1_7">
                            第7天
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="h5" colspan=7>2. 是否每天知道使用時間排名，知道的是第幾天? (複選)</td>
            </tr>
            <tr>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][2][]" value=1 id="q1_2_1">
                        <label class="form-check-label" for="q1_2_1">
                            第1天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][2][]" value=2 id="q1_2_2">
                        <label class="form-check-label" for="q1_2_2">
                            第2天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][2][]" value=3 id="q1_2_3">
                        <label class="form-check-label" for="q1_2_3">
                            第3天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][2][]" value=4 id="q1_2_4">
                        <label class="form-check-label" for="q1_2_4">
                            第4天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][2][]" value=5 id="q1_2_5">
                        <label class="form-check-label" for="q1_2_5">
                            第5天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][2][]" value=6 id="q1_2_6">
                        <label class="form-check-label" for="q1_2_6">
                            第6天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][2][]" value=7 id="q1_2_7">
                        <label class="form-check-label" for="q1_2_7">
                            第7天
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="h5" colspan=7>3. 在網頁上為自己設定使用時間減量目標，第幾天? (複選)</td>
            </tr>
            <tr>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][3][]" value=1 id="q1_3_1">
                        <label class="form-check-label" for="q1_3_1">
                            第1天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][3][]" value=2 id="q1_3_2">
                        <label class="form-check-label" for="q1_3_2">
                            第2天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][3][]" value=3 id="q1_3_3">
                        <label class="form-check-label" for="q1_3_3">
                            第3天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][3][]" value=4 id="q1_3_4">
                        <label class="form-check-label" for="q1_3_4">
                            第4天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][3][]" value=5 id="q1_3_5">
                        <label class="form-check-label" for="q1_3_5">
                            第5天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][3][]" value=6 id="q1_3_6">
                        <label class="form-check-label" for="q1_3_6">
                            第6天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][3][]" value=7 id="q1_3_7">
                        <label class="form-check-label" for="q1_3_7">
                            第7天
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="h5" colspan=7>4. 每天達成目標後，網頁有給予我獎勵點數，第幾天? (複選)</td>
            </tr>
            <tr>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][4][]" value=1 id="q1_4_1">
                        <label class="form-check-label" for="q1_4_1">
                            第1天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][4][]" value=2 id="q1_4_2">
                        <label class="form-check-label" for="q1_4_2">
                            第2天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][4][]" value=3 id="q1_4_3">
                        <label class="form-check-label" for="q1_4_3">
                            第3天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][4][]" value=4 id="q1_4_4">
                        <label class="form-check-label" for="q1_4_4">
                            第4天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][4][]" value=5 id="q1_4_5">
                        <label class="form-check-label" for="q1_4_5">
                            第5天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][4][]" value=6 id="q1_4_6">
                        <label class="form-check-label" for="q1_4_6">
                            第6天
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answer_sheet[question][1][4][]" value=7 id="q1_4_7">
                        <label class="form-check-label" for="q1_4_7">
                            第7天
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="h5" colspan=7>5. 整週都達成目標後，收到網頁給予我的獎勵卡?</td>
            </tr>
            <tr>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][1][5]" value=1 id="q1_5_1">
                        <label class="form-check-label" for="q1_5_1">
                            是
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][1][5]" value=0 id="q1_5_2">
                        <label class="form-check-label" for="q1_5_2">
                            否
                        </label>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="d-grid pt-2 px-2">
        <h4>二、健康上網表單的回鐀是否有幫助於我...</h4>
    </div>
    <div class="d-grid pt-3 px-2">
        <table class="table">
            <tr>
                <td class="h5" colspan=5>1. 了解健康上網的重要性</td>
            </tr>
            <tr>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][2][1]" id="q2_1_1" required>
                        <label class="form-check-label" for="q1_1">
                            非常不同意(1)
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][2][1]" id="q2_1_2" required>
                        <label class="form-check-label" for="q1_2">
                            不同意(2)
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][2][1]" id="q2_1_3" required>
                        <label class="form-check-label" for="q1_3">
                            普通(3)
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][2][1]" id="q2_1_4" required>
                        <label class="form-check-label" for="q1_4">
                            同意(4)
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][2][1]" id="q2_1_5" required>
                        <label class="form-check-label" for="q1_5">
                            非常同意(5)
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="h5" colspan=5>2. 提升我想健康上網的動機</td>
            </tr>
            <tr>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][2][2]" id="q2_2_1" required>
                        <label class="form-check-label" for="q2_1">
                            非常不同意(1)
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][2][2]" id="q2_2_2" required>
                        <label class="form-check-label" for="q2_2">
                            不同意(2)
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][2][2]" id="q2_2_3" required>
                        <label class="form-check-label" for="q2_3">
                            普通(3)
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][2][2]" id="q2_2_4" required>
                        <label class="form-check-label" for="q2_4">
                            同意(4)
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][2][2]" id="q2_2_5" required>
                        <label class="form-check-label" for="q2_5">
                            非常同意(5)
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="h5" colspan=5>3. 可實際幫助我做健康上網的時間管理</td>
            </tr>
            <tr>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][2][3]" id="q2_3_1" required>
                        <label class="form-check-label" for="q3_1">
                            非常不同意(1)
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][2][3]" id="q2_3_2" required>
                        <label class="form-check-label" for="q3_2">
                            不同意(2)
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][2][3]" id="q2_3_3" required>
                        <label class="form-check-label" for="q3_3">
                            普通(3)
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][2][3]" id="q2_3_4" required>
                        <label class="form-check-label" for="q3_4">
                            同意(4)
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][2][3]" id="q2_3_5" required>
                        <label class="form-check-label" for="q3_5">
                            非常同意(5)
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="h5" colspan=5>4. 有助於我 3C 自控力的提升</td>
            </tr>
            <tr>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][2][4]" id="q2_4_1" required>
                        <label class="form-check-label" for="q4_1">
                            非常不同意(1)
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][2][4]" id="q2_4_2" required>
                        <label class="form-check-label" for="q4_2">
                            不同意(2)
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][2][4]" id="q2_4_3" required>
                        <label class="form-check-label" for="q4_3">
                            普通(3)
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][2][4]" id="q2_4_4" required>
                        <label class="form-check-label" for="q4_4">
                            同意(4)
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer_sheet[question][2][4]" id="q2_4_5" required>
                        <label class="form-check-label" for="q4_5">
                            非常同意(5)
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="h5" colspan=5>5. 對此研究的建議</td>
            </tr>
            <tr>
                <td colspan=5>
                    <input class="form-control" type="search" name="answer_sheet[advice]" placeholder="您的建議...">
                </td>
            </tr>
        </table>
    </div>

    <div class="d-grid pt-2 px-2">
        <h3>
            第二部分
            上週手機使用時間評量（請從上週一開始填寫至週日）
        </h3>
    </div>
    <div class="d-grid pt-3 px-2">
        <h4 class="pt-3">
            我這一週，每日的
            <span class="fw-bold">螢幕使用時間</span>
            （沒使用請填 0）：
        </h4>

        <div id="alert_phone_total" class="d-none alert alert-danger">請確實輸入您的使用時間～</div>

        <table class="table table-bordered">
            <tr>
                <th>星期一</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][0][total][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][0][total][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>星期二</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][1][total][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][1][total][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>星期三</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][2][total][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][2][total][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>星期四</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][3][total][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][3][total][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>星期五</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][4][total][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][4][total][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th class="text-danger">星期六</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][5][total][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][5][total][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th class="text-danger">星期日</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][6][total][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][6][total][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
        </table>
    </div>

    <div class="d-grid pt-3 px-2">
        <h4 class="pt-3">
            我這一週，每日用手機玩
            <span class="fw-bold">遊戲</span>
            的使用時間（沒使用請填 0）：
        </h4>

        <div id="alert_phone_game" class="d-none alert alert-danger">請確實輸入您的使用時間～</div>

        <table class="table table-bordered">
            <tr>
                <th>星期一</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][0][game][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][0][game][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>星期二</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][1][game][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][1][game][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>星期三</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][2][game][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][2][game][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>星期四</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][3][game][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][3][game][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>星期五</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][4][game][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][4][game][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th class="text-danger">星期六</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][5][game][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][5][game][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th class="text-danger">星期日</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][6][game][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][6][game][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
        </table>
    </div>

    <div class="d-grid pt-3 px-2">
        <h4 class="pt-3">
            我這一週，每日用手機使用
            <span class="fw-bold">社交</span>
            的使用時間（沒使用請填 0）：
        </h4>

        <div id="alert_phone_social" class="d-none alert alert-danger">請確實輸入您的使用時間～</div>

        <table class="table table-bordered">
            <tr>
                <th>星期一</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][0][social][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][0][social][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>星期二</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][1][social][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][1][social][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>星期三</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][2][social][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][2][social][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>星期四</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][3][social][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][3][social][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>星期五</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][4][social][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][4][social][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th class="text-danger">星期六</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][5][social][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][5][social][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th class="text-danger">星期日</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][6][social][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][6][social][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
        </table>
    </div>

    <div class="d-grid pt-3 px-2">
        <h4 class="pt-3">
            我這一週，每日用手機使用
            <span class="fw-bold">娛樂</span>
            的使用時間（沒使用請填 0）：
        </h4>

        <div id="alert_phone_entertainment" class="d-none alert alert-danger">請確實輸入您的使用時間～</div>

        <table class="table table-bordered">
            <tr>
                <th>星期一</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][0][entertainment][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][0][entertainment][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>星期二</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][1][entertainment][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][1][entertainment][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>星期三</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][2][entertainment][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][2][entertainment][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>星期四</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][3][entertainment][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][3][entertainment][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>星期五</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][4][entertainment][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][4][entertainment][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th class="text-danger">星期六</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][5][entertainment][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][5][entertainment][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th class="text-danger">星期日</th>
                <td>
                    <input type="number" class="" name="answer_sheet[usage_time][6][entertainment][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[usage_time][6][entertainment][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
        </table>
    </div>

    <div class="d-grid pt-3 px-2">
        <h4 class="pt-3">
            我這一週，平均每日用電腦玩
            <span class="fw-bold">遊戲</span>
            的使用時間（沒使用請填 0）：
        </h4>

        <div id="alert_computer_game" class="d-none alert alert-danger">請確實輸入您的使用時間～</div>

        <table class="table table-bordered">
            <tr>
                <th></th>
                <th>用在非課業上</th>
            </tr>
            <tr>
                <th>非假日、週一至週五</th>
                <td>
                    平均每日
                    <input type="number" class="" name="answer_sheet[computer][game][weekday][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[computer][game][weekday][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>假日、週六、週日</th>
                <td>
                    平均每日
                    <input type="number" class="" name="answer_sheet[computer][game][weekend][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[computer][game][weekend][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
        </table>
    </div>

    <div class="d-grid pt-3 px-2">
        <h4 class="pt-3">
            我這一週，平均每日用電腦使用
            <span class="fw-bold">社群網站</span>
            的使用時間（沒使用請填 0）：
        </h4>

        <div id="alert_computer_social" class="d-none alert alert-danger">請確實輸入您的使用時間～</div>

        <table class="table table-bordered">
            <tr>
                <th></th>
                <th>用在非課業上</th>
            </tr>
            <tr>
                <th>非假日、週一至週五</th>
                <td>
                    平均每日
                    <input type="number" class="" name="answer_sheet[computer][social][weekday][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[computer][social][weekday][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>假日、週六、週日</th>
                <td>
                    平均每日
                    <input type="number" class="" name="answer_sheet[computer][social][weekend][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[computer][social][weekend][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
        </table>
    </div>

    <div class="d-grid pt-3 px-2">
        <h4 class="pt-3">
            我這一週，平均每日用電腦
            <span class="fw-bold">上網</span>
            的使用時間（沒使用請填 0）：
        </h4>

        <div id="alert_computer_internet" class="d-none alert alert-danger">請確實輸入您的使用時間～</div>

        <table class="table table-bordered">
            <tr>
                <th></th>
                <th>用在非課業上</th>
            </tr>
            <tr>
                <th>非假日、週一至週五</th>
                <td>
                    平均每日
                    <input type="number" class="" name="answer_sheet[computer][internet][weekday][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[computer][internet][weekday][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>假日、週六、週日</th>
                <td>
                    平均每日
                    <input type="number" class="" name="answer_sheet[computer][internet][weekend][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[computer][internet][weekend][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
        </table>
    </div>

    <div class="d-grid pt-3 px-2">
        <h4 class="pt-3">
            我這一週，平均每日用電腦使用
            <span class="fw-bold">網路影音</span>
            的使用時間（沒使用請填 0）：
        </h4>

        <div id="alert_computer_video" class="d-none alert alert-danger">請確實輸入您的使用時間～</div>

        <table class="table table-bordered">
            <tr>
                <th></th>
                <th>用在非課業上</th>
            </tr>
            <tr>
                <th>非假日、週一至週五</th>
                <td>
                    平均每日
                    <input type="number" class="" name="answer_sheet[computer][video][weekday][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[computer][video][weekday][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>假日、週六、週日</th>
                <td>
                    平均每日
                    <input type="number" class="" name="answer_sheet[computer][video][weekend][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[computer][video][weekend][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
        </table>
    </div>

    <div class="d-grid pt-3 px-2">
        <h4 class="pt-3">
            我這一週，平均每日用電腦使用
            <span class="fw-bold">通訊軟體</span>
            的使用時間（沒使用請填 0）：
        </h4>

        <div id="alert_computer_communication" class="d-none alert alert-danger">請確實輸入您的使用時間～</div>

        <table class="table table-bordered">
            <tr>
                <th></th>
                <th>用在非課業上</th>
            </tr>
            <tr>
                <th>非假日、週一至週五</th>
                <td>
                    平均每日
                    <input type="number" class="" name="answer_sheet[computer][communication][weekday][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[computer][communication][weekday][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
            <tr>
                <th>假日、週六、週日</th>
                <td>
                    平均每日
                    <input type="number" class="" name="answer_sheet[computer][communication][weekend][hour]" max="23" min="0" required>
                    小時
                    <input type="number" class="" name="answer_sheet[computer][communication][weekend][minute]" max="60" min="0" value="0" required>
                    分鐘
                </td>
            </tr>
        </table>
    </div>

    <div class="d-grid pt-3 px-2">
        <input type="submit" class="btn btn-primary">
    </div>
</form>
<script type="text/javascript">
    
</script>