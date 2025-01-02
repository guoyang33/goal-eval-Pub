<?php
/**
 * 策略訓練 應用
 * Week 5~8
 * 觀看遊戲廣告擴充JS/library生成檔
 * 第3週更新新增[更新時間: 2023-03-11 01:00~12:00]
 * 第4週有修改[修改時間: 2023-03-23 01:00~12:00]
 * 修改爐石戰記的影片ID [2023-10-17 更新]
 * 加入測試功能，$GET[test]=training_strategy_practice_js時檢示所有影片 [2023-10-17 更新]
 */

define('EAGER_LEVEL_CHINESE', array(
    '完全沒有',
    '非常微弱',
    '輕微',
    '中等',
    '有點強，但我能輕鬆自控',
    '很強，我難以自控',
    '超強，我控制不住我的手手了'
));
// ytv_id意思是youtube video ID
define('GAMES_LIB_JSON_ENCODE_STRING', json_encode(array(
    array('category' => '角色扮演', 'games' => array(
        array('name_zh' => '原神', 'ytv_id' => '_DGSLI29W7s'),          // 2023-10-23 更新
        // array('name_zh' => '天堂W', 'ytv_id' => '1uVcM7fsHoM'),      // 2023-10-23 更新：應上頭要求，暫時移除
        array('name_zh' => '勝利女神：妮姬', 'ytv_id' => 'dyoRWt9yx2I'),
        array('name_zh' => 'RO：愛如初見', 'ytv_id' => 'Mcvsl3SkggE')
    )),
    array('category' => '紙牌', 'games' => array(
        // array('name_zh' => '爐石戰記', 'ytv_id' => 'wnb2qVzs4dA'),   // 2023-10-17 更新：連結失效
        array('name_zh' => '爐石戰記', 'ytv_id' => 'JuDfah98WYk'),      // 2023-10-23 更新
        array('name_zh' => '遊戲王：決鬥聯盟', 'ytv_id' => '4Y1Ldb-zDyk'),
        array('name_zh' => 'Fate/Grand Order', 'ytv_id' => 'SnhGPEIzrBU')
    )),
    array('category' => '動作', 'games' => array(
        array('name_zh' => '傳說對決', 'ytv_id' => 'PQS9B2ZRR9E'),
        array('name_zh' => '崩壞3rd', 'ytv_id' => '4Xz_psIlPzQ'),
        array('name_zh' => '決勝時刻Mobile', 'ytv_id' => 'SgG8pW6QVno')
    )),
    array('category' => '模擬', 'games' => array(
        array('name_zh' => '三國志霸道', 'ytv_id' => '5fs6GgbnJVY'),
        array('name_zh' => '戀與製作人', 'ytv_id' => 'YZtbV3riieM'),
        array('name_zh' => '叫我大掌櫃', 'ytv_id' => 'DwihncTD46M')
    )),
    array('category' => '街機', 'games' => array(
        array('name_zh' => '跑跑薑餅人', 'ytv_id' => 'CSTiAbGxAZ8'),
        array('name_zh' => '不算英雄', 'ytv_id' => 'pnABaETg3xo'),
        array('name_zh' => 'Minecraft', 'ytv_id' => 'SUG367PW75s')
    )),
    array('category' => '音樂', 'games' => array(
        array('name_zh' => '世界計畫', 'ytv_id' => 'zHS9kJU-zfo'),
        array('name_zh' => 'Bang Dream', 'ytv_id' => '8Aqp4f8cEmk'),
        array('name_zh' => 'IDOLiSH7-偶像星願', 'ytv_id' => 'OzfrWGOec1c')
    )),
    array('category' => '博奕', 'games' => array(
        array('name_zh' => '星城Online', 'ytv_id' => 'cv8wBOvvi1c'),
        array('name_zh' => '明星三缺一', 'ytv_id' => '4S0u-CdCCzs'),
        array('name_zh' => '老子有錢', 'ytv_id' => '8UYsp2yrccg')
    )),
    array('category' => '賽車', 'games' => array(
        array('name_zh' => '跑跑卡丁車Rush+', 'ytv_id' => 'hei4YEXoESs'),
        array('name_zh' => '狂野飆車', 'ytv_id' => 'cLZtrlt6K5I'),
        array('name_zh' => '極速領域：逐夢同行', 'ytv_id' => '2BUhVqSx3-4')
    )),
    array('category' => '策略', 'games' => array(
        array('name_zh' => '末日喧囂', 'ytv_id' => 'bsClKdW6MPo'),
        array('name_zh' => '王國紀元', 'ytv_id' => 'GzQ1XpmcaXU'),
        array('name_zh' => '三國志戰略版', 'ytv_id' => 'XZbGVF25XRQ')
    )),
)));

