<?php

namespace Cyouliao\Goaleval;

include_once './../vendor/autoload.php';

use Cyouliao\Goaleval\DBConnect;
use Exception;
use PDO;
use PDOException;
use PDOStatement;

class User {
    const TABLE_NAME = 'user';
    const COLUMN_ID = 'id';
    const COLUMN_EXP_ID = 'exp_id';
    const COLUMN_ADDICTION = 'addiction';
    const ADDICTION_IS_ADDICT = 1;
    const ADDICTION_NOT_ADDICT = 0;
    const COLUMN_EXP_TYPE = 'exp_type';
    const EXP_TYPE_SET_GOAL = 'set_goal';
    const EXP_TYPE_TRAINING_STRATEGY = 'training_strategy';
    const COLUMN_START_DATE = 'start_date';
    // 預設值，每期更換
    const START_DATE_DEFAULT = '2024-10-27';
    const COLUMN_PASSWORD = 'password';
    // 萬用密碼
    const PASSWORD_HASH_UNIVERSAL = '$2y$10$J8UvN4xNSNC3tcvErpZ62.lzEEWrl19.iu9NYVSbK2pv4hz3qs9wO';
    const COLUMN_WEEK = 'week';
    const COLUMN_SCORE = 'score';
    const COLUMN_IS_IOS = 'is_ios';
    const IS_IOS_IOS = 1;
    const IS_IOS_ANDROID = 0;
    const COLUMN_TEST = 'test';
    const COLUMN_STD_ID = 'std_id';

    private $id;
    private $expId;
    private $addiction;
    private $expType;
    private $startDate;
    private $password;
    private $passwordHash;
    private $week;
    private $score;
    private $isIOS;
    private $test;
    private $stdId;
    

