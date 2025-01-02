<?php
use Cyouliao\Goaleval\DBConnect;
use Cyouliao\Goaleval\User;
use Cyouliao\Goaleval\ValidDays;

include_once './../vendor/autoload.php';

header('Content-Type: application/json; charset=utf-8');

const POST_DATA_KEY_EXP_ID      = 'exp_id';
const POST_DATA_KEY_STD_ID      = 'std_id';
const POST_DATA_KEY_PASSWORD    = 'password';

const STATUS_OK                             = 'OK';
const STATUS_ERROR_UNKNOWN                  = 'ERROR_UNKNOWN';
const STATUS_ERROR_MISSING_EXP_ID           = 'ERROR_MISSING_EXP_ID';
const STATUS_ERROR_MISSING_STD_ID           = 'ERROR_MISSING_STD_ID';
const STATUS_ERROR_MISSING_PASSWORD         = 'ERROR_MISSING_PASSWORD';
const STATUS_ERROR_PASSWORD_TOO_SHORT       = 'ERROR_PASSWORD_TOO_SHORT';
const STATUS_ERROR_USER_NOT_FOUND           = 'ERROR_USER_NOT_FOUND';
const STATUS_ERROR_USER_ALREADY_REGISTERED  = 'ERROR_USER_ALREADY_REGISTERED';
const STATUS_ERROR_USER_NOT_ANDROID         = 'ERROR_USER_NOT_ANDROID';
const STATUS_ERROR_EXP_ID_NOT_MATCH         = 'STATUS_ERROR_EXP_ID_NOT_MATCH';

const ERROR_MESSAGES = [
    STATUS_ERROR_MISSING_EXP_ID             => 'Argument missing: ' . POST_DATA_KEY_EXP_ID,
    STATUS_ERROR_MISSING_STD_ID             => 'Argument missing: ' . POST_DATA_KEY_STD_ID,
    STATUS_ERROR_MISSING_PASSWORD           => 'Argument missing: ' . POST_DATA_KEY_PASSWORD,
    STATUS_ERROR_PASSWORD_TOO_SHORT         => 'Password too short.',
    STATUS_ERROR_USER_NOT_FOUND             => 'User data not found in database.',
    STATUS_ERROR_USER_ALREADY_REGISTERED    => 'User is already registered.',
    STATUS_ERROR_USER_NOT_ANDROID           => 'User is not Android group.',
    STATUS_ERROR_EXP_ID_NOT_MATCH           => 'User ExpID not match.'
];

const RESPONSE_HEADERS = 'headers';
const RESPONSE_HEADERS_STATUS = 'status';
const RESPONSE_HEADERS_ERROR_MSG = 'errorMsg';

const RESPONSE_CONTENTS = 'contents';
const RESPONSE_CONTENTS_IS_SUCCESS = 'isSuccess';
const RESPONSE_CONTENTS_EXP_ID = 'expID';
const RESPONSE_CONTENTS_TOKEN = 'token';


const SOURCE_FILE = '/register.php';

try {
    $responseHeadersStatus = STATUS_OK;
    $responseHeadersErrorMessage = '';
    $responseContentsIsSuccess = false;
    $responseContentsExpID = '';
    $responseContentsToken = '';

    $expID = $_POST[POST_DATA_KEY_EXP_ID] ?? null;
    $stdID = $_POST[POST_DATA_KEY_STD_ID] ?? null;
    $password = $_POST[POST_DATA_KEY_PASSWORD] ?? null;

    if (is_null($expID)) {
        throw new Exception(STATUS_ERROR_MISSING_EXP_ID);
    }
    if (is_null($stdID)) {
        throw new Exception(STATUS_ERROR_MISSING_STD_ID);
    }
    if (is_null($password)) {
        throw new Exception(STATUS_ERROR_MISSING_PASSWORD);
    }
    if (strlen($password) < 8) {
        throw new Exception(STATUS_ERROR_PASSWORD_TOO_SHORT);
    }

    $user = User::getUserByStdId($stdID);
    if (is_null($user->id)) {
        throw new Exception(STATUS_ERROR_USER_NOT_FOUND);
    }
    if ($user->isIOS) {
        throw new Exception(STATUS_ERROR_USER_NOT_ANDROID);
    }
    if ($expID != $user->expId) {
        throw new Exception(STATUS_ERROR_EXP_ID_NOT_MATCH);
    }
    if (!is_null($user->passwordHash)) {
        throw new Exception(STATUS_ERROR_USER_ALREADY_REGISTERED);
    }

    $user->password = password_hash($password, PASSWORD_DEFAULT);
    $user->passwordHash = $user->password;
    $user->update();
    $validDaysUserSourceFiles = ValidDays::getSourceFilesByUserId($user->id);
    if (!in_array(SOURCE_FILE, $validDaysUserSourceFiles)) {
        $validDays = new ValidDays();
        $validDays->userId = $user->id;
        $validDays->provideDays = 8;
        $validDays->sourceFile = SOURCE_FILE;
        $validDays->sourceObject = '{"REASON":"PROVIDE_BEGINNING_PROGRESS", "THROUGH":"register.php"}';
        $validDays->week = 0;
        $validDays->date = date('Y-m-d');
        $validDays->insert();
    }
    
    $responseContentsIsSuccess = true;
    $responseContentsExpID = $user->expId;
    $responseContentsToken = $user->makeToken();

} catch (Exception $e) {
    $errorCode = $e->getMessage();
    if (key_exists($errorCode, ERROR_MESSAGES)) {
        $responseHeadersStatus = $errorCode;
        $responseHeadersErrorMessage = ERROR_MESSAGES[$errorCode];

    } else {
        $responseHeadersStatus = STATUS_ERROR_UNKNOWN;
        $responseHeadersErrorMessage = $e->getMessage();

    }

} finally {
    echo json_encode([
        RESPONSE_HEADERS => [
            RESPONSE_HEADERS_STATUS => $responseHeadersStatus,
            RESPONSE_HEADERS_ERROR_MSG => $responseHeadersErrorMessage
        ],
        RESPONSE_CONTENTS => [
            RESPONSE_CONTENTS_IS_SUCCESS => $responseContentsIsSuccess,
            RESPONSE_CONTENTS_EXP_ID => $responseContentsExpID,
            RESPONSE_CONTENTS_TOKEN => $responseContentsToken
        ]
    ]);
}
