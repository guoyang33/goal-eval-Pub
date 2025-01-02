<?php
require_once 'connect_db.php';
require_once 'get_user.php';
require_once 'html_head.php';

$reduce_rate = $_POST['reduce_rate'];
if (in_array('save_score', array_keys($_POST))) {
    $save_score = $_POST['save_score'];
}

$sth = $dbh->prepare("SELECT * FROM `goal` WHERE `u_id` = :u_id AND `week` = :week LIMIT 1;");
$sth->execute([
    'u_id' => $user['id'],
    'week' => $week
]);

$reduce_game = $reduce_rate['GAME'];
$reduce_video = $reduce_rate['VIDEO'];
$reduce_social = $reduce_rate['SOCIAL'];
$reduce_communication = $reduce_rate['COMMUNICATION'];
$reduce_total = $reduce_rate['TOTAL'];

if ($week > 1) {
    if (in_array('save_score', array_keys($_POST))) {
        $update_save_score_array = array();
        foreach (array_keys($save_score) as $save_category) {
            array_push($update_save_score_array, "`{$save_category}` = 1");
        }
    }
}

$goal = $sth->fetch(PDO::FETCH_ASSOC);
if ($goal === false) {      // 插入資料
    $sth = $dbh->prepare("INSERT INTO `goal` (`u_id`, `week`, `start_date`, `reduce_game`, `reduce_video`, `reduce_social`, `reduce_communication`, `reduce_total`) VALUE (:u_id, :week, :start_date, :reduce_game, :reduce_video, :reduce_social, :reduce_communication, :reduce_total);");
    $sth->execute([
        'u_id' =>                   $user['id'],
        'week' =>                   $week,
        'start_date' =>             ($start_date->modify((($week * 7) + 1) . ' day'))->format('Y-m-d'),
        'reduce_game' =>            $reduce_rate['GAME'],
        'reduce_video' =>           $reduce_rate['VIDEO'],
        'reduce_social' =>          $reduce_rate['SOCIAL'],
        'reduce_communication' =>   $reduce_rate['COMMUNICATION'],
        'reduce_total' =>           $reduce_rate['TOTAL'],
    ]);
} else {        // 資料已存在，改用更新
    $sth = $dbh->prepare("UPDATE `goal` SET `start_date`=:start_date, `reduce_game`=:reduce_game, `reduce_video`=:reduce_video, `reduce_social`=:reduce_social, `reduce_communication`=:reduce_communication, `reduce_total`=:reduce_total WHERE id=:g_id");
    $sth->execute([
        'start_date' =>             ($start_date->modify((($week * 7) + 1) . ' day'))->format('Y-m-d'),
        'reduce_game' =>            $reduce_rate['GAME'],
        'reduce_video' =>           $reduce_rate['VIDEO'],
        'reduce_social' =>          $reduce_rate['SOCIAL'],
        'reduce_communication' =>   $reduce_rate['COMMUNICATION'],
        'reduce_total' =>           $reduce_rate['TOTAL'],
        'g_id' =>                   $goal['id']
    ]);

    if ($week > 1) {
        if (in_array('save_score', array_keys($_POST))) {
            $sth = $dbh->prepare('UPDATE `goal` SET ' . implode(', ', $update_save_score_array) . ' WHERE `u_id` = :u_id AND `week` = :last_week LIMIT 1;');
            $sth->execute([
                'u_id' => $user['id'],
                'last_week' => ($week - 1)      // 更新上週的是否搶救目標
            ]);
        }
    }
}

// 新增進度(有效天數)
$insert_valid_days_status = insert_provide_valid_days($user['id'], PROVIDE_VALID_DAYS[$user['exp_type']]['set_goal_android'], basename(__FILE__), 'set_goal_android', $week);
if ($insert_valid_days_status['error']) {
    echo '<div class="d-grid pt-5 pb-3 px-2">
        <h2 class="text-danger">資料庫操作失敗，請聯絡研究人員: INSERT INTO valid_days</h2>
        <p>errorInfo: ';
        var_dump($insert_valid_days_status['content']);
        echo '</p>
        </div>';
    exit();
}

echo '<div class="d-grid pt-5 px-2">
<p>提交成功</p>';
echo '<button type="button" class="btn btn-primary" onclick=location.href="redirect.php?u_id='.$user['id'].'">
返回主選單
</button>
</div>
</div>';
?>