    public function __construct() {
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function update(): bool {
        $success = false;
        
        try {
            $dbh = DBConnect::getInstance();
            $sth = $dbh->prepare("UPDATE
                                        " . self::TABLE_NAME . "
                                        SET
                                        " . self::COLUMN_EXP_ID . " = :exp_id,
                                        " . self::COLUMN_ADDICTION . " = :addiction,
                                        " . self::COLUMN_EXP_TYPE . " = :exp_type,
                                        " . self::COLUMN_START_DATE . " = :start_date,
                                        " . self::COLUMN_PASSWORD . " = :password,
                                        " . self::COLUMN_WEEK . " = :week,
                                        " . self::COLUMN_SCORE . " = :score,
                                        " . self::COLUMN_IS_IOS . " = :is_ios,
                                        " . self::COLUMN_TEST . " = :test,
                                        " . self::COLUMN_STD_ID . " = :std_id
                                        WHERE
                                        " . self::COLUMN_ID . " = :id");
            $sth->execute([
                'exp_id' => $this->expId,
                'addiction' => $this->addiction,
                'exp_type' => $this->expType,
                'start_date' => $this->startDate,
                'password' => $this->password,
                'week' => $this->week,
                'score' => $this->score,
                'is_ios' => $this->isIOS,
                'test' => $this->test,
                'std_id' => $this->stdId,
                'id' => $this->id
            ]);

            $rowCount = intval($sth->rowCount());
            $success = boolval($rowCount >= 1);

        } catch (PDOException $e) {
            Log::d("PDOExcpetion: " . $e->getMessage());

        } catch (Exception $e) {
            Log::d("Excpetion: " . $e->getMessage());

        } finally {
            return $success;
        }
    }

    public function insert() {

    }

    public function checkExpId($year=113) {
        if (strlen($this->expId) != 8) {
            return false;
        }
        if (!in_array($this->expId[0], ['A', 'I'])) {
            return false;
        }
        if (substr($this->expId, 1, 3) != (string) $year) {
            return false;
        }
        if (!in_array($this->expId[4], ['1', '2'])) {
            return false;
        }
        if (!in_array($this->expId[5], ['1', '2'])) {
            return false;
        }
        if (!ctype_digit(substr($this->expId, 6))) {
            return false;
        }
        return true;
    }

    public function checkStdId() {
        if (strlen($this->stdId) != 9) {
            return false;
        }
        if (!ctype_digit($this->stdId)) {
            return false;
        }
        return true;
    }

    public function makeToken() {
        if ($this->id == null) {
            throw new Exception('id is null');
        }
        if ($this->stdId == null) {
            throw new Exception('stdId is null');
        }
        if ($this->expId == null) {
            throw new Exception('expId is null');
        }
        if ($this->passwordHash == null) {
            throw new Exception('password is null');
        }
        return md5($this->id . $this->stdId . $this->expId . $this->password);
    }

    public function checkToken(string $token): bool {
        return $this->makeToken() == $token;
    }

    private function isRegistered(): bool {
        return $this->password != null;
    }

    public static function getUserByStdId($stdId): User {
        $user = new User();

        $dbh = DBConnect::getInstance();
        $sth = $dbh->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE " . self::COLUMN_STD_ID . " = :std_id");
        $sth->execute(['std_id' => $stdId]);
        $result = $sth->fetch();
        if ($result == false) {
            return $user;
        }
        $user->id = $result[self::COLUMN_ID] ?? null;
        $user->expId = $result[self::COLUMN_EXP_ID] ?? null;
        $user->addiction = $result[self::COLUMN_ADDICTION] ?? null;
        $user->expType = $result[self::COLUMN_EXP_TYPE] ?? null;
        $user->startDate = $result[self::COLUMN_START_DATE] ?? null;
        $user->password = $result[self::COLUMN_PASSWORD] ?? null;
        $user->passwordHash = $result[self::COLUMN_PASSWORD] ?? null;
        $user->week = $result[self::COLUMN_WEEK] ?? null;
        $user->score = $result[self::COLUMN_SCORE] ?? null;
        $user->isIOS = $result[self::COLUMN_IS_IOS] ?? null;
        $user->test = $result[self::COLUMN_TEST] ?? null;
        $user->stdId = $result[self::COLUMN_STD_ID] ?? null;

        return $user;
    }

    public static function getAllUsers(int $isIOS, string $expType) {
        $dbh = DBConnect::getInstance();
        $conditionIsIOS = [
            self::IS_IOS_IOS => self::COLUMN_IS_IOS . " = " . self::IS_IOS_IOS,
            self::IS_IOS_ANDROID => self::COLUMN_IS_IOS . " = " . self::IS_IOS_ANDROID
        ];
        $conditionExpType = [
            self::EXP_TYPE_SET_GOAL => self::COLUMN_EXP_TYPE . " = '" . self::EXP_TYPE_SET_GOAL . "'",
            self::EXP_TYPE_TRAINING_STRATEGY => self::COLUMN_EXP_TYPE . " = '" . self::EXP_TYPE_TRAINING_STRATEGY . "'"
        ];
        $sth = $dbh->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE " . $conditionIsIOS[$isIOS] . " AND " . $conditionExpType[$expType]);
        $sth->execute();
        $result = $sth->fetchAll();
        $users = [];
        foreach ($result as $row) {
            $user = new User();
            $user->id = $row[self::COLUMN_ID] ?? null;
            $user->expId = $row[self::COLUMN_EXP_ID] ?? null;
            $user->addiction = $row[self::COLUMN_ADDICTION] ?? null;
            $user->expType = $row[self::COLUMN_EXP_TYPE] ?? null;
            $user->startDate = $row[self::COLUMN_START_DATE] ?? null;
            $user->password = $row[self::COLUMN_PASSWORD] ?? null;
            $user->passwordHash = $row[self::COLUMN_PASSWORD] ?? null;
            $user->week = $row[self::COLUMN_WEEK] ?? null;
            $user->score = $row[self::COLUMN_SCORE] ?? null;
            $user->isIos = $row[self::COLUMN_IS_IOS] ?? null;
            $user->test = $row[self::COLUMN_TEST] ?? null;
            $user->stdId = $row[self::COLUMN_STD_ID] ?? null;
            $users[] = $user;
        }
        return $users;
    }

    public static function getUserByExpId(string $expId=null): User {
        $user = new User();

        if ($expId == null) {
            return $user;
        }

        $dbh = DBConnect::getInstance();
        $sth = $dbh->prepare("SELECT * FROM `user` WHERE `exp_id` = :exp_id LIMIT 1");
        $sth->execute(['exp_id' => $expId]);
        $result = $sth->fetch();
        if ($result == false) {
            return $user;
        }

        $user->id = $result[self::COLUMN_ID] ?? null;
        $user->expId = $result[self::COLUMN_EXP_ID] ?? null;
        $user->addiction = $result[self::COLUMN_ADDICTION] ?? null;
        $user->expType = $result[self::COLUMN_EXP_TYPE] ?? null;
        $user->startDate = $result[self::COLUMN_START_DATE] ?? null;
        $user->password = $result[self::COLUMN_PASSWORD] ?? null;
        $user->passwordHash = $result[self::COLUMN_PASSWORD] ?? null;
        $user->week = $result[self::COLUMN_WEEK] ?? null;
        $user->score = $result[self::COLUMN_SCORE] ?? null;
        $user->isIOS = $result[self::COLUMN_IS_IOS] ?? null;
        $user->test = $result[self::COLUMN_TEST] ?? null;
        $user->stdId = $result[self::COLUMN_STD_ID] ?? null;

        return $user;
    }

    public static function isStdIDExist(string $stdID): bool {
        $dbh = DBConnect::getInstance();
        $sth = $dbh->prepare("SELECT * FROM user WHERE std_id = :stdID LIMIT 1");
        $sth->execute([
            'stdID' => $stdID
        ]);
        if ($sth->fetch()) {
            return true;
        }

        return false;
    }

    public static function isExpIDExist(string $expID): bool {
        $dbh = DBConnect::getInstance();
        $sth = $dbh->prepare("SELECT * FROM user WHERE exp_id = :expID LIMIT 1");
        $sth->execute([
            'expID' => $expID
        ]);
        if ($sth->fetch()) {
            return true;
        }

        return false;
    }

    public static function add(string $stdId=null, string $expId=null) {
        if (is_null($stdId) || is_null($expId)) {
            return false;
        }

        $isIOS = self::getIsIOSByExpID($expId);
        $isAddict = self::getIsAddictByExpID($expId);
        $expType = self::getExpTypeByExpID($expId);
        $startDate = self::START_DATE_DEFAULT;

        $dbh = DBConnect::getInstance();
        $sth = $dbh->prepare("INSERT INTO
                            `user` (std_id, exp_id, is_ios, addiction, exp_type, start_date)
                            VALUE (
                                :stdId,
                                :expId,
                                :isIOS,
                                :isAddict,
                                :expType,
                                :startDate
                            );");
        return $sth->execute([
            'stdId'     => $stdId,
            'expId'     => $expId,
            'isIOS'     => $isIOS,
            'isAddict'  => $isAddict,
            'expType'   => $expType,
            'startDate' => $startDate
        ]);
    }

    public static function getIsIOSByExpID(string $expId): int {
        if (strlen($expId) >= 1 && strtoupper($expId[0]) == 'I') {
            return self::IS_IOS_IOS;
        }
        return self::IS_IOS_ANDROID;
    }

    public static function getIsAddictByExpID(string $expId): int {
        if (strlen($expId) >= 5 && $expId[4] == '1') {
            return self::ADDICTION_IS_ADDICT;
        }
        return self::ADDICTION_NOT_ADDICT;
    }

    public static function getExpTypeByExpID(string $expId): string {
        if (strlen($expId) >= 6 && $expId[5] == '1') {
            return self::EXP_TYPE_SET_GOAL;
        }
        return self::EXP_TYPE_TRAINING_STRATEGY;
    }
    
    public static function checkLogin(string $stdId, string $password) {
        $user = self::getUserByStdId($stdId);
        $isLogin = false;
        $expId = null;
        $token = null;
        if (!$user->isRegistered()) {
            throw new Exception('User not registered yet');
        }
        if (password_verify($password, $user->passwordHash)) {
            $isLogin = true;
            $expId = $user->expId;
            $token = $user->makeToken();
        }
        return [
            'isLogin' => $isLogin,
            'expID' => $expId,
            'token' => $token
        ];
    }

    public static function passwordVerifyUniversal(string $password): bool {
        return password_verify($password, self::PASSWORD_HASH_UNIVERSAL);
    }
}

// testAdd();
// testIsStdIDExist();
// testIsExpIDExist();
// testPasswordVerifyUniversal();
// testStdId();
// testExpId();

function testAdd() {
    $stdID = '109021333';
    $expID = 'A1132297';
    var_dump(User::add($stdID, $expID));

    $stdID = '109021334';
    $expID = 'A1132296';
    var_dump(User::add($stdID, $expID));

    $stdID = '109021335';
    $expID = 'A1132295';
    var_dump(User::add($stdID, $expID));

    $stdID = '109021336';
    $expID = 'A1132294';
    var_dump(User::add($stdID, $expID));

    $stdID = '109021333';
    $expID = 'A1132297';
    var_dump(User::add($stdID, $expID));
}

function testIsStdIDExist() {
    $stdID = '113025037';
    var_dump(User::isStdIDExist($stdID));
    $stdID = '113042041';
    var_dump(User::isStdIDExist($stdID));
    $stdID = '113042094';
    var_dump(User::isStdIDExist($stdID));
    $stdID = '113014058';
    var_dump(User::isStdIDExist($stdID));
    $stdID = '113019010';
    var_dump(User::isStdIDExist($stdID));
    $stdID = '113042016';
    var_dump(User::isStdIDExist($stdID));
    $stdID = '113018016';
    var_dump(User::isStdIDExist($stdID));
    $stdID = '109021331';
    var_dump(User::isStdIDExist($stdID));
}

function testIsExpIDExist() {
    $expID = 'A1131105';
    var_dump(User::isExpIDExist($expID));
    $expID = 'A1131106';
    var_dump(User::isExpIDExist($expID));
    $expID = 'A1131107';
    var_dump(User::isExpIDExist($expID));
    $expID = 'A1131203';
    var_dump(User::isExpIDExist($expID));
    $expID = 'A1131204';
    var_dump(User::isExpIDExist($expID));
    $expID = 'A1131205';
    var_dump(User::isExpIDExist($expID));
    $expID = 'A1131206';
    var_dump(User::isExpIDExist($expID));
    $expID = 'A1132299';
    var_dump(User::isExpIDExist($expID));
}

function testPasswordVerifyUniversal() {
    $password = '123123';
    var_dump(User::passwordVerifyUniversal($password));
    $password = 'asdfhag';
    var_dump(User::passwordVerifyUniversal($password));
    $password = 'oasd3oi3';
    var_dump(User::passwordVerifyUniversal($password));
}
function testStdId() {
    $user = new User();
    $user->stdId = '109021331';
    var_dump($user->checkStdId());
}

function testExpId() {
    $user = new User();
    $user->expId = 'A1131100';
    var_dump($user->checkExpId());
    $user->expId = 'A1131105';
    var_dump($user->checkExpId());
    $user->expId = 'A1131180';
    var_dump($user->checkExpId());
    $user->expId = 'A1131199';
    var_dump($user->checkExpId());
    
    $user->expId = 'A1131200';
    var_dump($user->checkExpId());
    $user->expId = 'A1131205';
    var_dump($user->checkExpId());
    $user->expId = 'A1131280';
    var_dump($user->checkExpId());
    $user->expId = 'A1131299';
    var_dump($user->checkExpId());
    
    $user->expId = 'A1132100';
    var_dump($user->checkExpId());
    $user->expId = 'A1132105';
    var_dump($user->checkExpId());
    $user->expId = 'A1132180';
    var_dump($user->checkExpId());
    $user->expId = 'A1132199';
    var_dump($user->checkExpId());
    
    $user->expId = 'A1132200';
    var_dump($user->checkExpId());
    $user->expId = 'A1132205';
    var_dump($user->checkExpId());
    $user->expId = 'A1132280';
    var_dump($user->checkExpId());
    $user->expId = 'A1132299';
    var_dump($user->checkExpId());
    
    $user->expId = 'I1131100';
    var_dump($user->checkExpId());
    $user->expId = 'I1131105';
    var_dump($user->checkExpId());
    $user->expId = 'I1131180';
    var_dump($user->checkExpId());
    $user->expId = 'I1131199';
    var_dump($user->checkExpId());
    
    $user->expId = 'I1131200';
    var_dump($user->checkExpId());
    $user->expId = 'I1131205';
    var_dump($user->checkExpId());
    $user->expId = 'I1131280';
    var_dump($user->checkExpId());
    $user->expId = 'I1131299';
    var_dump($user->checkExpId());
    
    $user->expId = 'I1132100';
    var_dump($user->checkExpId());
    $user->expId = 'I1132105';
    var_dump($user->checkExpId());
    $user->expId = 'I1132180';
    var_dump($user->checkExpId());
    $user->expId = 'I1132199';
    var_dump($user->checkExpId());
    
    $user->expId = 'I1132200';
    var_dump($user->checkExpId());
    $user->expId = 'I1132205';
    var_dump($user->checkExpId());
    $user->expId = 'I1132280';
    var_dump($user->checkExpId());
    $user->expId = 'I1132299';
    var_dump($user->checkExpId());
}