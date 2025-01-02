<!doctype html>
<html>
    <head>
        <!--導入bootstrap 5各庫-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta1/css/bootstrap.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta1/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <title>碳足跡獎勵小語測試</title>
    </head>
    <body>
        <div class="container">
            <!--標題: 碳足跡獎勵小語測試-->
            <div class="row">
                <div class="col-12">
                    <h1>碳足跡獎勵小語測試</h1>
                </div>
            </div>
            <!--說明-->
            <div class="row">
                <div class="col-12">
                    <h4>畫面設定至第2週結算。填寫完評估單後在結算畫面可以看到獎勵小語</h4>
                    <h4>測試網址：</h4>
                </div>
            </div>
            <!--Table: 2x4-->
            <div class="col-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">設定情境</th>
                            <th scope="col">畫面測試入口</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">
                                使用時間
                                <span class="text-primary">達成</span>
                                設定目標，且使用時間有
                                <span class="text-primary">減少</span>
                            </th>
                            <td>
                                <a class="btn btn-warning" href="carbon_test.php?is_goal=1&is_equal=0&is_reduce=1&exp_type=training_strategy" target="_blank">策略組</a>
                                <a class="btn btn-info" href="carbon_test.php?is_goal=1&is_equal=0&is_reduce=1&exp_type=set_goal" target="_blank">目標組</a>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">使用時間
                                <span class="text-danger">未達成</span>
                                設定目標，且使用時間有
                                <span class="text-primary">減少</span>
                            </th>
                            <td>
                                <a class="btn btn-warning" href="carbon_test.php?is_goal=0&is_equal=0&is_reduce=1&exp_type=training_strategy" target="_blank">策略組</a>
                                <a class="btn btn-info" href="carbon_test.php?is_goal=0&is_equal=0&is_reduce=1&exp_type=set_goal" target="_blank">目標組</a>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">使用時間
                                <span class="text-danger">未達成</span>
                                設定目標，且使用時間與上週
                                <span class="text-danger">一致</span>
                            </th>
                            <td>
                                <a class="btn btn-warning" href="carbon_test.php?is_goal=0&is_equal=1&is_reduce=0&exp_type=training_strategy" target="_blank">策略組</a>
                                <a class="btn btn-info" href="carbon_test.php?is_goal=0&is_equal=1&is_reduce=0&exp_type=set_goal" target="_blank">目標組</a>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                使用時間
                                <span class="text-danger">未達成</span>
                                設定目標，且使用時間較上週
                                <span class="text-danger">增加</span>
                            </th>
                            <td>
                                <a class="btn btn-warning" href="carbon_test.php?is_goal=0&is_equal=0&is_reduce=0&exp_type=training_strategy" target="_blank">策略組</a>
                                <a class="btn btn-info" href="carbon_test.php?is_goal=0&is_equal=0&is_reduce=0&exp_type=set_goal" target="_blank">目標組</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>