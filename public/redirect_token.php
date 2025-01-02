<?php
use Cyouliao\Goaleval\User;

include_once './../vendor/autoload.php';

session_start();

$isLogin = false;

$expId = $_GET['exp_id'] ?? null;
$token = $_GET['token'] ?? null;

if ($expId != null && $token != null) {
    $user = User::getUserByExpId($expId);

    if ($token == $user->makeToken()) {
        $isLogin = true;
        $_SESSION['user'] = [
            'id' => $user->id,
            'std_id' => $user->stdId,
            'exp_id' => $user->expId,
            'exp_type' => $user->expType,
            'start_date' => $user->startDate,
        ];
    }
}

if ($isLogin) {
    header('Location: /redirect.php');
} else {
    $_SESSION['user'] = null;
    header('Location: /login.php');
}