<?php
/**
 * 第7週 第一次更新版本 新增
 * 高級權限 選項
 */
require_once './../html_head.php';



define('SUPER_USER_TOKEN', 'superpermissionstoken');

// 驗證
if (array_key_exists('confirm_data', $_POST)) {
    if ($_POST['confirm_data']['super_user_token'] != SUPER_USER_TOKEN) {
        echo '您好像來錯地方囉，請聯絡相關人員<br>如果您是管理人員請重新登入';
        exit();
    }
} else {
    echo '您好像來錯地方囉，請聯絡相關人員<br>如果您是管理人員請重新登入';
    exit();
}

if (array_key_exists('menu_data', $_POST)) {
    $next_page_title = '';
    $next_page_with_dir = '';
    $next_page = '';
    switch ($_POST['menu_data']['selected_option']) {
        case 'progress_overview':
            $next_page_title = 'progress_overview';
            $next_page_with_dir = 'progress_overview/progress_overview.php';
            $next_page = 'progress_overview.php';
            break;
        case 'user_passport':
            $next_page_title = 'user_passport';
            $next_page_with_dir = 'user_passport/user_passport.php';
            $next_page = 'user_passport.php';
            break;
        default:
            echo '<div class="d-grid pt-5 pb-3 px-2">
                <h2 class="text-danger">管理人員頁面選項錯誤，請聯絡相關人員</h2>
                <p>menu_data[selected_option]: ';
            var_dump($_POST['menu_data']['selected_option']);
            echo '</p></div>';
    }

    echo '<script type="text/javascript">
            window.addEventListener("load", () => {
                window.history.replaceState({}, "' . $next_page_title . '", "' . $next_page_with_dir . '");
                document.querySelector("form").submit();
            });
        </script>
        <form action="' . $next_page . '" method="POST"><input type="hidden" name="confirm_data[super_user_token]" value="' . SUPER_USER_TOKEN . '"></form>
        <h1>登入中請稍候...</h1>
    </div>
    </body>
    </html>';
} else {
    echo '<script type="text/javascript">function formSubmit() {window.history.replaceState({}, document.title, "' . basename(__FILE__) . '");}</script>
        <form action="' . basename(__FILE__) . '" method="POST" onsubmit="formSubmit();">
            <input type="hidden" name="confirm_data[super_user_token]" value="' . SUPER_USER_TOKEN . '">
            <h1 class="d-flex justify-content-center">請選擇要查看的內容</h1>
            <div class="d-flex justify-content-center">
                <span>
                    <label for="option_progress_overview" class="btn btn-outline-primary">受試者填答進度查看</label>
                    <input type="submit" id="option_progress_overview" class="btn-check d-none" name="menu_data[selected_option]" value="progress_overview">
                </span>
                &nbsp;&nbsp;&nbsp;
                <span>
                    <label for="option_user_passport" class="btn btn-outline-primary">研究編號開通</label>
                    <input type="submit" id="option_user_passport" class="btn-check d-none" name="menu_data[selected_option]" value="user_passport">
                </span>
                &nbsp;&nbsp;&nbsp;
                <span>
                    <!--
                    <label for="option_form_exporter" class="btn btn-outline-primary">表單填寫內容</label>
                    <input type="submit" id="option_form_exporter" class="btn-check d-none" name="" value="" disabled>
                    -->
                </span>
            </div>
        </form>
    </div>
    </body>
    </html>';
}
?>