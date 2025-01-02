<?php
/*
 * del_user.php
 * 刪除使用者
 */
require_once '../get_user.php';

if ($user) {
    $sth = $dbh->prepare('DELETE FROM user WHERE id = :u_id');
    $sth->execute(array(
        ':u_id' => $user['id']
    ));
    header('Location: index.php');
} else {
    echo '無此使用者';
}
?>