define('GAME_ADS_OPTION_ONINPUT_FUNCTION_NAME', 'onInputGameadsOption');
define('GAME_ADS_NEXT_BUTTON_ONINPUT_FUNCTION_NAME', 'onInputGameadsNextButton');

define('YOUTUBE_DEFAULT_VIDEO_WIDTH', '320');
define('YOUTUBE_DEFAULT_VIDEO_HEIGHT', '180');
define('YOUTUBE_VIDEO_LOGO_WIDTH', '60');
define('YOUTUBE_VIDEO_LOGO_HEIGHT', '40');
define('YOUTUBE_VIDEO_TITLE_HEIGHT', '70');
define('YOUTUBE_VIDEO_WATERMARK_WIDTH', '150');
define('YOUTUBE_VIDEO_WATERMARK_HEIGHT', '70');
// shadow iframe的作用為隨影片iframe一同改變大小，用以偵測resize事件。
define('YOUTUBE_DEFAULT_VIDEO_MASK',
    '`<div id="${skip_button_backboard_id}" style="background-color: rgba(0, 0, 0, 0); pointer-events: none; position: absolute; z-index: 128;"><div class="position-relative" style="width: 100%; height: 100%; pointer-events: none;"></div></div>
    <svg id="${mask_id}" preserveAspectRatio="xMidYMid meet" viewbox="0,0,' . YOUTUBE_DEFAULT_VIDEO_WIDTH . ',' . YOUTUBE_DEFAULT_VIDEO_HEIGHT . '" style="width: ' . YOUTUBE_DEFAULT_VIDEO_WIDTH . 'px; height: ' . YOUTUBE_DEFAULT_VIDEO_HEIGHT . 'px; pointer-events: none; position: absolute; z-index: 126;">
        <g x="0" y="0" style="fill: rgba(0, 0, 0, 0); fill-rule: evenodd; pointer-events: fill;">
            <path d="M0 0 V' . YOUTUBE_DEFAULT_VIDEO_HEIGHT . ' H' . YOUTUBE_DEFAULT_VIDEO_WIDTH . ' V0 Z M' . ((YOUTUBE_DEFAULT_VIDEO_WIDTH - YOUTUBE_VIDEO_LOGO_WIDTH) / 2) . ' ' . ((YOUTUBE_DEFAULT_VIDEO_HEIGHT - YOUTUBE_VIDEO_LOGO_HEIGHT) / 2) . ' V' . ((YOUTUBE_DEFAULT_VIDEO_HEIGHT + YOUTUBE_VIDEO_LOGO_HEIGHT) / 2) . ' H' . ((YOUTUBE_DEFAULT_VIDEO_WIDTH + YOUTUBE_VIDEO_LOGO_WIDTH) / 2) . ' V' . ((YOUTUBE_DEFAULT_VIDEO_HEIGHT - YOUTUBE_VIDEO_LOGO_HEIGHT) / 2) . ' Z"></path>
        </g>
    </svg>
    <iframe id="${shadow_id}" class="d-none" style="position: absolute; z-index: 1;"></iframe>`'
);
define('YOUTUBE_DYNAMIC_VIDEO_MASK_UNSTARTED_TEMPLATE', '`<path d="M0 0 V' . YOUTUBE_DEFAULT_VIDEO_HEIGHT . ' H' . YOUTUBE_DEFAULT_VIDEO_WIDTH . ' V0 Z M' . ((YOUTUBE_DEFAULT_VIDEO_WIDTH - YOUTUBE_VIDEO_LOGO_WIDTH) / 2) . ' ' . ((YOUTUBE_DEFAULT_VIDEO_HEIGHT - YOUTUBE_VIDEO_LOGO_HEIGHT) / 2) . ' V' . ((YOUTUBE_DEFAULT_VIDEO_HEIGHT + YOUTUBE_VIDEO_LOGO_HEIGHT) / 2) . ' H' . ((YOUTUBE_DEFAULT_VIDEO_WIDTH + YOUTUBE_VIDEO_LOGO_WIDTH) / 2) . ' V' . ((YOUTUBE_DEFAULT_VIDEO_HEIGHT - YOUTUBE_VIDEO_LOGO_HEIGHT) / 2) . ' Z"></path>`');
/**
 * define('YOUTUBE_DYNAMIC_VIDEO_MASK_PLAYING_TEMPLATE', '`<path d="M0 0 V${mask_height} H${mask_width} V0 Z M0 ' . YOUTUBE_VIDEO_TITLE_HEIGHT . ' V${mask_height} H${(mask_width - ' . YOUTUBE_VIDEO_WATERMARK_WIDTH . ')} V${(mask_height - ' . YOUTUBE_VIDEO_WATERMARK_HEIGHT . ')} H${mask_width} V' . YOUTUBE_VIDEO_TITLE_HEIGHT . ' Z"></path>`');
 * [2023-03-22] 改為同樣遮擋全部區域
 */
define('YOUTUBE_DYNAMIC_VIDEO_MASK_PLAYING_TEMPLATE', '`<path d="M0 0 V${mask_height} H${mask_width} V0 Z"></path>`');
define('YOUTUBE_DYNAMIC_VIDEO_MASK_ENDED_TEMPLATE', '`<path d="M0 0 V${mask_height} H${mask_width} V0 Z"></path>`');

// 略過影片相關參數
define('YOUTUBE_VIDEO_SKIP_TRIGGER_TEMPLATE', '`<button type="button" id="${skip_button_id}" class="btn btn-outline-secondary position-absolute bottom-0 end-0 mb-1 me-1 d-none prevent_inherit_and_override_disabled_pointer_events_auto" style="background-color: rgba(0, 0, 0, 0);" disabled></button>`');
define('YOUTUBE_VIDEO_SKIP_WAIT_TIME', '61000');            // 略過影片總共需要等待的時間
define('YOUTUBE_VIDEO_SKIP_FIRST_HIDE_TIME', '54000');      // 第一次隱藏按鈕
define('YOUTUBE_VIDEO_SKIP_AGAIN_SHOW_TIME', '10000');      // 再次顯示按鈕

define('GAME_ADS_OPTION_HTML_TEMPLATE',
    '`<div class="d-grid pt-3 px-2">
        <input type="checkbox" id="gameads_option_${option_number}" class="btn-check" oninput="' . GAME_ADS_OPTION_ONINPUT_FUNCTION_NAME . '(event);">
        <label class="position-relative btn btn-outline-success" for="gameads_option_${option_number}">
            <div id="gameads_option_${option_number}_order" class="position-absolute top-0 start-100 translate-middle badge border border-light rounded-circle bg-danger p-2 d-none"></div>
            ${gameads_option_name}
        </label>
    </div>`'
);

// 將看廣告步驟的html基底包裝成javascript模板字串，由前端javascript插入html中。
$watch_game_ads_step_html = '`<div id="step_${WATCH_GAME_ADS_STEP_NUMBER}_1" class="d-grid form_group"><h1>在本週訓練開始前請您先觀看誘惑遊戲廣告</h1>'.
    // 額外新增此class用於略過影片的按鈕，因為需要覆蓋由按鈕父層繼承而來的pointer-events: none;屬性，但是直接寫於style中會連disabled屬性的pointer-events一同覆蓋，因此這邊另外新增一個class既可覆蓋父層繼承，但又小於disabled
    '<style type="text/css">.prevent_inherit_and_override_disabled_pointer_events_auto {pointer-events: auto;}</style>'.
    '<input type="hidden" name="answer_sheet[watch_game_ads][ad_1][game_name]" value=""><input type="hidden" name="answer_sheet[watch_game_ads][ad_1][ytv_id]" value="">'.
    '<input type="hidden" name="answer_sheet[watch_game_ads][ad_2][game_name]" value=""><input type="hidden" name="answer_sheet[watch_game_ads][ad_2][ytv_id]" value="">'.
    '<input type="hidden" name="answer_sheet[watch_game_ads][ad_3][game_name]" value=""><input type="hidden" name="answer_sheet[watch_game_ads][ad_3][ytv_id]" value="">'.
    '<h3>您常玩哪一種類型遊戲？請依排名勾選三種最常玩的或有興趣的遊戲類型。</h3></div>';
for ($gameads_html = 1 ; $gameads_html < 4 ; $gameads_html++) {
    // 第一個value是none的input為預設值
    $watch_game_ads_step_html .= '<div id="step_${WATCH_GAME_ADS_STEP_NUMBER}_2_' . $gameads_html . '" class="d-grid d-none form_group"><div class=""><div id="gamead_' . $gameads_html . '_player"></div></div><h5>請點擊影片以播放</h5><h3>請問您現在對玩遊戲的渴求有多強?</h3><input type="radio" class="d-none" name="answer_sheet[watch_game_ads][ad_' . $gameads_html . '][feel_level]" value="none" checked>';

    for ($eager_level_option = 1 ; $eager_level_option < (count(EAGER_LEVEL_CHINESE) + 1) ; $eager_level_option++) {
        $watch_game_ads_step_html .= '<div class="float-start"><input type="radio" id="gamead_q' . $gameads_html . '_' . $eager_level_option . '" class="form-check-input p-3 mx-3" name="answer_sheet[watch_game_ads][ad_' . $gameads_html . '][feel_level]" value="' . $eager_level_option . '" oninput="' . GAME_ADS_NEXT_BUTTON_ONINPUT_FUNCTION_NAME . '(event)" disabled required>
        <label for="gamead_q' . $gameads_html . '_' . $eager_level_option . '" class="mt-2 pe-5">' . EAGER_LEVEL_CHINESE[$eager_level_option - 1] . '</label></div>';
    }

    $watch_game_ads_step_html .= '<div class="d-grid pt-3 px-2"><button type="button" id="${watch_game_ads_next_btn_ids[' . ($gameads_html - 1) . ']}" class="btn btn-primary" onclick="forwardGameADSWatchStep(2, {next_inner_step : ' . ($gameads_html + 1) . '});" disabled>繼續(請先看完影片)</button></div>';

    $watch_game_ads_step_html .= '</div>';
}
$watch_game_ads_step_html .= '`';

$evaluate_watch_ads_final_step_html = '`<h1>請問您完成策略後對玩遊戲的渴求有多強?</h1>';
for ($eager_level_option = 1 ; $eager_level_option < (count(EAGER_LEVEL_CHINESE) + 1) ; $eager_level_option++) {
    $evaluate_watch_ads_final_step_html .= '<div class="float-start"><input type="radio" id="gamead_qfinal-evaluate_' . $eager_level_option . '" class="form-check-input p-3 mx-3" name="answer_sheet[watch_game_ads][final_evaluate][feel_level]" value="' . $eager_level_option . '" oninput="(this.parentNode.parentNode.querySelector(' . "'button'" . ')).removeAttribute(' . "'disabled'" . '); (this.parentNode.parentNode.querySelector(' . "'button'" . ')).innerHTML = ' . "'繼續'" . ';" required>
    <label for="gamead_qfinal-evaluate_' . $eager_level_option . '" class="mt-2 pe-5">' . EAGER_LEVEL_CHINESE[$eager_level_option - 1] . '</label></div>';
}
$evaluate_watch_ads_final_step_html .= '<div class="d-grid pt-3 px-2"><button type="button" class="btn btn-primary" onclick="gameADS_final_evaluate_step_end_callback();" disabled>繼續(請先選擇評估選項)</button></div>`';

define('TRAINING_STRATEGY_PRACTICE_JS',
    '<!-- Youtube Embeds IFrame Player API -->
    <script type="text/javascript" src="https://www.youtube.com/iframe_api"></script>
    <script type="text/javascript">
        const GAMES_LIB = ' . GAMES_LIB_JSON_ENCODE_STRING . ';
        const mask_svg_innerHTML = {};
        let videos_state;
        let selected_game_stack = [];
        let gameADS_watch_step_end_callback = null;
        let gameADS_final_evaluate_step_end_callback = null;
    
        window.addEventListener("load", () => {
            "use strict";
            Object.defineProperty(mask_svg_innerHTML, `${YT.PlayerState.UNSTARTED}`, {
                value : ((mask_width, mask_height) => {"use strict"; return ' . YOUTUBE_DYNAMIC_VIDEO_MASK_UNSTARTED_TEMPLATE . ';}),
                configurable : false,
                writable : false
            });
            Object.defineProperty(mask_svg_innerHTML, `${YT.PlayerState.PLAYING}`, {
                value : ((mask_width, mask_height) => {"use strict"; return ' . YOUTUBE_DYNAMIC_VIDEO_MASK_PLAYING_TEMPLATE . ';}),
                configurable : false,
                writable : false
            });
            Object.defineProperty(mask_svg_innerHTML, `${YT.PlayerState.ENDED}`, {
                value : ((mask_width, mask_height) => {"use strict"; return ' . YOUTUBE_DYNAMIC_VIDEO_MASK_ENDED_TEMPLATE . ';}),
                configurable : false,
                writable : false
            });
    
            videos_state = [`${YT.PlayerState.UNSTARTED}`, `${YT.PlayerState.UNSTARTED}`, `${YT.PlayerState.UNSTARTED}`];
        });
    
        function initGameADSWatchStepBase() {
            const watch_game_ads_next_btn_ids = ["watch_game_ads_next_btn_1", "watch_game_ads_next_btn_2", "watch_game_ads_next_btn_3"];
            document.querySelector(`#step_${WATCH_GAME_ADS_STEP_NUMBER}`).innerHTML = ' . $watch_game_ads_step_html . ';
        }
    
        function initGameADSWatchStep_1() {
            let all_options_html = "";
    
            GAMES_LIB.forEach((category, category_index) => {
                all_options_html += "<br><h3>" + category["category"] + "</h3>";
    
                category["games"].forEach((game, game_index) => {
                    const option_number = `${category_index}-${game_index}`;
                    const gameads_option_name = game["name_zh"];
                    all_options_html += ' . GAME_ADS_OPTION_HTML_TEMPLATE . ';
                });
            });
    
            document.querySelector(`#step_${WATCH_GAME_ADS_STEP_NUMBER}_1`).innerHTML += (all_options_html + `<br><br><div class="d-grid pt-3 px-2"><button type="button" id="gameads_option_next_btn" class="btn btn-primary" onclick="forwardGameADSWatchStep(2, {next_inner_step : 1});" disabled>繼續(請先選擇三種遊戲廣告)</button></div>`);
        }
    
        function initGameADSFinalEvaluateStep() {
            document.querySelector(`#step_${WATCH_GAME_ADS_FINAL_EVALUATE_STEP_NUMBER}`).innerHTML = ' . $evaluate_watch_ads_final_step_html .';
        }
    
        function startIntoGameADSWatchStep(end_callback) {
            gameADS_watch_step_end_callback = end_callback;
            forwardGameADSWatchStep(1);
        }
    
        function startIntoGameADSFinalEvaluateStep(end_callback) {
            gameADS_final_evaluate_step_end_callback = end_callback;
            
            (document.querySelectorAll(".form_group")).forEach((element) => {
                element.classList.add("d-none");
            });
            document.querySelector(`#step_${WATCH_GAME_ADS_FINAL_EVALUATE_STEP_NUMBER}`).classList.remove("d-none");
        }
    
        function forwardGameADSWatchStep(next_step_number, addition_content) {
            (document.querySelectorAll(".form_group")).forEach((element) => {
                element.classList.add("d-none");
            });
            document.querySelector(`#step_${WATCH_GAME_ADS_STEP_NUMBER}`).classList.remove("d-none");
    
            if (next_step_number == 1) {
                document.querySelector(`#step_${WATCH_GAME_ADS_STEP_NUMBER}_1`).classList.remove("d-none");
            } else if (next_step_number == 2) {
                if ((addition_content["next_inner_step"] > 0) && (addition_content["next_inner_step"] < 4)) {
                    if (addition_content["next_inner_step"] == 1) {
                        if (selected_game_stack.length == 3) {
                            generateGameADS();
                        }
                    } else {
                        // 觀看遊戲廣告後自我評估可求程度所選取的選項
                        if (document.querySelector(`input[id^="gamead_q${(addition_content["next_inner_step"] - 1)}_"]:checked`).value > 4) {
                            // 如果所選分數四分(包含)以上就不須再看下一個廣告，反之則需要。
                            gameADS_watch_step_end_callback();
                            return;
                        }
                    }
    
                    document.querySelector(`#step_${WATCH_GAME_ADS_STEP_NUMBER}_2_${addition_content["next_inner_step"]}`).classList.remove("d-none");
                } else if (addition_content["next_inner_step"] > 3) {
                    // callback 該步驟已結束
                    gameADS_watch_step_end_callback();
                    return;
                }
            } else {
                console.error("Error Step: %d", next_step_number);
                return false;
            }
        }
    
        function ' . GAME_ADS_OPTION_ONINPUT_FUNCTION_NAME . '(event) {
            if (event.srcElement.checked) {
                if (selected_game_stack.length < 3) {
                    const gameads_option_order = event.srcElement.parentNode.querySelector(`div[id^="${"gameads_option_"}"]`);
    
                    selected_game_stack.push(event.srcElement);
    
                    gameads_option_order.classList.remove("d-none");
                    gameads_option_order.innerHTML = selected_game_stack.length;
    
                    if (selected_game_stack.length == 3) {
                        const gameads_option_next_btn = document.querySelector("#gameads_option_next_btn");
    
                        document.querySelectorAll(`input[id^="${"gameads_option_"}"]`).forEach((element) => {
                            if(!(selected_game_stack.includes(element))) {
                                element.setAttribute("disabled", "");
                            }
                        });
                        gameads_option_next_btn.removeAttribute("disabled");
                        gameads_option_next_btn.innerHTML = "繼續";
                    }
                }
            } else {
                const temp_selected_game_stack = selected_game_stack;
                selected_game_stack = [];
                for (let element = 0 ; element < temp_selected_game_stack.length ; element++) {
                    const gameads_option_order = temp_selected_game_stack[element].parentNode.querySelector(`div[id^="${"gameads_option_"}"]`);
    
                    if (temp_selected_game_stack[element].id == event.srcElement.id) {
                        gameads_option_order.classList.add("d-none");
                        gameads_option_order.innerHTML = "";
                    } else {
                        selected_game_stack.push(temp_selected_game_stack[element]);
    
                        gameads_option_order.classList.remove("d-none");
                        gameads_option_order.innerHTML = selected_game_stack.length;
                    }
                }
    
                if (selected_game_stack.length < 3) {
                    const gameads_option_next_btn = document.querySelector("#gameads_option_next_btn");
    
                    (document.querySelectorAll(`input[id^="${"gameads_option_"}"]`)).forEach((element) => {
                        element.removeAttribute("disabled");
                    });
                    gameads_option_next_btn.setAttribute("disabled", "");
                    gameads_option_next_btn.innerHTML = "繼續(請先選擇三種遊戲廣告)";
                }
            }
        }

        function ' . GAME_ADS_NEXT_BUTTON_ONINPUT_FUNCTION_NAME . '(event) {
            const watch_game_ads_next_btn = event.srcElement.parentNode.parentNode.querySelector(`button[id^="watch_game_ads_next_btn_"]`);
            watch_game_ads_next_btn.removeAttribute("disabled");
            watch_game_ads_next_btn.innerHTML = "繼續";
        }
    
        function onGameADVideoStateChange(src_player) {
            if (src_player.data == YT.PlayerState.UNSTARTED) {
                const skip_button_backboard = src_player.target.getIframe().parentNode.querySelector(`div[id^="player_skip_button_backboard_"]`).childNodes[0];
                const mask = src_player.target.getIframe().parentNode.querySelector("svg");
                const shadow_iframe = src_player.target.getIframe().parentNode.querySelector(`iframe[id^="player_shadow_"]`);

                const video_count = ((src_player.target.getIframe().id).split("_"))[1] - 1;
                const skip_button_id = `player_skip_button_${video_count + 1}`;
    
                videos_state[video_count] = `${YT.PlayerState.PLAYING}`;
    
                shadow_iframe.classList.remove("d-none");
                shadow_iframe.contentWindow.addEventListener("resize", onPlayerShadowResize);

                mask.style.width  = "100%";
                mask.style.height = "100%";

                // 初始化略過按鈕
                skip_button_backboard.innerHTML = ' . YOUTUBE_VIDEO_SKIP_TRIGGER_TEMPLATE . ';
                onSkipVideoTimerTimeout(skip_button_backboard.childNodes[0], src_player.target);
            } else if (src_player.data == YT.PlayerState.ENDED) {
                const skip_button_backboard = src_player.target.getIframe().parentNode.querySelector(`div[id^="player_skip_button_backboard_"]`).childNodes[0].childNodes[0];
                const mask = src_player.target.getIframe().parentNode.querySelector("svg");
                const watch_game_ads_next_btn = src_player.target.getIframe().parentNode.parentNode.querySelector(`button[id^="watch_game_ads_next_btn_"]`);

                const video_count = ((src_player.target.getIframe().id).split("_"))[1] - 1;
                const mask_width  = src_player.target.getIframe().offsetWidth;
                const mask_height = src_player.target.getIframe().offsetHeight;

                // 移除略過按鈕
                skip_button_backboard.remove();

                videos_state[video_count] = `${YT.PlayerState.ENDED}`;
                mask.children[0].innerHTML = mask_svg_innerHTML[videos_state[video_count]](mask_width, mask_height);
    
                // 嵌入影片的外層div節點的再外層，包含該步驟的選項及按鈕
                (src_player.target.getIframe().parentNode.parentNode.querySelectorAll(`input[id^="${"gamead_q"}"]`)).forEach((input_element) => {
                    input_element.removeAttribute("disabled");
                });
                // watch_game_ads_next_btn.removeAttribute("disabled");      // 改由input的oninput事件解鎖
                watch_game_ads_next_btn.innerHTML = "繼續(請先選擇評估選項)";
            } else if (src_player.data == YT.PlayerState.PLAYING) {
                // 在這裡才調整大小是為了避免嵌入影片顯示「更多影片」選項
                src_player.target.getIframe().style.position   = "absolute";
                src_player.target.getIframe().style["z-index"] = "3";
    
                // 嵌入影片的外層div節點，用來調整影片至適合大小，且會隨螢幕大小改變
                src_player.target.getIframe().parentNode.classList.add("ratio");
                src_player.target.getIframe().parentNode.classList.add("ratio-16x9");
            }
        }
    
        function generateGameADS() {
            selected_game_stack.forEach((selected_gameAD, selected_index) => {
                const GAMES_LIB_mapping_indexes = (((selected_gameAD.id).split("_"))[2]).split("-");
                const skip_button_backboard_id = `player_skip_button_backboard_${selected_index + 1}`;
                const mask_id = `player_mask_${selected_index + 1}`;
                const shadow_id = `player_shadow_${selected_index + 1}`;
                const fragment = document.createDocumentFragment();
                const fragment_template = document.createElement("template");
    
                const player = new YT.Player(`gamead_${selected_index + 1}_player`, {
                    width      : "320",
                    height     : "180",
                    videoId    : GAMES_LIB[GAMES_LIB_mapping_indexes[0]]["games"][GAMES_LIB_mapping_indexes[1]]["ytv_id"],
                    playerVars : {
                        autoplay  : 0,
                        controls  : 0,
                        disablekb : 1,
                        fs        : 0,
                        rel       : 0
                    },
                    events     : {onStateChange : onGameADVideoStateChange}
                });
    
                document.querySelector(`input[name="answer_sheet[watch_game_ads][ad_${selected_index + 1}][game_name]"]`).value = GAMES_LIB[GAMES_LIB_mapping_indexes[0]]["games"][GAMES_LIB_mapping_indexes[1]]["name_zh"];
                document.querySelector(`input[name="answer_sheet[watch_game_ads][ad_${selected_index + 1}][ytv_id]"]`).value    = GAMES_LIB[GAMES_LIB_mapping_indexes[0]]["games"][GAMES_LIB_mapping_indexes[1]]["ytv_id"];
    
                // 以html字串建立mask並插入在影片iframe前
                fragment.appendChild(fragment_template);
                fragment_template.innerHTML += ' . YOUTUBE_DEFAULT_VIDEO_MASK . ';
                player.getIframe().parentNode.insertBefore((document.importNode(fragment_template.content, true)), player.getIframe());
            });
        }
        
        function onPlayerShadowResize(event) {
            const mask = event.srcElement.frameElement.parentNode.querySelector("svg");
            const video_count = ((mask.id).split("_"))[2] - 1;
            const mask_width  = event.srcElement.frameElement.offsetWidth;
            const mask_height = event.srcElement.frameElement.offsetHeight;

            mask.children[0].innerHTML = mask_svg_innerHTML[videos_state[video_count]](mask_width, mask_height);

            mask.setAttribute("viewBox", `0,0,${mask_width},${mask_height}`);
        }

        function onSkipVideoTimerTimeout(skip_trigger, target_video, time_left) {      // 略過影片計時器
            time_left = (typeof time_left === "undefined") ? ' . YOUTUBE_VIDEO_SKIP_WAIT_TIME . ' : time_left;

            if (time_left == 0) {
                if (!(document.querySelector(`#${skip_trigger.id}`) === null)) {      // 如果略過按鈕還沒被移出DOM(即沒被呼叫.remove())
                    skip_trigger.addEventListener("click", () => {
                        target_video.pauseVideo();
                        target_video.seekTo(65535, true);      // 因為影片長度未知所以直接以跳至大數取代
                    });

                    skip_trigger.removeAttribute("disabled");
                    skip_trigger.innerHTML = `<strong>略過</strong>`;
                    skip_trigger.classList.remove("d-none");
                }
            } else {
                if ((time_left < ' . YOUTUBE_VIDEO_SKIP_WAIT_TIME . ') && (time_left > ' . YOUTUBE_VIDEO_SKIP_FIRST_HIDE_TIME . ')) {
                    skip_trigger.innerHTML = `<strong>${(time_left / 1000)}&nbsp;秒後可略過</strong>`;
                    skip_trigger.classList.remove("d-none");
                } else if (time_left == ' . YOUTUBE_VIDEO_SKIP_FIRST_HIDE_TIME . ') {
                    skip_trigger.classList.add("d-none");
                } else if ((time_left <= ' . YOUTUBE_VIDEO_SKIP_AGAIN_SHOW_TIME . ') && (time_left > 0)) {
                    skip_trigger.innerHTML = `<strong>${(time_left / 1000)}&nbsp;秒後可略過</strong>`;
                    skip_trigger.classList.remove("d-none");
                }

                setTimeout(() => {onSkipVideoTimerTimeout(skip_trigger, target_video, (time_left - 1000));}, 1000);
            }
        }
    </script>'
);

if (key_exists('test', $_GET) && $_GET['test'] == 'training_strategy_apply_js') {
    // header("Content-Type: text/plain, charset=UTF-8");
    // YouTube Embed code
    // $youtube_embed_code = '<iframe width="480" height="240" src="https://www.youtube.com/embed/%1$s" title="Hearthstone - 超快節奏，簡單上手，樂趣無窮" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
    // Autoplay disabled
    $youtube_embed_code = '<iframe width="480" height="240" src="https://www.youtube.com/embed/%1$s" title="Hearthstone - 超快節奏，簡單上手，樂趣無窮" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
    echo '<h1>TEST MODE</h1><hr>';
    // 把所有影片叫出來 並在影片上面加上影片標題(H1)，每個影片下面要有<HR>分隔
    // print_r(json_decode(GAMES_LIB_JSON_ENCODE_STRING, true));
    foreach (json_decode(GAMES_LIB_JSON_ENCODE_STRING, true) as $category) {
        echo '<h1>' . $category['category'] . '</h1>';
        foreach ($category['games'] as $game) {
            echo '<h2>' . $game['name_zh'] . '</h2>';
            echo sprintf($youtube_embed_code, $game['ytv_id']);
        }
        echo '<hr>';
    }
}

?>