<?php
// 20230310新增 禁用cache
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
// HTTP/1.0
header("Pragma: no-cache");
define('STRATEGY_PRACTICE_INTRO_HTML', '<div class="d-grid pt-5 pb-3 px-2">
    <h2>誘惑下的武功秘笈-挑戰篇</h2>
    <p>前兩週您已學到四種抵抗誘惑的秘訣了，接下來第三週到第四週要實戰演練，請您每週進行四種策略演練，會先給您看一段遊戲廣告畫面，測試您想玩的渴求程度，再請您實際執行我們的策略試試，試完策略再次評估想玩的渴求，加油!</p>
</div>');
define('STRATEGY_APPLY_INTRO_HTML', '<div class="d-grid pt-5 pb-3 px-2">
    <h2>誘惑下的武功秘笈-應用篇</h2>
    <p>經過前面的演練，您的功力大大進步了，接下來第五週到第八週，請您靈活運用各種秘訣，會先給您看一段遊戲廣告畫面，測試您想玩的渴求，再自行選擇一種您認為最有效的策略去實踐，實踐完再評估想玩的渴求，加油!</p>
</div>')
?>

<head>

<title>健康上網APP</title>

<!-- 20230310新增 禁用cache -->
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">

<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"> -->

<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<!-- Bootstrap5 -->
<!-- MaxCDN -->
<!-- Latest compiled and minified CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Latest compiled JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom styles -->
<!-- 使畫面不可選取 -->
<style type="text/css">
html {
    user-select: none;
}
</style>

<!-- 20230323新增 取消預設右鍵事件 -->
<script type="text/javascript">window.addEventListener("contextmenu", (e) => {e.preventDefault();});</script>


</head>

<body>
<div class="container">