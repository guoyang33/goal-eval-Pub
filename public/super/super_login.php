<?php
/**
 * 第7週 第一次更新版本 新增
 * 高級權限 登入
 */
require_once './../html_head.php';



define('SUPER_USER_PASSWORD', 'superuserpassword');
define('SUPER_USER_TOKEN', 'superpermissionstoken');

if (array_key_exists('super_login_data', $_POST)) {
    /*  方法測試: 將頁面導到上一頁，即以清除上一頁的方式，避免使用者按上一頁。————測試結果：暫時無效
    if ($_POST['super_login_data']['password'] === 'abcdcba') {
        // header("Location: super_menu.php");
        echo '<script type="text/javascript">
            window.addEventListener("load", () => {
                history.go(-2);
                window.location.replace("super_menu.php");
            });
        </script>
        <h1>登入中請稍候...</h1>';
    } else {
        echo '<script type="text/javascript">
            window.addEventListener("load", () => {
                document.querySelector("button").addEventListener("click", () => {
                    history.go(-2);
                    window.location.replace("' . basename(__FILE__) . '");
                });
            });
        </script>
        <h1>密碼錯誤，請回到登入頁面再次檢查</h1>
        <button type="button" class="btn btn-primary">返回登入畫面</button>';
    }
    */
    if ($_POST['super_login_data']['password'] === SUPER_USER_PASSWORD) {
        echo '<script type="text/javascript">
                window.addEventListener("load", () => {
                    window.history.replaceState({}, "super_menu", "super_menu.php");
                    document.querySelector("form").submit();
                });
            </script>
            <form action="super_menu.php" method="POST"><input type="hidden" name="confirm_data[super_user_token]" value="' . SUPER_USER_TOKEN . '"></form>
            <h1>登入中請稍候...</h1>
        </div>
        </body>
        </html>';
    } else {
        echo '<script type="text/javascript">
                window.addEventListener("load", () => {document.querySelector("button").addEventListener("click", () => {window.location.replace("' . basename(__FILE__) . '");});});
            </script>
            <h1>密碼錯誤，請回到登入頁面再次檢查</h1>
            <button type="button" class="btn btn-primary">返回登入畫面</button>
        </div>
        </body>
        </html>';
    }
} else {
    echo '<script type="text/javascript">/* [註1] */ function formSubmit() {window.history.replaceState({}, document.title, "' . basename(__FILE__) . '");}</script>
        <h1>健康上網APP&nbsp;管理人員&nbsp;網頁版</h1>
        <form action="' . basename(__FILE__) . '" method="POST" onsubmit="formSubmit();">
            <label for="password">密碼</label>
            <input type="password" id="password" name="super_login_data[password]" required><br>
            <input type="submit" value="登入">
        </form>
    </div>
    </body>
    </html>';
}
/**
 * [註1] : replace調原本頁面避免重複提交，類似window.location.replace()
 */
?>