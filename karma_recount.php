<?php

require_once('Settings.php');

$dsn = "mysql:host=$db_server;dbname=$db_name";
$pdo = new PDO($dsn, $db_user, $db_passwd);
$members = $db_prefix.'members';
$log_karma = $db_prefix.'log_karma';

$stmt_members = $pdo->query("SELECT id_member FROM $members");
while ($row_members = $stmt_members->fetch())
{
    $id_member = $row_members['id_member'];
    $stmt_karma = $pdo->query("SELECT * FROM $log_karma WHERE link != 'PM' AND id_target = $id_member");

    $karma_good = 0;
    $karma_bad = 0;

    while ($row_karma = $stmt_karma->fetch())
    {
        if ($row_karma['action'] > 0) {
            $karma_good++;
        } else {
            $karma_bad++;
        }
    }

    $sql = "UPDATE $members SET karma_good = $karma_good, karma_bad = $karma_bad WHERE id_member = $id_member";
    $pdo->prepare($sql)->execute();
}