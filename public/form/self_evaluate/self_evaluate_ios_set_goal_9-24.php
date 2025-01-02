<!--<Version : 3.0>-->
<?php
/**
 * 自我評估單 ios版
 * Week 9-24
 */

require_once 'connect_db.php';
require_once 'get_user.php';
require_once 'html_head.php';

$form_name = FORM_NAME['self_evaluate']['ios'][$user['exp_type']][$week];
?>
        <script type="text/javascript">
            window.addEventListener("load", () => {
                const protected_values_pool = ProtectedValuesManager({question_array : [], usage_time_objects : {}, first_invalid : {flag : false}});
                const question_array = protected_values_pool.getValues("question_array");
                const usage_time_objects = protected_values_pool.getValues("usage_time_objects");
                const first_invalid = protected_values_pool.getValues("first_invalid");

                initQuestionObjects(question_array);
                initUsageTimeObjects(usage_time_objects);
                initInputInvalid(first_invalid);
                
                document.querySelector("#submit").addEventListener("click", () => {submitPrecheck(question_array);});
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
            
            function initQuestionObjects(questions_array) {
                const questions_collector = document.querySelectorAll(`div[id|="${"question"}"]`);

                for (let i = 0 ; i < questions_collector.length ; i++) {
                    questions_array.push(questions_collector[i]);
                    initQuestionsOptionAction(questions_collector[i]);
                }
            }

            function initQuestionsOptionAction(question) {
                const options = question.querySelectorAll("input");
                const groups = {};
                const uncheckers = {};

                for (let i = 0 ; i < options.length ; i++) {
                    const split_id = options[i].id.split("-");
                    if (!Boolean(groups[split_id[2]])) {
                        groups[split_id[2]] = [];
                        uncheckers[split_id[2]] = function() {
                            for (group in groups) {
                                if ((group == split_id[2])) {
                                    groups[group].forEach(customValidityRemover);
                                } else {
                                    groups[group].forEach(unchecker);
                                }
                            }
                        };
                    }
                    groups[split_id[2]].push(options[i]);
                    options[i].addEventListener("input", uncheckers[split_id[2]]);

                    options[i].type = "checkbox";
                    // 複選擇題name值在這邊修改
                    options[i].name = `answer_sheet[self_evaluate][set_goal][question][${split_id[0]}][]`;
                    options[i].value = split_id[1];
                }
            }

            function customValidityRemover(element) {
                element.setCustomValidity("");
            }

            function unchecker(element) {
                element.checked = false;
            }

            function initUsageTimeObjects(usage_time_objects) {
                const usage_time_array = document.querySelectorAll(`input[id|="${"usagetime"}"]`);

                for (let i = 0 ; i < usage_time_array.length ; i++) {
                    // 如果id是"usagetime-pc_game-workday-hour"就會切成[usagetime, pc_game, workday, hour]
                    const split_id = usage_time_array[i].id.split("-");
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
                        element.name = `answer_sheet[self_evaluate][set_goal][usage_time][${split_id[1]}][${split_id[2]}]`;
                    } else {
                        element.type = "number";
                        if (split_id[3] == "hour") {
                            element.max = 23;
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

            function submitPrecheck(question_array) {
                for (let element of question_array) {
                    const inner_checkboxes = element.querySelectorAll("input");
                    if (element.querySelectorAll("input:checked").length) {
                        for (let checkbox of inner_checkboxes) {
                            checkbox.setCustomValidity("");
                        }
                    } else {
                        inner_checkboxes[0].setCustomValidity("該題尚未作答，請至少選擇其中一項");
                        return false;
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
            <h2>請依照您使用「健康上網表單」的情況回答下列問題，幫助我們暸解您上週<strong class="text-info"><?php echo '(' . (($start_date->modify((1 + $week * 7) . ' day'))->format('Y-m-d')) . '~' . (($start_date->modify((($week + 1) * 7) . ' day'))->format('Y-m-d')) . ')'; ?></strong>的情形，同時也請您回顧和思考。（手機使用時間請參照iPhone的「螢幕使用時間」功能，電腦使用時間請自行評估）</h2>
        </div>
        <div class="d-grid pt-3 px-2">
            <!-- <form id="form" action="" method="POST"> -->
            <?php echo '<form id="form" action="self_evaluate_ios_submit.php?u_id=' . $user['id'] . '" method="POST">' ?>
                <?php echo '<input type="hidden" name="form_name" value="' . $form_name . '">' ?>
                <div class="d-grid pt-2 px-2 text-center border-primary border-bottom border-3 w-auto mx-auto mb-3">
                    <h1><strong>第一部分</strong></h1>
                    <h3>上週使用時間和使用行為評量</h3>
                </div>
                <div class="border-secondary border-opacity-50 border-bottom w-95 mx-3 py-3">
                    <h3>一、上一週自律訓練過程「健康上網表單」回饋的功能是否能讓我…</h3>
                    <div id="question-1_1" class="container clearfix">
                        <h4>1. 是否每天知道手機各類型App使用時間，知道的是第幾天?&nbsp;&nbsp;星期：</h4>
                        <div class="float-start">
                            <!--id的格式:[q第幾題(同時也是表單name的值)-該題第幾個選項(的值)-第幾個群]-->
                            <input id="q1_1-0-0" class="form-check-input p-3 mx-3">
                            <label for="q1_1-0-0" class="mt-2 pe-5"><h3>不知道</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q1_1-1-1" class="form-check-input p-3 mx-3">
                            <label for="q1_1-1-1" class="mt-2 pe-5"><h3>一</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q1_1-2-1" class="form-check-input p-3 mx-3">
                            <label for="q1_1-2-1" class="mt-2 pe-5"><h3>二</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q1_1-3-1" class="form-check-input p-3 mx-3">
                            <label for="q1_1-3-1" class="mt-2 pe-5"><h3>三</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q1_1-4-1" class="form-check-input p-3 mx-3">
                            <label for="q1_1-4-1" class="mt-2 pe-5"><h3>四</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q1_1-5-1" class="form-check-input p-3 mx-3">
                            <label for="q1_1-5-1" class="mt-2 pe-5"><h3>五</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q1_1-6-1" class="form-check-input p-3 mx-3">
                            <label for="q1_1-6-1" class="mt-2 pe-5"><h3>六</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q1_1-7-1" class="form-check-input p-3 mx-3">
                            <label for="q1_1-7-1" class="mt-2 pe-5"><h3>日</h3></label>
                        </div>
                    </div><br>
                    <div id="question-1_2" class="container clearfix">
                        <h4>2. 是否每天知道使用時間排名，知道的是第幾天?&nbsp;&nbsp;星期：</h4>
                        <div class="float-start">
                            <input id="q1_2-0-0" class="form-check-input p-3 mx-3">
                            <label for="q1_2-0-0" class="mt-2 pe-5"><h3>不知道</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q1_2-1-1" class="form-check-input p-3 mx-3">
                            <label for="q1_2-1-1" class="mt-2 pe-5"><h3>一</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q1_2-2-1" class="form-check-input p-3 mx-3">
                            <label for="q1_2-2-1" class="mt-2 pe-5"><h3>二</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q1_2-3-1" class="form-check-input p-3 mx-3">
                            <label for="q1_2-3-1" class="mt-2 pe-5"><h3>三</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q1_2-4-1" class="form-check-input p-3 mx-3">
                            <label for="q1_2-4-1" class="mt-2 pe-5"><h3>四</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q1_2-5-1" class="form-check-input p-3 mx-3">
                            <label for="q1_2-5-1" class="mt-2 pe-5"><h3>五</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q1_2-6-1" class="form-check-input p-3 mx-3">
                            <label for="q1_2-6-1" class="mt-2 pe-5"><h3>六</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q1_2-7-1" class="form-check-input p-3 mx-3">
                            <label for="q1_2-7-1" class="mt-2 pe-5"><h3>日</h3></label>
                        </div>
                    </div><br>
                </div>
                <div class="border-secondary border-opacity-50 border-top w-95 mx-3 py-3">
                    <h3>二、「健康上網表單」的回饋是否有幫助於我…</h3>
                    <div class="container clearfix">
                        <h4>1. 了解健康上網的重要性</h4>
                        <div class="float-start">
                            <input id="q2_1-1-1" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet[self_evaluate][set_goal][question][q2_1]" value="1" required>
                            <label for="q2_1-1-1" class="mt-2 pe-5"><h3>非常不同意</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q2_1-2-2" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet[self_evaluate][set_goal][question][q2_1]" value="2">
                            <label for="q2_1-2-2" class="mt-2 pe-5"><h3>不同意</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q2_1-3-3" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet[self_evaluate][set_goal][question][q2_1]" value="3">
                            <label for="q2_1-3-3" class="mt-2 pe-5"><h3>普通</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q2_1-4-4" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet[self_evaluate][set_goal][question][q2_1]" value="4">
                            <label for="q2_1-4-4" class="mt-2 pe-5"><h3>同意</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q2_1-5-5" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet[self_evaluate][set_goal][question][q2_1]" value="5">
                            <label for="q2_1-5-5" class="mt-2 pe-5"><h3>非常同意</h3></label>
                        </div>
                    </div><br>
                    <div class="container clearfix">
                        <h4>2. 提升我想健康上網的動機</h4>
                        <div class="float-start">
                            <input id="q2_2-1-1" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet[self_evaluate][set_goal][question][q2_2]" value="1" required>
                            <label for="q2_2-1-1" class="mt-2 pe-5"><h3>非常不同意</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q2_2-2-2" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet[self_evaluate][set_goal][question][q2_2]" value="2">
                            <label for="q2_2-2-2" class="mt-2 pe-5"><h3>不同意</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q2_2-3-3" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet[self_evaluate][set_goal][question][q2_2]" value="3">
                            <label for="q2_2-3-3" class="mt-2 pe-5"><h3>普通</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q2_2-4-4" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet[self_evaluate][set_goal][question][q2_2]" value="4">
                            <label for="q2_2-4-4" class="mt-2 pe-5"><h3>同意</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q2_2-5-5" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet[self_evaluate][set_goal][question][q2_2]" value="5">
                            <label for="q2_2-5-5" class="mt-2 pe-5"><h3>非常同意</h3></label>
                        </div>
                    </div><br>
                    <div class="container clearfix">
                        <h4>3. 可實際幫助我做健康上網的時間管理</h4>
                        <div class="float-start">
                            <input id="q2_3-1-1" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet[self_evaluate][set_goal][question][q2_3]" value="1" required>
                            <label for="q2_3-1-1" class="mt-2 pe-5"><h3>非常不同意</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q2_3-2-2" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet[self_evaluate][set_goal][question][q2_3]" value="2">
                            <label for="q2_3-2-2" class="mt-2 pe-5"><h3>不同意</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q2_3-3-3" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet[self_evaluate][set_goal][question][q2_3]" value="3">
                            <label for="q2_3-3-3" class="mt-2 pe-5"><h3>普通</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q2_3-4-4" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet[self_evaluate][set_goal][question][q2_3]" value="4">
                            <label for="q2_3-4-4" class="mt-2 pe-5"><h3>同意</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q2_3-5-5" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet[self_evaluate][set_goal][question][q2_3]" value="5">
                            <label for="q2_3-5-5" class="mt-2 pe-5"><h3>非常同意</h3></label>
                        </div>
                    </div><br>
                    <div class="container clearfix">
                        <h4>4. 有助於我3C自控力的提升</h4>
                        <div class="float-start">
                            <input id="q2_4-1-1" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet[self_evaluate][set_goal][question][q2_4]" value="1" required>
                            <label for="q2_4-1-1" class="mt-2 pe-5"><h3>非常不同意</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q2_4-2-2" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet[self_evaluate][set_goal][question][q2_4]" value="2">
                            <label for="q2_4-2-2" class="mt-2 pe-5"><h3>不同意</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q2_4-3-3" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet[self_evaluate][set_goal][question][q2_4]" value="3">
                            <label for="q2_4-3-3" class="mt-2 pe-5"><h3>普通</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q2_4-4-4" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet[self_evaluate][set_goal][question][q2_4]" value="4">
                            <label for="q2_4-4-4" class="mt-2 pe-5"><h3>同意</h3></label>
                        </div>
                        <div class="float-start">
                            <input id="q2_4-5-5" class="form-check-input p-3 mx-3" type="radio" name="answer_sheet[self_evaluate][set_goal][question][q2_4]" value="5">
                            <label for="q2_4-5-5" class="mt-2 pe-5"><h3>非常同意</h3></label>
                        </div>
                    </div><br>
                    <div class="container">
                        <label for="q2_5"><h4>5. 對此研究的建議</h4></label>
                        <textarea id="q2_5" class="form-control" name="answer_sheet[self_evaluate][set_goal][question][q2_5]" placeholder="問答題: 對此研究有哪些建議?" required></textarea>
                    </div><br>
                </div><br>
                <div class="d-grid pt-2 px-2 text-center border-primary border-bottom border-top border-3 w-auto mx-auto mb-3">
                    <h1><br><strong>第二部分</strong></h1>
                    <h3>上週使用時間評量</h3>
                    <h5>請從上週一開始填寫至週日</h5>
                </div>
                <div class="border-secondary border-opacity-50 border-bottom w-95 mx-3 py-3">
                    <h3>我這一週，每日的<strong>螢幕使用時間</strong>（沒使用請填0）：</h3>
                    <div class="container border border-dark border-2 rounded-bottom rounded-4">
                        <div class="row">
                            <div class="col text-center border-end border-dark">週一</div>
                            <div class="col">每日
                                <input id="usagetime-mobile_total-mon-hour">小時
                                <input id="usagetime-mobile_total-mon-minute">分鐘
                                <input id="usagetime-mobile_total-mon-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">週二</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_total-tue-hour">小時
                                <input id="usagetime-mobile_total-tue-minute">分鐘
                                <input id="usagetime-mobile_total-tue-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">週三</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_total-wed-hour">小時
                                <input id="usagetime-mobile_total-wed-minute">分鐘
                                <input id="usagetime-mobile_total-wed-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">週四</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_total-thu-hour">小時
                                <input id="usagetime-mobile_total-thu-minute">分鐘
                                <input id="usagetime-mobile_total-thu-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">週五</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_total-fri-hour">小時
                                <input id="usagetime-mobile_total-fri-minute">分鐘
                                <input id="usagetime-mobile_total-fri-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center text-danger border-top border-end border-dark">週六</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_total-sat-hour">小時
                                <input id="usagetime-mobile_total-sat-minute">分鐘
                                <input id="usagetime-mobile_total-sat-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center text-danger border-top border-end border-dark">週日</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_total-sun-hour">小時
                                <input id="usagetime-mobile_total-sun-minute">分鐘
                                <input id="usagetime-mobile_total-sun-sum">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-secondary border-opacity-50 border-bottom border-top w-95 mx-3 py-3">
                    <h3>我這一週，每日用手機玩<strong>遊戲</strong>的使用時間（沒使用請填0）：</h3>
                    <div class="container border border-dark border-2 rounded-bottom rounded-4">
                        <div class="row">
                            <div class="col text-center border-end border-dark">週一</div>
                            <div class="col">每日
                                <input id="usagetime-mobile_game-mon-hour">小時
                                <input id="usagetime-mobile_game-mon-minute">分鐘
                                <input id="usagetime-mobile_game-mon-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">週二</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_game-tue-hour">小時
                                <input id="usagetime-mobile_game-tue-minute">分鐘
                                <input id="usagetime-mobile_game-tue-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">週三</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_game-wed-hour">小時
                                <input id="usagetime-mobile_game-wed-minute">分鐘
                                <input id="usagetime-mobile_game-wed-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">週四</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_game-thu-hour">小時
                                <input id="usagetime-mobile_game-thu-minute">分鐘
                                <input id="usagetime-mobile_game-thu-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">週五</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_game-fri-hour">小時
                                <input id="usagetime-mobile_game-fri-minute">分鐘
                                <input id="usagetime-mobile_game-fri-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center text-danger border-top border-end border-dark">週六</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_game-sat-hour">小時
                                <input id="usagetime-mobile_game-sat-minute">分鐘
                                <input id="usagetime-mobile_game-sat-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center text-danger border-top border-end border-dark">週日</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_game-sun-hour">小時
                                <input id="usagetime-mobile_game-sun-minute">分鐘
                                <input id="usagetime-mobile_game-sun-sum">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-secondary border-opacity-50 border-bottom border-top w-95 mx-3 py-3">
                    <h3>我這一週，每日用手機使用<strong>社交</strong>的使用時間（沒使用請填0）：</h3>
                    <div class="container border border-dark border-2 rounded-bottom rounded-4">
                        <div class="row">
                            <div class="col text-center border-end border-dark">週一</div>
                            <div class="col">每日
                                <input id="usagetime-mobile_social-mon-hour">小時
                                <input id="usagetime-mobile_social-mon-minute">分鐘
                                <input id="usagetime-mobile_social-mon-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">週二</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_social-tue-hour">小時
                                <input id="usagetime-mobile_social-tue-minute">分鐘
                                <input id="usagetime-mobile_social-tue-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">週三</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_social-wed-hour">小時
                                <input id="usagetime-mobile_social-wed-minute">分鐘
                                <input id="usagetime-mobile_social-wed-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">週四</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_social-thu-hour">小時
                                <input id="usagetime-mobile_social-thu-minute">分鐘
                                <input id="usagetime-mobile_social-thu-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">週五</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_social-fri-hour">小時
                                <input id="usagetime-mobile_social-fri-minute">分鐘
                                <input id="usagetime-mobile_social-fri-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center text-danger border-top border-end border-dark">週六</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_social-sat-hour">小時
                                <input id="usagetime-mobile_social-sat-minute">分鐘
                                <input id="usagetime-mobile_social-sat-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center text-danger border-top border-end border-dark">週日</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_social-sun-hour">小時
                                <input id="usagetime-mobile_social-sun-minute">分鐘
                                <input id="usagetime-mobile_social-sun-sum">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-secondary border-opacity-50 border-bottom border-top w-95 mx-3 py-3">
                    <h3>我這一週，每日用手機使用<strong>娛樂</strong>的使用時間（沒使用請填0）：</h3>
                    <div class="container border border-dark border-2 rounded-bottom rounded-4">
                        <div class="row">
                            <div class="col text-center border-end border-dark">週一</div>
                            <div class="col">每日
                                <input id="usagetime-mobile_entertainment-mon-hour">小時
                                <input id="usagetime-mobile_entertainment-mon-minute">分鐘
                                <input id="usagetime-mobile_entertainment-mon-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">週二</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_entertainment-tue-hour">小時
                                <input id="usagetime-mobile_entertainment-tue-minute">分鐘
                                <input id="usagetime-mobile_entertainment-tue-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">週三</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_entertainment-wed-hour">小時
                                <input id="usagetime-mobile_entertainment-wed-minute">分鐘
                                <input id="usagetime-mobile_entertainment-wed-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">週四</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_entertainment-thu-hour">小時
                                <input id="usagetime-mobile_entertainment-thu-minute">分鐘
                                <input id="usagetime-mobile_entertainment-thu-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center border-top border-end border-dark">週五</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_entertainment-fri-hour">小時
                                <input id="usagetime-mobile_entertainment-fri-minute">分鐘
                                <input id="usagetime-mobile_entertainment-fri-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center text-danger border-top border-end border-dark">週六</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_entertainment-sat-hour">小時
                                <input id="usagetime-mobile_entertainment-sat-minute">分鐘
                                <input id="usagetime-mobile_entertainment-sat-sum">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center text-danger border-top border-end border-dark">週日</div>
                            <div class="col border-top border-dark">每日
                                <input id="usagetime-mobile_entertainment-sun-hour">小時
                                <input id="usagetime-mobile_entertainment-sun-minute">分鐘
                                <input id="usagetime-mobile_entertainment-sun-sum">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-secondary border-opacity-50 border-bottom border-top w-95 mx-3 py-3">
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