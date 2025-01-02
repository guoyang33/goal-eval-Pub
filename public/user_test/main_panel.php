<?php
/*
 * user_test/main_panel.php
 * Main panel for the user test
 */
require_once '../get_user.php';

require_once '../connect_db.php';

// $u_id = $_GET['u_id'];
// $sth = $dbh->prepare('SELECT * FROM user WHERE id = :u_id');
// $sth->execute(array(':u_id' => $u_id));
// $user = $sth->fetch(PDO::FETCH_ASSOC);

// if (!$user) {
//     echo 'User not found';
//     exit();
// }

// foreach ($user as $key => $value) {
//     echo $key . ': ' . $value . '<br>';
// }
echo $user['exp_type'];

echo '<hr>';
// 增加天數
echo '<button class="btn btn-primary mx-1 btn-add-day" type="button">增加天數</button>';
// 減少天數
echo '<button class="btn btn-secondary mx-1 btn-sub-day" type="button">減少天數</button>';
// 刪除使用者
echo '<button class="btn btn-danger mx-1 btn-del-user" type="button">刪除使用者</button>';
// 回到首頁
echo '<a href="index.php" class="btn btn-info mx-1" type="button" role="button">回到首頁</a>';
echo '<br>
<a href="../redirect.php?u_id=' . $user['id'] . '" target="_blank">參與者介面</a>';
echo '<hr>';
echo '<iframe src="../redirect.php?u_id=' . $user['id'] . '" width="100%" height="1000px"></iframe>';
?>

<script type="text/javascript">
    $(document).ready(function() {
        $('.btn-add-day').click(function() {
            location.href = 'add_day.php?u_id=<?php echo $user['id']; ?>';
        })
        $('.btn-sub-day').click(function() {
            location.href = 'sub_day.php?u_id=<?php echo $user['id']; ?>';
        })
        $('.btn-del-user').click(function() {
            location.href = 'del_user.php?u_id=<?php echo $user['id']; ?>';
        })
    });
</script>