<?php
include_once './../../vendor/autoload.php';
use Cyouliao\Goaleval\User;

session_start();
$adminLogin = $_SESSION['admin_login'] ?? null;

const FILTER_KEY = 'user_list';
const FILTER_IS_IOS = 'is_ios';
const FILTER_EXP_TYPE = 'exp_type';

$filterIsIOS    = $_COOKIE[FILTER_KEY][FILTER_IS_IOS] ?? User::IS_IOS_IOS;
$filterExpType  = $_COOKIE[FILTER_KEY][FILTER_EXP_TYPE] ?? User::EXP_TYPE_SET_GOAL;

$users = User::getAllUsers($filterIsIOS, $filterExpType);
?>
<html lang="zh-Hant">
    <head>
        <script type="text/javascript">
            <?php if ($adminLogin != 1) { ?>
                window.alert("您尚未登入，將導向至登入頁面");
                window.location.href = "/admin/login.php";
            <?php } ?>
        </script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="CYou Liao">
        <meta name="generator" content="Hugo 0.84.0">
        <title>參與者列表</title>

        <!-- Bootstrap core CSS -->
        <link href="./../dist/css/bootstrap.min.css" rel="stylesheet">
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

    </head>
    <body>
        <div class="container">
            <header class="pb-3 mb-4 border-bottom">
                <a href="/admin" class="d-flex align-items-center text-dark text-decoration-none">
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="40" height="32" class="me-2" viewBox="0 0 118 94" role="img"><title>Bootstrap</title><path fill-rule="evenodd" clip-rule="evenodd" d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508zM80 57.863C80 66.663 73.436 72 62.543 72H44a2 2 0 01-2-2V24a2 2 0 012-2h18.437c9.083 0 15.044 4.92 15.044 12.474 0 5.302-4.01 10.049-9.119 10.88v.277C75.317 46.394 80 51.21 80 57.863zM60.521 28.34H49.948v14.934h8.905c6.884 0 10.68-2.772 10.68-7.727 0-4.643-3.264-7.207-9.012-7.207zM49.948 49.2v16.458H60.91c7.167 0 10.964-2.876 10.964-8.281 0-5.406-3.903-8.178-11.425-8.178H49.948z" fill="currentColor"></path></svg> -->
                    <span class="fs-4">管理員系統</span>
                </a>
            </header>
            
            <main>
                <div class="py-5">
                    <h2>參與者列表</h2>
                    <form action="/admin/user_list_filter.php" method="post">
                        <div class="row">
                            <p class="col">篩選條件：</p>
                            <div class="my-3 col">
                                <div class="form-check col">
                                    <input id="is-ios-ios" name="<?php echo FILTER_IS_IOS; ?>" type="radio" class="form-check-input" value="<?php echo User::IS_IOS_IOS; ?>"<?php if ($filterIsIOS == User::IS_IOS_IOS) { ?> checked<?php } ?>>
                                    <label class="form-check-label" for="is-ios-ios">蘋果組</label>
                                </div>
                                <div class="form-check col">
                                    <input id="is-ios-android" name="<?php echo FILTER_IS_IOS; ?>" type="radio" class="form-check-input" value="<?php echo User::IS_IOS_ANDROID; ?>"<?php if ($filterIsIOS == User::IS_IOS_ANDROID) { ?> checked<?php } ?>>
                                    <label class="form-check-label" for="is-ios-android">安卓組</label>
                                </div>
                            </div>

                            <div class="my-3 col">
                                <div class="form-check">
                                    <input id="exp-type-set-goal" name="<?php echo FILTER_EXP_TYPE; ?>" type="radio" class="form-check-input" value="<?php echo User::EXP_TYPE_SET_GOAL; ?>"<?php if ($filterExpType == User::EXP_TYPE_SET_GOAL) { ?> checked<?php } ?>>
                                    <label class="form-check-label" for="exp-type-set-goal">目標組</label>
                                </div>
                                <div class="form-check">
                                    <input id="exp-type-training-strategy" name="<?php echo FILTER_EXP_TYPE; ?>" type="radio" class="form-check-input" value="<?php echo User::EXP_TYPE_TRAINING_STRATEGY; ?>"<?php if ($filterExpType == User::EXP_TYPE_TRAINING_STRATEGY) { ?> checked<?php } ?>>
                                    <label class="form-check-label" for="exp-type-training-strategy">策略組</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">篩選</button>
                        </div>
                    </form>

                    <div>
                        <div class="row bg-info">
                            <div class="col">學號</div>
                            <div class="col">研究編號</div>
                            <div class="col d-none d-sm-block">成癮組別</div>
                            <div class="col">研究組別</div>
                            <div class="col">註冊狀態</div>
                        </div>
                        <?php foreach ($users as $user) { ?>
                            <div class="row">
                                <div class="col"><?php echo $user->stdId; ?></div>
                                <div class="col"><?php echo $user->expId; ?></div>
                                <div class="col d-none d-sm-block"><?php echo ($user->addiction == 1) ? '成癮組' : '非成癮組'; ?></div>
                                <div class="col"><?php echo ($user->expType == User::EXP_TYPE_SET_GOAL) ? '目標組' : '策略組'; ?></div>
                                <div class="col"><?php echo ($user->password) ? '已註冊' : '未註冊'; ?></div>
                            </div>
                        <?php } ?>
                    </div>

                </div>
            </main>
                
                <footer class="my-5 pt-5 text-muted text-center text-small">
                    <p class="mb-1">&copy; 2024 CYou Liao</p>
                </footer>
            </div>

        <script src="./../dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>

</html>