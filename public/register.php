<?php
include_once './../vendor/autoload.php';
use Cyouliao\Goaleval\DBConnect;
use Cyouliao\Goaleval\User;
use Cyouliao\Goaleval\ValidDays;

const SOURCE_FILE = '/register.php';

session_start();

$stdId = $_POST['register']['std_id'] ?? null;
$expId = $_POST['register']['exp_id'] ?? null;
$password = $_POST['register']['password'] ?? null;
$passwordConfirm = $_POST['register']['password_confirm'] ?? null;
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
<title>健康上網APP</title>

<!-- <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/"> -->



<!-- Bootstrap core CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- <link href="/docs/5.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->

<!-- Favicons -->
<!-- <link rel="apple-touch-icon" href="/docs/5.0/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
<link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
<link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
<link rel="manifest" href="/docs/5.0/assets/img/favicons/manifest.json">
<link rel="mask-icon" href="/docs/5.0/assets/img/favicons/safari-pinned-tab.svg" color="#7952b3">
<link rel="icon" href="/docs/5.0/assets/img/favicons/favicon.ico"> -->
<meta name="theme-color" content="#7952b3">


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
<link href="https://getbootstrap.com/docs/5.0/examples/sign-in/signin.css" rel="stylesheet">
<!-- <link href="signin.css" rel="stylesheet"> -->
</head>

<!------PHP code to check if the user is already logged in or not------>
<?php if (isset($_SESSION['user_id'])) { ?>
    <script type="text/javascript">
    window.alert("您已經登入囉！將導向至計畫主頁面");
    window.location.href = "redirect.php";
    </script>
<?php } ?>

<?php
if (key_exists('register', $_POST)) {
    $user = User::getUserByStdId($stdId);
    if (is_null($user->id)) {
?>
    <script type="text/javascript">
    window.alert("找不到此學號，請重新輸入");
    </script>
<?php
    } else if ($expId != $user->expId) {
?>
    <script type="text/javascript">
    window.alert("研究編號錯誤，請重新輸入");
    </script>
<?php
    } else if (strlen($password) < 8) {
?>
    <script type="text/javascript">
    window.alert("密碼長度不足，請重新輸入");
    </script>
<?php
    } else if ($password != $passwordConfirm) {
?>
    <script type="text/javascript">
    window.alert("密碼不一致，請重新輸入");
    </script>
<?php
    } else {
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->update();
        $validDaysUserSourceFiles = ValidDays::getSourceFilesByUserId($user->id);
        if (!in_array(SOURCE_FILE, $validDaysUserSourceFiles)) {
            $validDays = new ValidDays();
            $validDays->userId = $user->id;
            $validDays->provideDays = 8;
            $validDays->sourceFile = SOURCE_FILE;
            $validDays->sourceObject = '{"REASON":"PROVIDE_BEGINNING_PROGRESS", "THROUGH":"register.php"}';
            $validDays->week = 0;
            $validDays->date = date('Y-m-d');
            $validDays->insert();
        }
?>
    <script type="text/javascript">
    window.alert("註冊成功，將導向至登入頁面");
    window.location.href = "/login.php";
    </script>
<?php
    }
}
?>
<!------PHP code to check if the user is already logged in or not------>

<body class="text-center">

<main class="form-signin xl-col-3">
<header class="pb-3 mb-4 border-bottom">
<a href="/" class="d-flex align-items-center text-dark text-decoration-none">
<!-- <svg xmlns="http://www.w3.org/2000/svg" width="40" height="32" class="me-2" viewBox="0 0 118 94" role="img"><title>Bootstrap</title><path fill-rule="evenodd" clip-rule="evenodd" d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508zM80 57.863C80 66.663 73.436 72 62.543 72H44a2 2 0 01-2-2V24a2 2 0 012-2h18.437c9.083 0 15.044 4.92 15.044 12.474 0 5.302-4.01 10.049-9.119 10.88v.277C75.317 46.394 80 51.21 80 57.863zM60.521 28.34H49.948v14.934h8.905c6.884 0 10.68-2.772 10.68-7.727 0-4.643-3.264-7.207-9.012-7.207zM49.948 49.2v16.458H60.91c7.167 0 10.964-2.876 10.964-8.281 0-5.406-3.903-8.178-11.425-8.178H49.948z" fill="currentColor"></path></svg> -->
<span class="fs-4">健康上網</span>
</a>
</header>
<form method="post">
<!-- <img class="mb-4" src="/docs/5.0/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57"> -->
<h1 class="h3 mb-3 fw-normal">計畫參與者註冊</h1>
<p>親愛的計畫參與者您好，請輸入您的學號以進行註冊。</p>

<div class="form-floating">
    <input type="text" class="form-control" id="floatingInputStdId" name="register[std_id]" value="<?php echo $stdId; ?>" placeholder="" required>
    <label for="floatingInputStdId">學號</label>
</div>

<div class="form-floating mt-2">
    <input type="text" class="form-control" id="floatingInputExpId" name="register[exp_id]" value="<?php echo $expId; ?>" placeholder="" required>
    <label for="floatingInputExpId">研究編號</label>
</div>

<div class="form-floating mt-2">
    <input type="password" class="form-control" id="floatingPassword" name="register[password]" placeholder="Password" required>
    <label for="floatingPassword">密碼 (長度至少8個字元)</label>
</div>
<div class="form-floating mt-2">
    <input type="password" class="form-control" id="floatingPasswordConfirm" name="register[password_confirm]" placeholder="Password" required>
    <label for="floatingPasswordConfirm">再次輸入密碼</label>
</div>

<!-- <div class="checkbox mb-3"> -->
<!-- <label> -->
<!-- <input type="checkbox" value="remember-me"> Remember me -->
<!-- </label> -->
<!-- </div> -->
<button class="w-100 btn btn-lg btn-primary mt-3" type="submit">註冊</button>
<p class="mt-5 mb-3 text-muted">&copy; 2024 CYou Liao</p>
</form>
</main>
</body>
</html>
