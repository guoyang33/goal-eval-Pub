<?php

use Cyouliao\Goaleval\User;

include_once './../vendor/autoload.php';

const POST_KEY = 'forgot';
const KEY_STD_ID = 'std_id';
const KEY_EXP_ID = 'exp_id';
const KEY_PASSWORD = 'password';
const KEY_PASSWORD_CONFIRM = 'password_confirm';

session_start();
$userId = $_SESSION['user']['id'] ?? null;

$loginStdId = $_POST['login']['std_id'] ?? null;
$loginPassword = $_POST['login']['password'] ?? null;
?>

<html lang="zh-Hant">
<head>

<?php
$stdID = $_POST[POST_KEY][KEY_STD_ID] ?? null;
$expID = $_POST[POST_KEY][KEY_EXP_ID] ?? null;
$password = $_POST[POST_KEY][KEY_PASSWORD] ?? null;
$passwordConfirm = $_POST[POST_KEY][KEY_PASSWORD_CONFIRM] ?? null;

if (key_exists(POST_KEY, $_POST)) {
    if (is_null($stdID) || is_null($expID) || is_null($password) || is_null($passwordConfirm)) {
        echo '<script>window.alert("所有欄位不得有空");</script>';

    } else {
        $user = User::getUserByStdId($stdID);

        if (is_null($user->id)) {
            echo '<script>window.alert("學號不存在，請詢問管理人員");</script>';

        } elseif ($expID != $user->expId) {
            echo '<script>window.alert("研究編號不匹配，請詢問管理人員");</script>';

        } elseif (strlen($password) < 8) {
            echo '<script>window.alert("密碼長度過短，請重新輸入");</script>';

        } elseif ($passwordConfirm != $password) {
            echo '<script>window.alert("兩次輸入的密碼不一制，請重新輸入");</script>';

        } else {
            try {
                $newPassword = password_hash($password, PASSWORD_DEFAULT);
                $user->password = $newPassword;
                $user->passwordHash = $newPassword;
    
                $updateSuccess = $user->update();
                if ($updateSuccess) {
                    echo '<script> window.alert("密碼變更成功，將重新導向登入畫面"); location.href="/login.php"; </script>';
    
                } else {
                    echo '<script>window.alert("密碼變更失敗，請詢問管理人員");</script>';
    
                }
            } catch (PDOException $e) {
                $errorMsg = $e->getMessage();
                echo '<script>window.alert("密碼變更失敗，請詢問管理人員\n' . $errorMsg . '");</script>';
                
            } catch (Exception $e) {
                $errorMsg = $e->getMessage();
                echo '<script>window.alert("密碼變更失敗，請詢問管理人員\n' . $errorMsg . '");</script>';

            }

        }

    }
}

?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="CYou Liao">
    <meta name="generator" content="Hugo 0.84.0">
    <title>健康上網APP</title>
    
    <!-- <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/"> -->
    
    
    
    <!-- Bootstrap core CSS -->
    <link href="/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <!-- Favicons -->
    <!-- <link rel="apple-touch-icon" href="/docs/5.0/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="/docs/5.0/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="/docs/5.0/assets/img/favicons/safari-pinned-tab.svg" color="#7952b3">
    <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#7952b3"> -->
    
    
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }
        
        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    
    
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="/dist/example/sign-in/signin.css">
    <!-- <link href="/examples/sign-in/signin.css" rel="stylesheet"> -->
</head>
<body class="text-center">
    
    <main class="form-signin">
        <header class="pb-3 mb-4 border-bottom">
            <a href="/" class="d-flex align-items-center text-dark text-decoration-none">
                <!-- <svg xmlns="http://www.w3.org/2000/svg" width="40" height="32" class="me-2" viewBox="0 0 118 94" role="img"><title>Bootstrap</title><path fill-rule="evenodd" clip-rule="evenodd" d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508zM80 57.863C80 66.663 73.436 72 62.543 72H44a2 2 0 01-2-2V24a2 2 0 012-2h18.437c9.083 0 15.044 4.92 15.044 12.474 0 5.302-4.01 10.049-9.119 10.88v.277C75.317 46.394 80 51.21 80 57.863zM60.521 28.34H49.948v14.934h8.905c6.884 0 10.68-2.772 10.68-7.727 0-4.643-3.264-7.207-9.012-7.207zM49.948 49.2v16.458H60.91c7.167 0 10.964-2.876 10.964-8.281 0-5.406-3.903-8.178-11.425-8.178H49.948z" fill="currentColor"></path></svg> -->
                <span class="fs-4">健康上網</span>
            </a>
        </header>
        <form method="post">
            <!-- <img class="mb-4" src="/docs/5.0/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57"> -->
            <h1 class="h3 mb-3 fw-normal">重設密碼</h1>
            
            <div class="form-floating">
                <input type="text" class="form-control" id="floatingInputStdId" name="forgot[std_id]" placeholder="" value="<?php echo $stdID; ?>" required>
                <label for="floatingInputStdId">學號</label>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control" id="floatingInputStdId" name="forgot[exp_id]" placeholder="" value="<?php echo $expID; ?>" required>
                <label for="floatingInputStdId">研究編號</label>
            </div>
            <p class="text-danger">若您不確定自己的研究編號，請向管理人員詢問</p>
            <p class="text-danger mt-2">密碼長度請設置8以上</p>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" name="forgot[password]" placeholder="Password" required>
                <label for="floatingPassword">密碼</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" name="forgot[password_confirm]" placeholder="Password" required>
                <label for="floatingPassword">再次輸入密碼</label>
            </div>
            
            <button class="w-100 btn btn-lg btn-primary" type="submit">送出</button>

            <p class="mt-5 mb-3 text-muted">&copy; 2024 CYou Liao</p>
        </form>
    </main>
    
    
    
</body>
</html>
