<?php

use Cyouliao\Goaleval\Log;

include_once './../vendor/autoload.php';

Log::greeting();
Log::d('is verbose mode: ' . var_export(Log::isVerboseMode(), true));
?>
<html lang="zh-Hant">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="CYou Liao">
    <meta name="generator" content="Hugo 0.84.0">
    <title>健康上網APP</title>
    
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/jumbotron/">
    
    
    
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="/docs/5.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    
    <!-- Favicons -->
    <link rel="manifest" href="/assets/img/favicons/manifest.json">
    <link rel="icon" href="/assets/img/favicons/favicon.ico" sizes="any">
    <link rel="apple-touch-icon" href="/assets/img/favicons/apple-touch-icon.png">
    
    
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
    <main>
        <div class="container py-4">
            <header class="pb-3 mb-4 border-bottom">
                <a href="/" class="d-flex align-items-center text-dark text-decoration-none">
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="40" height="32" class="me-2" viewBox="0 0 118 94" role="img"><title>Bootstrap</title><path fill-rule="evenodd" clip-rule="evenodd" d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508zM80 57.863C80 66.663 73.436 72 62.543 72H44a2 2 0 01-2-2V24a2 2 0 012-2h18.437c9.083 0 15.044 4.92 15.044 12.474 0 5.302-4.01 10.049-9.119 10.88v.277C75.317 46.394 80 51.21 80 57.863zM60.521 28.34H49.948v14.934h8.905c6.884 0 10.68-2.772 10.68-7.727 0-4.643-3.264-7.207-9.012-7.207zM49.948 49.2v16.458H60.91c7.167 0 10.964-2.876 10.964-8.281 0-5.406-3.903-8.178-11.425-8.178H49.948z" fill="currentColor"></path></svg> -->
                    <span class="fs-4">健康上網</span>
                </a>
            </header>
            
            <div class="p-5 mb-4 bg-light rounded-3">
                <div class="container-fluid py-5">
                    <h1 class="display-5 fw-bold">健康上網</h1>
                    <p class="col-md-8 fs-4">歡迎使用健康上網APP，請依下列步驟繼續下一步</p>
                    <a href="#next-step" class="btn btn-primary btn-lg" type="button">繼續</a>
                </div>
            </div>
            
            <section id="next-step">
                <div class="row align-items-md-stretch">
                    <div class="col-md-6">
                        <div class="h-100 p-5 text-white bg-dark rounded-3">
                            <h2>我剛完成報到</h2>
                            <p>請進入註冊頁面，再依研究人員指示完成參與者系統的註冊手續。</p>
                            <a href="register.php" class="btn btn-outline-light" type="button">註冊</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="h-100 p-5 bg-light border rounded-3">
                            <h2>我已完成系統註冊</h2>
                            <p>請進入登入頁面，再依畫面指示，繼續完成本研究計畫的日程規劃。</p>
                            <a href="login.php" class="btn btn-outline-secondary" type="button">登入</a>
                        </div>
                    </div>
                </div>
            </section>
            
            <section id="android-group">
                <div class="row align-items-md-stretch">
                    <div class="col-md-6">
                        <div class="h-100 p-5 text-white bg-dark rounded-3">
                            <h2>我是參與安卓(Android)組的計畫</h2>
                            <p>請至以下Github連結下載程式安裝檔並自行完成安裝程序</p>
                            <a href="" class="btn btn-outline-light" type="button">註冊</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="h-100 p-5 bg-light border rounded-3">
                            <h2>我已完成系統註冊</h2>
                            <p>請進入登入頁面，再依畫面指示，繼續完成本研究計畫的日程規劃。</p>
                            <a href="login.php" class="btn btn-outline-secondary" type="button">登入</a>
                        </div>
                    </div>
                </div>
            </section>
            
            <footer class="pt-3 mt-4 text-muted border-top">
                &copy; 2024 CYou Liao
            </footer>
        </div>
    </main>
</body>
</html>