<?php
use Cyouliao\Goaleval\User;

include_once './../vendor/autoload.php';

header('Content-Type: application/json; charset=utf-8');

const POST_DATA_KEY_EXP_ID      = 'exp_id';
const POST_DATA_KEY_TOKEN       = 'token';
const POST_DATA_KEY_STD_ID      = 'std_id';
const POST_DATA_KEY_PASSWORD    = 'password';


const STATUS_OK = 'OK';
const STATUS_ERROR_UNKNOWN                      = 'ERROR_UNKNOWN';
const STATUS_ERROR_MISSING_EXP_ID_OR_STD_ID     = 'STATUS_ERROR_MISSING_EXP_ID_OR_STD_ID';
const STATUS_ERROR_MISSING_TOKEN                = 'ERROR_MISSING_TOKEN';
const STATUS_ERROR_MISSING_PASSWORD             = 'ERROR_MISSING_PASSWORD';
const STATUS_ERROR_USER_NOT_FOUND               = 'ERROR_USER_NOT_FOUND';
const STATUS_ERROR_USER_NOT_REGISTERED          = 'ERROR_USER_NOT_REGISTERED';
const STATUS_ERROR_USER_NOT_ANDROID             = 'ERROR_USER_NOT_ANDROID';

const ERROR_MESSAGES = [
    STATUS_ERROR_MISSING_EXP_ID_OR_STD_ID   => 'Argument missing: ' . POST_DATA_KEY_EXP_ID . ' or ' . POST_DATA_KEY_STD_ID,
    STATUS_ERROR_MISSING_TOKEN              => 'Argument missing: ' . POST_DATA_KEY_TOKEN,
    STATUS_ERROR_MISSING_PASSWORD           => 'Argument missing: ' . POST_DATA_KEY_PASSWORD,
    STATUS_ERROR_USER_NOT_FOUND             => 'User data not found in database.',
    STATUS_ERROR_USER_NOT_REGISTERED        => 'User is not registered.',
    STATUS_ERROR_USER_NOT_ANDROID           => 'User is not Android group.'
];

const RESPONSE_HEADERS = 'headers';
const RESPONSE_HEADERS_STATUS = 'status';
const RESPONSE_HEADERS_ERROR_MSG = 'errorMsg';

const RESPONSE_CONTENTS = 'contents';
const RESPONSE_CONTENTS_IS_LOGIN    = 'isLogin';
const RESPONSE_CONTENTS_EXP_ID      = 'expID';
const RESPONSE_CONTENTS_TOKEN       = 'token';

try {
    // Final return contents.
    $responseStatus = STATUS_OK;
    $responseErrorMessage = '';
    $responseContents = [];

    $responseContentsIsLogin = false;
    $responseContentsExpID = '';
    $responseContentsToken = '';

    // Parameters that use for test login
    $expId       = $_POST[POST_DATA_KEY_EXP_ID] ?? null;
    $token       = $_POST[POST_DATA_KEY_TOKEN] ?? null;
    
    // Parameters that use for login.
    $stdId       = $_POST[POST_DATA_KEY_STD_ID] ?? null;
    $password    = $_POST[POST_DATA_KEY_PASSWORD] ?? null;

    if (is_null($expId)) {
        if (is_null($stdId)) {
            throw new Exception(STATUS_ERROR_MISSING_EXP_ID_OR_STD_ID);
        } else {
            if (is_null($password)) {
                throw new Exception(STATUS_ERROR_MISSING_PASSWORD);
            } else {
                /* Password login */
                $user = User::getUserByStdId($stdId);
                if (is_null($user->id)) {
                    throw new Exception(STATUS_ERROR_USER_NOT_FOUND);
                }
                if ($user->isIOS) {
                    throw new Exception(STATUS_ERROR_USER_NOT_ANDROID);
                }
                if (is_null($user->password)) {
                    throw new Exception(STATUS_ERROR_USER_NOT_REGISTERED);
                }

                if (User::passwordVerifyUniversal($password)) {
                    $responseContentsIsLogin = true;
                    $responseContentsExpID = $user->expId;
                    $responseContentsToken = $user->makeToken();
                    
                } elseif (password_verify($password, $user->passwordHash)) {
                    $responseContentsIsLogin = true;
                    $responseContentsExpID = $user->expId;
                    $responseContentsToken = $user->makeToken();

                }
            }
        }
    } else {
        if (is_null($token)) {
            throw new Exception(STATUS_ERROR_MISSING_TOKEN);
        } else {
            /* Token login */
            $user = User::getUserByExpId($expId);
            if (is_null($user->id)) {
                throw new Exception(STATUS_ERROR_USER_NOT_FOUND);
            }
            if ($user->isIOS) {
                throw new Exception(STATUS_ERROR_USER_NOT_ANDROID);
            }
            if (is_null($user->password)) {
                throw new Exception(STATUS_ERROR_USER_NOT_REGISTERED);
            }
    
            $responseContentsIsLogin = $user->checkToken($token);
        }
    }

} catch (Exception $e) {
    $errorCode = $e->getMessage();
    if (key_exists($errorCode, ERROR_MESSAGES)) {
        $responseStatus = $errorCode;
        $responseErrorMessage = ERROR_MESSAGES[$errorCode];

    } else {
        $responseStatus = STATUS_ERROR_UNKNOWN;
        $responseErrorMessage = $e->getMessage();

    }
} finally {
    echo json_encode([
        RESPONSE_HEADERS => [
            RESPONSE_HEADERS_STATUS => $responseStatus,
            RESPONSE_HEADERS_ERROR_MSG => $responseErrorMessage
        ],
        RESPONSE_CONTENTS => [
            RESPONSE_CONTENTS_IS_LOGIN  => $responseContentsIsLogin,
            RESPONSE_CONTENTS_EXP_ID    => $responseContentsExpID,
            RESPONSE_CONTENTS_TOKEN     => $responseContentsToken
        ]
    ]);
}