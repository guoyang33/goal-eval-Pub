<?php
namespace Cyouliao\Goaleval;

use PDO;

include_once './../vendor/autoload.php';

class CategoryUsageManualIOS {
    const TABLE_NAME = 'category_usage_manual_ios';

    const COLUMN_ID = 'id';
    const COLUMN_U_ID = 'u_id';
    const COLUMN_CATEGORY = 'category';
    const COLUMN_USAGE_TIME = 'usage_time';
    const COLUMN_SUBMIT_DATE = 'submit_date';
    const COLUMN_WEEK = 'week';
    const COLUMN_MAKEUP = 'makeup';

    const CATEGORY_GAME = 'game';
    const CATEGORY_SOCIAL = 'social';
    const CATEGORY_ENTERTAINMENT = 'entertainment';
    const CATEGORY_TOTAL = 'total';

    private $id = null;
    private $uId = null;
    private $category = null;
    private $usageTime = null;
    private $submitDate = null;
    private $week = null;
    private $makeup = null;

    public static function getUsageTime(int $uId, int $week, string $category): int {
        $tableName = self::TABLE_NAME;
        $columnUsageTime = self::COLUMN_USAGE_TIME;
        $columnUId = self::COLUMN_U_ID;
        $columnWeek = self::COLUMN_WEEK;
        $columnCategory = self::COLUMN_CATEGORY;

        $dbh = DBConnect::getInstance();
        $sth = $dbh->prepare("SELECT $columnUsageTime FROM $tableName WHERE $columnUId = :uId AND $columnWeek = :week AND $columnCategory = :category LIMIT 1;");
        $sth->bindParam(':uId', $uId, PDO::PARAM_INT);
        $sth->bindParam(':week', $week, PDO::PARAM_INT);
        $sth->bindParam(':category', $category, PDO::PARAM_STR);
        $sth->execute();
        
        return intval($sth->fetch(PDO::FETCH_COLUMN));
    }

    public static function getBaseline(int $uId, string $category): int {        
        return self::getUsageTime($uId, 0, $category);
    }
}

// CategoryUsageManualIOS::getBaseline(1, 'aa');