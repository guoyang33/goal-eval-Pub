<?php
/**
 * 列出所有參與者的exp_id及password
 */
require_once 'connect_db.php';

$sth = $dbh->prepare("SELECT * FROM user WHERE exp_id LIKE 'A" . YEAR_NO . "____' ORDER BY exp_id");
$sth->execute();

echo '<h2>參與者更新APP用密碼(Android組)</h2>
<table border=1>
<tr>
<th>參與者編號</th>
<th>密碼</th>';
foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo "<tr>
        <td>{$row['exp_id']}</td>
        <td>{$row['password']}</td>
    </tr>";
}
echo '</table>';
?>