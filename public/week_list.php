<?php
/**
 * listing all user's week
 */
require_once 'connect_db.php';

$today = new DateTimeImmutable(date('Y-m-d'));

$sth = $dbh->prepare("SELECT * FROM `user` WHERE 1");
$sth->execute();
$user_list = $sth->fetchAll(PDO::FETCH_ASSOC);
echo '<table border=1>
<tr>
<th>id</th>
<th>exp_id</th>
<th>week</th>
</tr>';
foreach ($user_list as $user) {
    $u_id = $user['id'];
    $exp_id = $user['exp_id'];
    $start_date = new DateTimeImmutable($user['start_date']);
    $date_diff = $today->diff($start_date);
    if ($date_diff->days == 0) {
        $week = 0;
    } else {
        if ($date_diff->invert == 0) {
            
        }
    }
}
?>