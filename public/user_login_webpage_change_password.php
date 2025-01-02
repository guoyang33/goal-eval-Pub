<?php
require_once 'html_head.php';
?>
        <h1>您目前的密碼為預設密碼(<?php echo $password; ?>)，請<span class="text-danger">務必</span>改成新的密碼</h1>
        <form action="user_login_webpage.php" method="POST">
            <input type="hidden" name="exp_id" value="<?php echo $exp_id; ?>">
            <input type="hidden" name="password" value="<?php echo $password; ?>">
            <input type="hidden" name="change_password" value="1">

            <h3>你的編號為<?php echo $exp_id; ?>，請設定新密碼</h3><br>
            <table border="0">
                <tr>
                    <td><label for="new_password">新密碼：</label></td>
                    <td><input type="password" id="new_password" name="new_password" required></td>
                </tr>
                <tr>
                    <td><label for="check_password">確認新密碼：</label></td>
                    <td><input type="password" id="check_password" name="check_password" required></td>
                </tr>
                <tr>
                    <td colspan="2"><h3><strong class="text-danger">請務必記住您的新密碼！</strong><h3></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="修改密碼"></td>
                </tr>
            </table>
            
            <!-- <input type="hidden" name="exp_id" value="<?php echo $exp_id; ?>">
            <input type="hidden" name="password" value="<?php echo $password; ?>">

            <h3>你的編號為<?php echo $exp_id; ?>，請設定新密碼</h3><br>
            <label for="new_password">&emsp;&emsp;&emsp;新密碼：</label>
            <input type="password" id="new_password" name="new_password" required><br>
            <label for="check_password">確認新密碼：</label>
            <input type="password" id="check_password" name="check_password" required><br>
            <h3><strong class="text-danger">請務必記住您的新密碼！</strong><h3>
            <input type="submit" value="修改密碼"> -->
        </form>
    </div>
</body>
</html>