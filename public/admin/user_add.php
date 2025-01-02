<?php

use Cyouliao\Goaleval\User;

include_once './../../vendor/autoload.php';

session_start();
$adminLogin = $_SESSION['admin_login'] ?? null;

$stdId = $_POST['user_add']['std_id'] ?? null;
$expId = $_POST['user_add']['exp_id'] ?? null;
?>

<html lang="zh-Hant">
<head>

<script type="text/javascript">
    <?php if ($adminLogin != 1) { ?>
        window.alert("您尚未登入，將導向至登入頁面");
        window.location.href = "/admin/login.php";
    <?php } ?>

    <?php
    if (key_exists('user_add', $_POST)) {
        $user = new User();
        $user->stdId = $stdId;
        $user->expId = $expId;
        if (!$user->checkStdId()) {
    ?>
            window.alert("學號格式錯誤，請重新輸入");
    <?php
        } else if (!$user->checkExpId()) {
    ?>
            window.alert("研究編號格式錯誤，請重新輸入");
    <?php
        } else if (User::isStdIDExist($stdId)) {
    ?>
            window.alert("輸入的學號重複，請重新輸入");
    <?php

        } else if (User::isExpIDExist($expId)) {
    ?>
            window.alert("輸入的研究編號重複，請重新輸入");
    <?php

        } else {
            if (User::add($stdId, $expId)) {
                $stdId = null;
                $expId = null;
                ?>
                window.alert("新增成功");
                <?php
            } else {
                ?>
                window.alert("新增失敗，請通知管理員");
                <?php
            }
        }
    }
    ?>
</script>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="CYou Liao">
<meta name="generator" content="Hugo 0.84.0">
<title>健康上網APP 新增參與者資料</title>

<link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/checkout/">



<!-- Bootstrap core CSS -->
<link href="https://getbootstrap.com/docs/5.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Favicons -->
<!-- <link rel="apple-touch-icon" href="/docs/5.0/assets/img/favicons/apple-touch-icon.png" sizes="180x180"> -->
<!-- <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png"> -->
<!-- <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png"> -->
<!-- <link rel="manifest" href="/docs/5.0/assets/img/favicons/manifest.json"> -->
<!-- <link rel="mask-icon" href="/docs/5.0/assets/img/favicons/safari-pinned-tab.svg" color="#7952b3"> -->
<!-- <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon.ico"> -->
<!-- <meta name="theme-color" content="#7952b3"> -->


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
<link href="https://getbootstrap.com/docs/5.0/examples/checkout/form-validation.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container">
<main>

<header class="pb-3 mb-4 border-bottom">
<a href="/admin" class="d-flex align-items-center text-dark text-decoration-none">
<!-- <svg xmlns="http://www.w3.org/2000/svg" width="40" height="32" class="me-2" viewBox="0 0 118 94" role="img"><title>Bootstrap</title><path fill-rule="evenodd" clip-rule="evenodd" d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508zM80 57.863C80 66.663 73.436 72 62.543 72H44a2 2 0 01-2-2V24a2 2 0 012-2h18.437c9.083 0 15.044 4.92 15.044 12.474 0 5.302-4.01 10.049-9.119 10.88v.277C75.317 46.394 80 51.21 80 57.863zM60.521 28.34H49.948v14.934h8.905c6.884 0 10.68-2.772 10.68-7.727 0-4.643-3.264-7.207-9.012-7.207zM49.948 49.2v16.458H60.91c7.167 0 10.964-2.876 10.964-8.281 0-5.406-3.903-8.178-11.425-8.178H49.948z" fill="currentColor"></path></svg> -->
<span class="fs-4">管理員系統</span>
</a>
</header>

<div class="py-5 text-center">
<!-- <img class="d-block mx-auto mb-4" src="/docs/5.0/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57"> -->
<h2>新增參與者</h2>
<p class="lead">輸入學號以及研究編號以建立參與者資料</p>
<p>研究編號編碼格式:{手機類別}{學年}{成癮組別}{研究組別}{流水號}</p>
<ul class="text-start">
    <li>手機類別：一個英文字母<span class="text-danger">(大寫)</span> (A:Android組 | I:iOS組) </li>
    <li>學年：三位數(113:2024年)</li>
    <li>成癮組別：一位數 (1:成癮組 | 2:非成癮組)</li>
    <li>研究組別：一位數 (1:對照組(目標設定) | 2:調控組(策略+目標))</li>
    <li>流水號：二位數 (流水號小於10前面要有0，如：01、05、09...)</li>
</ul>

<div class="text-start">
    <p>範例（2024-10-21編）：</p>
    <table class="table">
        <tr>
            <td>A1131100</td>
            <td>Android組，本學年(113)，成癮組，策略+目標，0號</td>
        </tr>
        <tr>
            <td>A113110<span class="text-danger">5</span></td>
            <td>Android組，本學年(113)，成癮組，策略+目標，<span class="text-danger">5</span>號</td>
        </tr>
        <tr>
            <td><span class="text-danger">I</span>1131100</td>
            <td><span class="text-danger">iOS</span>組，本學年(113)，成癮組，策略+目標，0號</td>
        </tr>
        <tr>
            <td>I113<span class="text-danger">2</span>199</td>
            <td>iOS組，本學年(113)，<span class="text-danger">非成癮</span>組，策略+目標，99號</td>
        </tr>
        <tr>
            <td>I1132<span class="text-danger">2</span>48</td>
            <td>iOS組，本學年(113)，非成癮組，<span class="text-danger">目標設定</span></>，48號</td>
        </tr>
    </table>
</div>

</div>

<div class="row g-5">
<div class="col">
<!-- <h4 class="mb-3">Billing address</h4> -->
<form class="needs-validation" method="post" novalidate>

<div class="col-12">
<label for="std-id" class="form-label">學號</label>
<input type="text" class="form-control" id="std-id" name="user_add[std_id]" value="<?php echo $stdId; ?>" placeholder="學號" required>
<div class="invalid-feedback">
請確認學號格式是否正確
</div>
</div>

<div class="col-12">
<label for="exp-id" class="form-label">研究編號</label>
<input type="text" class="form-control" id="exp-id" name="user_add[exp_id]" value="<?php echo $expId; ?>" placeholder="研究編號" required>
</div>


<hr class="my-4">

<button class="w-100 btn btn-primary btn-lg" type="submit">新增</button>
</form>
</div>
</div>
</main>

<footer class="my-5 pt-5 text-muted text-center text-small">
<p class="mb-1">&copy; 2024 CYou Liao</p>
</footer>
</div>


<script src="https://getbootstrap.com/docs/5.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script src="https://getbootstrap.com/docs/5.0/examples/checkout/form-validation.js"></script>
</body>
</html>