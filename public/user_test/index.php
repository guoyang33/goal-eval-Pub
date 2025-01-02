<?php
/*
 * index.php
 * 使用者測試頁面
 * 可以直接輸入ID新增使用者帳號
 * 可直接對特定ID進行使用者進程的操作
 */
?>
<!-- bootstrap template 帶一個input 的表單 -->
<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>使用者測試頁面</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <!-- Bootstrap5 -->
    <!-- MaxCDN -->
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>使用者測試頁面</h1>
        <form action="query.php" method="post" class="form-inline"><!-- bootstrap template -->
            <h2 class="mt-3">使用研究者帳號登入</h2>
            <table>
                <tr>
                    <td>
                        <input type="text" class="form-control" name="exp_id" placeholder="請輸入研究編號" autofocus>
                    </td>
                    <td>
                        <span>
                            <label for="btn_login" class="btn btn-outline-primary">登入</label>
                            <input type="submit" id="btn_login" class="btn-check d-none" name="login_type" value="login">
                        </span>
                    </td>
                </tr>
            </table>
            <hr>
            <h2 class="mt-3">建立研究編號</h2>
            <table>
                <tr>
                    <th>手機類別</th>
                    <th>實驗組別</th>
                </tr>
                <tr>
                    <td>
                        <select name="device" class="form-control">
                            <option value="ios">蘋果(I:iOS)</option>
                            <option value="android">安卓(A:Android)</option>
                        </select>
                    </td>
                    <td>
                        <select name="exp_type" class="form-control">
                            <option value="training_strategy">策略(1)</option>
                            <option value="set_goal">目標(2)</option>
                        </select>
                    </td>
                    <td>
                        <span>
                            <label for="btn_create" class="btn btn-outline-success">建立</label>
                            <input type="submit" id="btn_create" class="btn-check d-none" name="login_type" value="create">
                        </span>
                    </td>
                </tr>
            </table>
        </form>
        <hr>
        <?php
        require_once '../connect_db.php';
        $sth = $dbh->prepare("SELECT * FROM `user` WHERE `test` = 1 ORDER BY `exp_id` ASC;");
        $sth->execute();
        $users = $sth->fetchAll(PDO::FETCH_ASSOC);
        if (count($users) > 0) {
            echo '
            <div class="d-grid pt-5 pb-3 px-2">
                <h2>已建立的研究編號</h2>
                <table class="table table-bordered">
                    <tr>
                        <th>研究編號</th>
                    </tr>
            ';
            foreach ($users as $user) {
                echo '
                    <tr>
                        <td>' . $user['exp_id'] . '</td>
                        <td><a href="main_panel.php?u_id=' . $user['id'] . '" class="btn btn-outline-primary">功能測試</a></td>
                    </tr>
                ';
            }
            echo '
            </table>
            </div>    
            ';
        }
        ?>
    </div>
</body>
</html>