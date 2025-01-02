<?php
$answer_sheet = $_POST['answer_sheet'];
$mobile_usetime = $answer_sheet['spend_time']['target']['time'];

$mobile_tags = array('mobile_games', 'mobile_socialnetworking', 'mobile_entertainment', 'mobile_screentime');
$mobile_name = array('遊戲', '社交', '娛樂', '螢幕使用時間');
$weekday = array('mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun');
$option_reductiontarget = array('<option value=0>0</option>', '<option value=3>3</option><option value=4>4</option><option value=5>5</option><option value=6>6</option><option value=7>7</option><option value=8>8</option><option value=9>9</option><option value=10>10</option>');

$reductiontarget_option_type = array();
$mobile_usetime_avg_table_inner = '';

for ($tag_index = 0 ; $tag_index < count($mobile_tags) ; $tag_index++) {
    $weekday_sum = 0;

    foreach ($weekday as $day) {
        $weekday_sum += $mobile_usetime[$mobile_tags[$tag_index]][$day];
    }

    if ($weekday_sum) {
        $reductiontarget_option_type[$mobile_tags[$tag_index]] = &$option_reductiontarget[1];
    } else {
        $reductiontarget_option_type[$mobile_tags[$tag_index]] = &$option_reductiontarget[0];
    }

    $weekday_avg = floor($weekday_sum / 7);
    $mobile_usetime_avg_table_inner .= ("<tr><td>{$mobile_name[$tag_index]}</td><td>" . (floor($weekday_avg / 60)) . "時" . ($weekday_avg % 60) . "分</td></tr>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        #textarea-originaldata {
            width: 80%;
        }
    </style>
</head>
<body>
    <h1><strong><label for="textarea-originaldata">原始資料:</label></strong></h1>
    <textarea id="textarea-originaldata" rows="20"><?php echo json_encode($answer_sheet); ?></textarea>
    <br>
    <h1><strong>您在上一週的手機使用時間</strong></h1>
    <table><tr><th>App類別</th><th>每天平均使用時間</th></tr><?php echo $mobile_usetime_avg_table_inner; ?></table>
    <br>
    <h1><strong>請您依預備週的使用為基準值來設定本週的減量目標</strong></h1>
    <form action="test_get_value.php" method="POST">
        <?php
        for ($tag_index = 0 ; $tag_index < count($mobile_tags) ; $tag_index++) {
            echo "<label for=\"{$mobile_tags[$tag_index]}\">{$mobile_name[$tag_index]}&nbsp;</label><select name=\"answer_sheet[reduction_target][$mobile_tags[$tag_index]]\" id=\"{$mobile_tags[$tag_index]}\">{$reductiontarget_option_type[$mobile_tags[$tag_index]]}</select>&nbsp;%<br>";
        }
        ?>
        <input type="submit" value="提交">
    </form>
</body>
</html>
