<?php
/**
 * 自我評估單 iOS版
 * Week 0
 */
require_once 'connect_db.php';
require_once 'get_user.php';
require_once 'html_head.php';

$form_name = FORM_NAME['self_evaluate']['ios'][$user['exp_type']][$week];
?>
<div class="d-grid pt-5 pb-3 px-2">
    <h2>自我評估單(第0週)</h2>
</div>
<div class="d-grid pt-2 px-2">
    <span>
        第一部分
        上週手機使用時間評量（請從上週一開始填寫至週日）
    </span>
</div>
<form action="self_evaluate_ios_target.php" method="POST">
    <input type="hidden" name="u_id" value="<?php echo $user['id']; ?>">
    <input type="hidden" name="form_name" value="<?php echo $form_name; ?>">
    <!-- <input type="hidden" id="evaluate" name="answer_sheet[evaluate]"> -->

    <div class="d-grid pt-3 px-2">
        <h3 class="pt-3">
            我這一週，每日的
            <span class="fw-bold">螢幕使用時間</span>
            （沒使用請填 0）：
        </h3>

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
        <h3 class="pt-3">
            我這一週，每日用手機玩
            <span class="fw-bold">遊戲</span>
            的使用時間（沒使用請填 0）：
        </h3>

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
        <h3 class="pt-3">
            我這一週，每日用手機使用
            <span class="fw-bold">社交</span>
            的使用時間（沒使用請填 0）：
        </h3>

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
        <h3 class="pt-3">
            我這一週，每日用手機使用
            <span class="fw-bold">娛樂</span>
            的使用時間（沒使用請填 0）：
        </h3>

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
        <h3 class="pt-3">
            我這一週，平均每日用電腦玩
            <span class="fw-bold">遊戲</span>
            的使用時間（沒使用請填 0）：
        </h3>

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
        <h3 class="pt-3">
            我這一週，平均每日用電腦使用
            <span class="fw-bold">社群網站</span>
            的使用時間（沒使用請填 0）：
        </h3>

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
        <h3 class="pt-3">
            我這一週，平均每日用電腦
            <span class="fw-bold">上網</span>
            的使用時間（沒使用請填 0）：
        </h3>

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
        <h3 class="pt-3">
            我這一週，平均每日用電腦使用
            <span class="fw-bold">網路影音</span>
            的使用時間（沒使用請填 0）：
        </h3>

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
        <h3 class="pt-3">
            我這一週，平均每日用電腦使用
            <span class="fw-bold">通訊軟體</span>
            的使用時間（沒使用請填 0）：
        </h3>

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