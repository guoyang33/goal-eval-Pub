<?php

use Cyouliao\Goaleval\User;

include_once './../../vendor/autoload.php';

const FILTER_KEY = 'user_list';
const FILTER_IS_IOS = 'is_ios';
const FILTER_EXP_TYPE = 'exp_type';

$filterIsIOS = $_POST[FILTER_IS_IOS] ?? User::IS_IOS_IOS;
$filterExpType = $_POST[FILTER_EXP_TYPE] ?? User::EXP_TYPE_SET_GOAL;

setcookie(FILTER_KEY . '[' . FILTER_IS_IOS . ']', $filterIsIOS);
setcookie(FILTER_KEY . '[' . FILTER_EXP_TYPE . ']', $filterExpType);

header("Location: /admin/user_list.php");