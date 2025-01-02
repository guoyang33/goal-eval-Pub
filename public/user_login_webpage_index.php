<?php
require_once 'html_head.php';
?>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }

            #main_form {
                text-align: center;
                padding: 0 20px;
            }

            #title {
                text-align: center;
                margin: 30px 0;
            }

            #main_img {
                width: 400px;
                display: block;
                margin: 0 auto;
            }

            #password_label {
                margin-top: 10px;
                text-align: right;
            }

            #submit_input {
                display: block;
                margin-top: 20px;
                padding: 10px 20px;
                text-align: center;
                margin: 0 auto;
            }
        </style>
        <form id="main_form" action="user_login_webpage.php" method="POST">
            <h1 id="title"><strong>請登入您的帳號</strong></h1>
            <div>
                <img id="main_img" src="img/user_login_webpage_index_main_img.gif">
            </div>
            <br><br>
            <label for="exp_id">編號：</label>
            <input type="text" id="exp_id" name="exp_id" placeholder="請輸入編號" required>
            <label id="password_label" for="password">密碼：</label>
            <input type="password" id="password" name="password" placeholder="請輸入密碼" required>
            <br><br>
            <input type="submit" id="submit_input" value="登入">
        </form>
    </div>
</body>
</html>