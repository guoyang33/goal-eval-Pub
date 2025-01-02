<?php

namespace Cyouliao\Goaleval;

include_once './../vendor/autoload.php';

use Cyouliao\Goaleval\DBConnect;
use Exception;
use PDO;

class ValidDays {
    const TABLE_NAME = 'valid_days';
    const COLUMN_ID = 'id';
    const COLUMN_USER_ID = 'u_id';
    const COLUMN_PROVIDE_DAYS = 'provide_days';
    const COLUMN_SOURCE_FILE = 'source_file';
    const COLUMN_SOURCE_OBJECT = 'source_object';
    const COLUMN_WEEK = 'week';
    const COLUMN_DATE = 'date';

    private $id;
    private $userId;
    private $provideDays;
    private $sourceFile;
    private $sourceObject;
    private $week;
    private $date;

    public function __construct() {
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function insert() {
        $dbh = DBConnect::getInstance();
        $sth = $dbh->prepare("INSERT INTO 
                                    `".self::TABLE_NAME."`
                                    (
                                        `".self::COLUMN_ID."`,
                                        `".self::COLUMN_USER_ID."`,
                                        `".self::COLUMN_PROVIDE_DAYS."`,
                                        `".self::COLUMN_SOURCE_FILE."`,
                                        `".self::COLUMN_SOURCE_OBJECT."`,
                                        `".self::COLUMN_WEEK."`,
                                        `".self::COLUMN_DATE."`
                                    )
                                    VALUES
                                    (
                                        NULL,
                                        :u_id,
                                        :provide_days,
                                        :source_file,
                                        :source_object,
                                        :week,
                                        :date
                                    );");
        $sth->execute([
            'u_id' => $this->userId,
            'provide_days' => $this->provideDays,
            'source_file' => $this->sourceFile,
            'source_object' => $this->sourceObject,
            'week' => $this->week,
            'date' => $this->date
        ]);
        return $dbh->lastInsertId();
    }

    public static function getSourceFilesByUserId($userId) {
        $dbh = DBConnect::getInstance();
        $sth = $dbh->prepare("SELECT `source_file` FROM `".self::TABLE_NAME."` WHERE `".self::COLUMN_USER_ID."` = :user_id;");
        $sth->execute([ 'user_id' => $userId ]);
        return $sth->fetchAll(PDO::FETCH_COLUMN, 0);
    }
}

// testGetSourceFilesByUserId();

function testGetSourceFilesByUserId() {
    var_dump(ValidDays::getSourceFilesByUserId(1));
}