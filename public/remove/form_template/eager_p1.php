<!--<Version : 3.0>-->
<?php
/**
 * 自我評估單 android版
 * Week 0
 */

require_once 'connect_db.php';
require_once 'get_user.php';
require_once 'html_head.php';

$form_name = FORM_NAME['self_evaluate']['android'][$user['exp_type']][$week];
?>
        <script type="text/javascript">
            window.addEventListener("load", () => {
                const protected_values_pool = ProtectedValuesManager({usage_time_objects : {}, first_invalid : {flag : false}});
                const usage_time_objects = protected_values_pool.getValues("usage_time_objects");
                const first_invalid = protected_values_pool.getValues("first_invalid");

                initUsageTimeObjects(usage_time_objects);
                initInputInvalid(first_invalid);
                
                document.querySelector("#form").addEventListener("submit", () => {submitPreprocessing(usage_time_objects);});
            });

            function ProtectedValuesManager(protected_values) {
                const registered_protected_values = protected_values;
                return {
                    getValues: function(value_name) {
                        return registered_protected_values[value_name];
                    }
                };
            }

            function isInViewport(elment) {
                const rect = elment.getBoundingClientRect();
                return (
                    rect.top >= 0 &&
                    rect.left >= 0 &&
                    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                );
            }

            function initUsageTimeObjects(usage_time_objects) {
                const usage_time_array = document.querySelectorAll(`input[id|="${"usagetime"}"]`);

                for (let i = 0 ; i < usage_time_array.length ; i++) {
                    // 如果id是"usagetime-pc_game-workday-hour"就會切成[usagetime, pc_game, workday, hour]
                    const split_id = usage_time_array[i].getAttribute("id").split("-");
                    if (!Boolean(usage_time_objects[split_id[1]])) {
                        usage_time_objects[split_id[1]] = {};
                        usage_time_objects[split_id[1]][split_id[2]] = {};
                    } else {
                        if (!Boolean(usage_time_objects[split_id[1]][split_id[2]])) {
                            usage_time_objects[split_id[1]][split_id[2]] = {};
                        }
                    }
                    usage_time_objects[split_id[1]][split_id[2]][split_id[3]] = usage_time_array[i];

                    const element = usage_time_objects[split_id[1]][split_id[2]][split_id[3]];
                    if (split_id[3] == "sum") {
                        element.type = "hidden";
                        element.value = 0;
                        /** 如要修改id為usagetime-XXX-XXX-sum的hidden input的name值請在這邊修改
                            事實上，只有id結尾為-sum這個input有name值，也就是說只有他的value值會被收集
                            另外兩個結尾為-hour跟-minute的值只負責給使用者填入，這兩個input的值會在form submit時計算後相加進-sum input內才提交給後端
                            這邊的第一個鍵值self_evaluate代表是自我評估這個表單；
                            第二個鍵值代表是哪一組，這邊set_goal代表目標調控組；
                            第三個鍵值為什麼類型的資料，這邊usage_time代表的是「使用時間」的類型，因為在其他頁面還會有「選擇問題」的類型，所以才又再加一個小節
                            第四個鍵值為「花時間在什麼上面」的種類；第五個鍵值為非假日或假日
                            另外，這邊是JavaScript的模板字串，不是PHP的
                        ***/
                        element.name = `answer_sheet[self_evaluate][training_strategy][usage_time][${split_id[1]}][${split_id[2]}]`;
                    } else {
                        element.type = "number";
                        if (split_id[3] == "hour") {
                            element.max = 24;
                        } else {
                            element.max = 60;
                        }
                        element.min = 0;
                        element.placeholder = 0;
                        element.required = true;
                    }
                }
            }

            function initInputInvalid(first_invalid) {
                const all_input = document.querySelectorAll("input");
                const all_textarea = document.querySelectorAll("textarea");

                addInvalid(all_input, "input");
                addInvalid(all_textarea, "textarea");

                function addInvalid(element_list, element_type) {
                    for (let i = 0 ; i < element_list.length ; i++) {
                        if (element_type == "input") {
                            if (element_list[i].type == "hidden") {
                                continue;
                            } else if (element_list[i].type == "submit") {
                                continue;
                            }
                        }

                        element_list[i].addEventListener("invalid", (event) => {
                            if (first_invalid.flag) {
                                return false;
                            } else {
                                first_invalid.flag = true;
                                const src = event.srcElement;
                                if (isInViewport(src)) {
                                    setTimeout(() => {first_invalid.flag = false;}, 200);
                                    return;
                                } else {
                                    const timers = [null, null];
                                    timers[0] = setInterval(() => {
                                        if (isInViewport(src)) {
                                            clearInterval(timers[0]);
                                            clearTimeout(timers[1]);
                                            src.reportValidity();
                                            first_invalid.flag = false;
                                        }
                                    }, 200);

                                    timers[1] = setTimeout(() => {
                                        clearInterval(timers[0]);
                                        first_invalid.flag = false;
                                    }, 2000);
                                }
                            }
                        });
                    }
                }
            }

            function submitPreprocessing(usage_time_objects) {
                sumTimeMain(usage_time_objects);
            }

            function sumTimeMain(usage_time_objects) {
                for (let topic_type in usage_time_objects) {
                    for (let day_type in usage_time_objects[topic_type]) {
                        usage_time_objects[topic_type][day_type]["sum"].value = (usage_time_objects[topic_type][day_type]["hour"].value * 60) + (usage_time_objects[topic_type][day_type]["minute"].value * 1);
                    }
                }
            }
        </script>
        
        <div class="d-grid pt-5 pb-3 px-2 text-center">
            <h2>請依照您使用「健康上網APP」的情況回答下列問題，幫助我們暸解您上週的情形，同時也請您回顧和思考。</h2>
        </div>
        <div class="d-grid pt-3 px-2">
            <!-- <form id="form" action="" method="POST"> -->
            <?php echo '<form id="form" action="self_evaluate_target.php?u_id=' . $user['id'] . '" method="POST">' ?>
                <?php echo '<input type="hidden" name="form_name" value="' . $form_name . '">' ?>
                <div class="d-grid pt-2 px-2 text-center border-primary border-bottom border-3 w-auto mx-auto mb-3">
                    <h1><strong>第一部分</strong></h1>
                    <h3>上週使用時間評量</h3>
                </div>
                <div class="border-secondary border-opacity-50 border-bottom w-95 mx-3 py-3">
                    <h3>我這一週，平均每日用<strong>電腦</strong>玩<strong class="text-danger">遊戲</strong>的使用時間（沒使用請填0）：</h3>
                    <div class="container border border-dark border-2 rounded-bottom rounded-4">
                        <div class="row">
                            <div class="col text-center border-end border-dark">&nbsp;</div>
                            <div class="col text-center">用在非課業上</div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">非假日、<br>週一至週五</div>
                            <div class="col border-top border-dark">平均每日
                                <!--會在submit按鈕按下時將小時與分鐘數加總進hidden的input再送出資料-->
                                <!--usagetime-XXX-XXX-sum這個hidden type的input一開始沒有name值，值會在頁面load完時一起加上去，所以如果要修改建議到javascript函式中修改-->
                                <!--其他Attribute一樣也會在javascript中自動生成-->
                                <!--另外，加總後的值單位為分鐘-->
                                <input id="usagetime-pc_game-workday-hour">小時
                                <input id="usagetime-pc_game-workday-minute">分鐘
                                <input id="usagetime-pc_game-workday-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">假日、<br>週六、週日</div>
                            <div class="col border-top border-dark">平均每日
                                <input id="usagetime-pc_game-holiday-hour">小時
                                <input id="usagetime-pc_game-holiday-minute">分鐘
                                <input id="usagetime-pc_game-holiday-sum">
                            </div>
                        </div>
                    </div>
                    <br>
                    <h3>我這一週，平均每日用<strong>手機</strong>玩<strong class="text-danger">遊戲</strong>的使用時間（沒使用請填0）：</h3>
                    <div class="container border border-dark border-2 rounded-bottom rounded-4">
                        <div class="row">
                            <div class="col text-center border-end border-dark">&nbsp;</div>
                            <div class="col text-center">用在非課業上</div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">非假日、<br>週一至週五</div>
                            <div class="col border-top border-dark">平均每日
                                <input id="usagetime-mobile_game-workday-hour">小時
                                <input id="usagetime-mobile_game-workday-minute">分鐘
                                <input id="usagetime-mobile_game-workday-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">假日、<br>週六、週日</div>
                            <div class="col border-top border-dark">平均每日
                                <input id="usagetime-mobile_game-holiday-hour">小時
                                <input id="usagetime-mobile_game-holiday-minute">分鐘
                                <input id="usagetime-mobile_game-holiday-sum">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-secondary border-opacity-50 border-bottom border-top w-95 mx-3 py-3">
                    <h3>我這一週，平均每日用<strong>電腦</strong>使用<strong class="text-danger">社群網站</strong>的使用時間（沒使用請填0）：</h3>
                    <div class="container border border-dark border-2 rounded-bottom rounded-4">
                        <div class="row">
                            <div class="col text-center border-end border-dark">&nbsp;</div>
                            <div class="col text-center">用在非課業上</div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">非假日、<br>週一至週五</div>
                            <div class="col border-top border-dark">平均每日
                                <input id="usagetime-pc_socialmedia-workday-hour">小時
                                <input id="usagetime-pc_socialmedia-workday-minute">分鐘
                                <input id="usagetime-pc_socialmedia-workday-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">假日、<br>週六、週日</div>
                            <div class="col border-top border-dark">平均每日
                                <input id="usagetime-pc_socialmedia-holiday-hour">小時
                                <input id="usagetime-pc_socialmedia-holiday-minute">分鐘
                                <input id="usagetime-pc_socialmedia-holiday-sum">
                            </div>
                        </div>
                    </div>
                    <br>
                    <h3>我這一週，平均每日用<strong>手機</strong>使用<strong class="text-danger">社群網站</strong>的使用時間（沒使用請填0）：</h3>
                    <div class="container border border-dark border-2 rounded-bottom rounded-4">
                        <div class="row">
                            <div class="col text-center border-end border-dark">&nbsp;</div>
                            <div class="col text-center">用在非課業上</div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">非假日、<br>週一至週五</div>
                            <div class="col border-top border-dark">平均每日
                                <input id="usagetime-mobile_socialmedia-workday-hour">小時
                                <input id="usagetime-mobile_socialmedia-workday-minute">分鐘
                                <input id="usagetime-mobile_socialmedia-workday-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">假日、<br>週六、週日</div>
                            <div class="col border-top border-dark">平均每日
                                <input id="usagetime-mobile_socialmedia-holiday-hour">小時
                                <input id="usagetime-mobile_socialmedia-holiday-minute">分鐘
                                <input id="usagetime-mobile_socialmedia-holiday-sum">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-secondary border-opacity-50 border-bottom border-top w-95 mx-3 py-3">
                    <h3>我這一週，平均每日<strong class="text-danger">手機</strong>的使用時間（沒使用請填0）：</h3>
                    <div class="container border border-dark border-2 rounded-bottom rounded-4">
                        <div class="row">
                            <div class="col text-center border-end border-dark">&nbsp;</div>
                            <div class="col text-center">用在非課業上</div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">非假日、<br>週一至週五</div>
                            <div class="col border-top border-dark">平均每日
                                <input id="usagetime-mobile_avg-workday-hour">小時
                                <input id="usagetime-mobile_avg-workday-minute">分鐘
                                <input id="usagetime-mobile_avg-workday-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">假日、<br>週六、週日</div>
                            <div class="col border-top border-dark">平均每日
                                <input id="usagetime-mobile_avg-holiday-hour">小時
                                <input id="usagetime-mobile_avg-holiday-minute">分鐘
                                <input id="usagetime-mobile_avg-holiday-sum">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-secondary border-opacity-50 border-bottom border-top w-95 mx-3 py-3">
                    <h3>我這一週，平均每日用<strong>電腦</strong><strong class="text-danger">上網</strong>的使用時間（沒使用請填0）：</h3>
                    <div class="container border border-dark border-2 rounded-bottom rounded-4">
                        <div class="row">
                            <div class="col text-center border-end border-dark">&nbsp;</div>
                            <div class="col text-center">用在非課業上</div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">非假日、<br>週一至週五</div>
                            <div class="col border-top border-dark">平均每日
                                <input id="usagetime-pc_online-workday-hour">小時
                                <input id="usagetime-pc_online-workday-minute">分鐘
                                <input id="usagetime-pc_online-workday-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">假日、<br>週六、週日</div>
                            <div class="col border-top border-dark">平均每日
                                <input id="usagetime-pc_online-holiday-hour">小時
                                <input id="usagetime-pc_online-holiday-minute">分鐘
                                <input id="usagetime-pc_online-holiday-sum">
                            </div>
                        </div>
                    </div>
                    <br>
                    <h3>我這一週，平均每日用<strong>手機</strong><strong class="text-danger">上網</strong>的使用時間（沒使用請填0）：</h3>
                    <div class="container border border-dark border-2 rounded-bottom rounded-4">
                        <div class="row">
                            <div class="col text-center border-end border-dark">&nbsp;</div>
                            <div class="col text-center">用在非課業上</div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">非假日、<br>週一至週五</div>
                            <div class="col border-top border-dark">平均每日
                                <input id="usagetime-mobile_online-workday-hour">小時
                                <input id="usagetime-mobile_online-workday-minute">分鐘
                                <input id="usagetime-mobile_online-workday-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">假日、<br>週六、週日</div>
                            <div class="col border-top border-dark">平均每日
                                <input id="usagetime-mobile_online-holiday-hour">小時
                                <input id="usagetime-mobile_online-holiday-minute">分鐘
                                <input id="usagetime-mobile_online-holiday-sum">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-secondary border-opacity-50 border-bottom border-top w-95 mx-3 py-3">
                    <h3>我這一週，平均每日用<strong>電腦</strong>使用<strong class="text-danger">網路影音</strong>的使用時間（沒使用請填0）：</h3>
                    <div class="container border border-dark border-2 rounded-bottom rounded-4">
                        <div class="row">
                            <div class="col text-center border-end border-dark">&nbsp;</div>
                            <div class="col text-center">用在非課業上</div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">非假日、<br>週一至週五</div>
                            <div class="col border-top border-dark">平均每日
                                <input id="usagetime-pc_internetmultimedia-workday-hour">小時
                                <input id="usagetime-pc_internetmultimedia-workday-minute">分鐘
                                <input id="usagetime-pc_internetmultimedia-workday-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">假日、<br>週六、週日</div>
                            <div class="col border-top border-dark">平均每日
                                <input id="usagetime-pc_internetmultimedia-holiday-hour">小時
                                <input id="usagetime-pc_internetmultimedia-holiday-minute">分鐘
                                <input id="usagetime-pc_internetmultimedia-holiday-sum">
                            </div>
                        </div>
                    </div>
                    <br>
                    <h3>我這一週，平均每日用<strong>手機</strong>使用<strong class="text-danger">網路影音</strong>的使用時間（沒使用請填0）：</h3>
                    <div class="container border border-dark border-2 rounded-bottom rounded-4">
                        <div class="row">
                            <div class="col text-center border-end border-dark">&nbsp;</div>
                            <div class="col text-center">用在非課業上</div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">非假日、<br>週一至週五</div>
                            <div class="col border-top border-dark">平均每日
                                <input id="usagetime-mobile_internetmultimedia-workday-hour">小時
                                <input id="usagetime-mobile_internetmultimedia-workday-minute">分鐘
                                <input id="usagetime-mobile_internetmultimedia-workday-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">假日、<br>週六、週日</div>
                            <div class="col border-top border-dark">平均每日
                                <input id="usagetime-mobile_internetmultimedia-holiday-hour">小時
                                <input id="usagetime-mobile_internetmultimedia-holiday-minute">分鐘
                                <input id="usagetime-mobile_internetmultimedia-holiday-sum">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-secondary border-opacity-50 border-top w-95 mx-3 py-3">
                    <h3>我這一週，平均每日用<strong>電腦</strong>使用<strong class="text-danger">通訊軟體</strong>的使用時間（沒使用請填0）：</h3>
                    <div class="container border border-dark border-2 rounded-bottom rounded-4">
                        <div class="row">
                            <div class="col text-center border-end border-dark">&nbsp;</div>
                            <div class="col text-center">用在非課業上</div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">非假日、<br>週一至週五</div>
                            <div class="col border-top border-dark">平均每日
                                <input id="usagetime-pc_messagingapp-workday-hour">小時
                                <input id="usagetime-pc_messagingapp-workday-minute">分鐘
                                <input id="usagetime-pc_messagingapp-workday-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">假日、<br>週六、週日</div>
                            <div class="col border-top border-dark">平均每日
                                <input id="usagetime-pc_messagingapp-holiday-hour">小時
                                <input id="usagetime-pc_messagingapp-holiday-minute">分鐘
                                <input id="usagetime-pc_messagingapp-holiday-sum">
                            </div>
                        </div>
                    </div>
                    <br>
                    <h3>我這一週，平均每日用<strong>手機</strong>使用<strong class="text-danger">通訊軟體</strong>的使用時間（沒使用請填0）：</h3>
                    <div class="container border border-dark border-2 rounded-bottom rounded-4">
                        <div class="row">
                            <div class="col text-center border-end border-dark">&nbsp;</div>
                            <div class="col text-center">用在非課業上</div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">非假日、<br>週一至週五</div>
                            <div class="col border-top border-dark">平均每日
                                <input id="usagetime-mobile_messagingapp-workday-hour">小時
                                <input id="usagetime-mobile_messagingapp-workday-minute">分鐘
                                <input id="usagetime-mobile_messagingapp-workday-sum">
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="d-grid p-5 border-primary border-top border-3">
                    <input id="submit" class="mx-5 bg-primary bg-gradient bg-opacity-50 border-info border-opacity-75 rounded-4" type="submit" value="提交">
                </div>
            </form>
        </div>
    </div>
</body>
</html>