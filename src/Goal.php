<?php
namespace Cyouliao\Goaleval;

use Exception;
use PDO;
use PDOException;

include_once './../vendor/autoload.php';

class Goal {

    const TABLE_NAME = 'goal';
    
    const COLUMN_ID                         = 'id';
    const COLUMN_U_ID                       = 'u_id';
    const COLUMN_WEEK                       = 'week';
    const COLUMN_START_DATE                 = 'start_date';
    const COLUMN_REDUCE_GAME                = 'reduce_game';
    const COLUMN_REDUCE_VIDEO               = 'reduce_video';
    const COLUMN_REDUCE_SOCIAL              = 'reduce_social';
    const COLUMN_REDUCE_COMMUNICATION       = 'reduce_communication';
    const COLUMN_REDUCE_ENTERTAINMENT       = 'reduce_entertainment';
    const COLUMN_REDUCE_TOTAL               = 'reduce_total';
    const COLUMN_SCORE_GAME                 = 'score_game';
    const COLUMN_SCORE_VIDEO                = 'score_video';
    const COLUMN_SCORE_SOCIAL               = 'score_social';
    const COLUMN_SCORE_COMMUNICATION        = 'score_communication';
    const COLUMN_SCORE_ENTERTAINMENT        = 'score_entertainment';
    const COLUMN_SCORE_TOTAL                = 'score_total';
    const COLUMN_SAVE_GAME                  = 'save_game';
    const COLUMN_SAVE_VIDEO                 = 'save_video';
    const COLUMN_SAVE_SOCIAL                = 'save_social';
    const COLUMN_SAVE_COMMUNICATION         = 'save_communication';
    const COLUMN_SAVE_ENTERTAINMENT         = 'save_entertainment';
    const COLUMN_SAVE_TOTAL                 = 'save_total';
    const COLUMN_SCORE_SAVE_GAME            = 'score_save_game';
    const COLUMN_SCORE_SAVE_VIDEO           = 'score_save_video';
    const COLUMN_SCORE_SAVE_SOCIAL          = 'score_save_social';
    const COLUMN_SCORE_SAVE_COMMUNICATION   = 'score_save_communication';
    const COLUMN_SCORE_SAVE_ENTERTAINMENT   = 'score_save_entertainment';
    const COLUMN_SCORE_SAVE_TOTAL            = 'score_save_total';

    private $id = null;
    private $uId = null;
    private $week = null;
    private $startDate = null;
    private $reduceGame = null;
    private $reduceVideo = null;
    private $reduceSocial = null;
    private $reduceCommunication = null;
    private $reduceEntertainment = null;
    private $reduceTotal = null;
    private $scoreGame = null;
    private $scoreVideo = null;
    private $scoreSocial = null;
    private $scoreCommunication = null;
    private $scoreEntertainment = null;
    private $scoreTotal = null;
    private $saveGame = null;
    private $saveVideo = null;
    private $saveSocial = null;
    private $saveCommunication = null;
    private $saveEntertainment = null;
    private $saveTotal = null;
    private $scoreSaveGame = null;
    private $scoreSaveVideo = null;
    private $scoreSaveSocial = null;
    private $scoreSaveCommunication = null;
    private $scoreSaveEntertainment = null;
    private $scoreSaveTota = null;

    function __construct() {
        
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public static function rowToInstance(array $row=[]): Goal {
        $goal = new self();

        $goal->id = $row['id'] ?? null;
        $goal->uId = $row['uId'] ?? null;
        $goal->week = $row['week'] ?? null;
        $goal->startDate = $row['startDate'] ?? null;
        $goal->reduceGame = $row['reduceGame'] ?? null;
        $goal->reduceVideo = $row['reduceVideo'] ?? null;
        $goal->reduceSocial = $row['reduceSocial'] ?? null;
        $goal->reduceCommunication = $row['reduceCommunication'] ?? null;
        $goal->reduceEntertainment = $row['reduceEntertainment'] ?? null;
        $goal->reduceTotal = $row['reduceTotal'] ?? null;
        $goal->scoreGame = $row['scoreGame'] ?? null;
        $goal->scoreVideo = $row['scoreVideo'] ?? null;
        $goal->scoreSocial = $row['scoreSocial'] ?? null;
        $goal->scoreCommunication = $row['scoreCommunication'] ?? null;
        $goal->scoreEntertainment = $row['scoreEntertainment'] ?? null;
        $goal->scoreTotal = $row['scoreTotal'] ?? null;
        $goal->saveGame = $row['saveGame'] ?? null;
        $goal->saveVideo = $row['saveVideo'] ?? null;
        $goal->saveSocial = $row['saveSocial'] ?? null;
        $goal->saveCommunication = $row['saveCommunication'] ?? null;
        $goal->saveEntertainment = $row['saveEntertainment'] ?? null;
        $goal->saveTotal = $row['saveTotal'] ?? null;
        $goal->scoreSaveGame = $row['scoreSaveGame'] ?? null;
        $goal->scoreSaveVideo = $row['scoreSaveVideo'] ?? null;
        $goal->scoreSaveSocial = $row['scoreSaveSocial'] ?? null;
        $goal->scoreSaveCommunication = $row['scoreSaveCommunication'] ?? null;
        $goal->scoreSaveEntertainment = $row['scoreSaveEntertainment'] ?? null;
        $goal->scoreSaveTota = $row['scoreSaveTota'] ?? null;

        return $goal;
    }

    public static function calculateScore(int $uId, bool $isIOS, int $week, string $category, string $columnReduce, string $columnScore): int {
        if ($isIOS) {
            $baseline = CategoryUsageManualIOS::getBaseline($uId, $category);
            $usageTime = CategoryUsageManualIOS::getUsageTime($uId, $week, $category);

        } else {
            $baseline = CategoryUsageManualAndroid::getBaseline($uId, $category);
            $usageTime = CategoryUsageManualAndroid::getUsageTime($uId, $week, $category);
            
        }
        Log::d("Goal::calculateScore category:{$category}, baseline: {$baseline}, usageTime: {$usageTime}");

        $tableName = self::TABLE_NAME;
        $columnUId = self::COLUMN_U_ID;
        $columnWeek = self::COLUMN_WEEK;

        $dbh = DBConnect::getInstance();
        $sth = $dbh->prepare("SELECT
                                ((EXP(SUM(LOG(1 - ($columnReduce * 0.01)))) * :baseline) >= :usageTime) * 100
                            FROM
                                $tableName
                            WHERE
                                ($columnUId = :uId AND $columnScore > 69)
                                OR ($columnUId = :uId AND $columnWeek = :week);");
        $sth->bindParam(':baseline', $baseline, PDO::PARAM_INT);
        $sth->bindParam(':usageTime', $usageTime, PDO::PARAM_INT);
        $sth->bindParam(':uId', $uId, PDO::PARAM_INT);
        $sth->bindParam(':week', $week, PDO::PARAM_INT);
        $sth->execute();

        $score = intval($sth->fetchColumn());
        Log::d("score: $score");

        return $score;
    }

    public static function updateScore(int $uId=null, int $week=null, bool $isIOS=null): bool {
        $tableName = self::TABLE_NAME;
        $columnScoreGame = self::COLUMN_SCORE_GAME;
        $columnScoreVideo = self::COLUMN_SCORE_VIDEO;
        $columnScoreCommunication = self::COLUMN_SCORE_COMMUNICATION;
        $columnScoreEntertainment = self::COLUMN_SCORE_ENTERTAINMENT;
        $columnScoreSocial = self::COLUMN_SCORE_SOCIAL;
        $columnScoreTotal = self::COLUMN_SCORE_TOTAL;
        $columnUId = self::COLUMN_U_ID;
        $columnWeek = self::COLUMN_WEEK;
        
        $success = false;

        if (is_null($uId) || is_null($week) || is_null($isIOS)) {
            return false;
        }

        try {
            $dbh = DBConnect::getInstance();

            if ($isIOS) {
                $sth = $dbh->prepare("UPDATE $tableName SET $columnScoreGame = :scoreGame, $columnScoreSocial = :scoreSocial, $columnScoreEntertainment = :scoreEntertainment, $columnScoreTotal = :scoreTotal WHERE $columnUId = :uId AND $columnWeek = :week");
                $sth->bindParam(':scoreGame', self::calculateScore($uId, $isIOS, $week, CategoryUsageManualIOS::CATEGORY_GAME, self::COLUMN_REDUCE_GAME, self::COLUMN_SCORE_GAME), PDO::PARAM_INT);
                $sth->bindParam(':scoreSocial', self::calculateScore($uId, $isIOS, $week, CategoryUsageManualIOS::CATEGORY_SOCIAL, self::COLUMN_REDUCE_SOCIAL, self::COLUMN_SCORE_SOCIAL), PDO::PARAM_INT);
                $sth->bindParam(':scoreEntertainment', self::calculateScore($uId, $isIOS, $week, CategoryUsageManualIOS::CATEGORY_ENTERTAINMENT, self::COLUMN_REDUCE_ENTERTAINMENT, self::COLUMN_SCORE_ENTERTAINMENT), PDO::PARAM_INT);
                $sth->bindParam(':scoreTotal', self::calculateScore($uId, $isIOS, $week, CategoryUsageManualIOS::CATEGORY_TOTAL, self::COLUMN_REDUCE_TOTAL, self::COLUMN_SCORE_TOTAL), PDO::PARAM_INT);
                $sth->bindParam(':uId', $uId, PDO::PARAM_INT);
                $sth->bindParam(':week', $week, PDO::PARAM_INT);

            } else {
                $sth = $dbh->prepare("UPDATE $tableName SET $columnScoreGame = :scoreGame, $columnScoreSocial = :scoreSocial, $columnScoreCommunication = :scoreCommunication, $columnScoreVideo = :scoreVideo, $columnScoreTotal = :scoreTotal WHERE $columnUId = :uId AND $columnWeek = :week");
                $sth->bindParam(':scoreGame', self::calculateScore($uId, $isIOS, $week, CategoryUsageManualAndroid::CATEGORY_GAME, self::COLUMN_REDUCE_GAME, self::COLUMN_SCORE_GAME), PDO::PARAM_INT);
                $sth->bindParam(':scoreVideo', self::calculateScore($uId, $isIOS, $week, CategoryUsageManualAndroid::CATEGORY_VIDEO, self::COLUMN_REDUCE_VIDEO, self::COLUMN_SCORE_VIDEO), PDO::PARAM_INT);
                $sth->bindParam(':scoreCommunication', self::calculateScore($uId, $isIOS, $week, CategoryUsageManualAndroid::CATEGORY_COMMUNICATION, self::COLUMN_REDUCE_COMMUNICATION, self::COLUMN_SCORE_COMMUNICATION), PDO::PARAM_INT);
                $sth->bindParam(':scoreSocial', self::calculateScore($uId, $isIOS, $week, CategoryUsageManualAndroid::CATEGORY_SOCIAL, self::COLUMN_REDUCE_SOCIAL, self::COLUMN_SCORE_SOCIAL), PDO::PARAM_INT);
                $sth->bindParam(':scoreTotal', self::calculateScore($uId, $isIOS, $week, CategoryUsageManualAndroid::CATEGORY_TOTAL, self::COLUMN_REDUCE_TOTAL, self::COLUMN_SCORE_TOTAL), PDO::PARAM_INT);
                $sth->bindParam(':uId', $uId, PDO::PARAM_INT);
                $sth->bindParam(':week', $week, PDO::PARAM_INT);
                
            }

            $sth->execute();
            
            $rowCount = intval($sth->rowCount());
            Log::d("row count: $rowCount");
            
            $success = ($sth->rowCount() >= 1);
            Log::d("success: $success");

        } catch (PDOException $e) {
            Log::d("PDOException: " . var_export($e->getMessage(), true));
            
        } catch(Exception $e) {
            Log::d("Exception: " . var_export($e->getMessage(), true));
            
        } finally {
            return $success;
        }
    }

    public static function calculateReduceRatio(int $uId, int $week, string $columnReduce, string $columnScore): float {
        $tableName = self::TABLE_NAME;
        $columnUId = self::COLUMN_U_ID;
        $columnWeek = self::COLUMN_WEEK;

        $dbh = DBConnect::getInstance();
        $sth = $dbh->prepare("SELECT
                                EXP(
                                    SUM(
                                        LOG(
                                            1 - ($columnReduce * 0.01)
                                        )
                                    )
                                )
                            FROM
                                $tableName
                            WHERE
                                ($columnUId = :uId AND $columnScore > 69)
                                OR
                                ($columnUId = :uId AND $columnWeek = :week);");
        $sth->bindParam(':uId', $uId, PDO::PARAM_INT);
        $sth->bindParam(':week', $week, PDO::PARAM_INT);
        
        $sth->execute();

        $reduceRatio = floatval($sth->fetchColumn());
        Log::d("reduce ratio ($columnReduce): $reduceRatio");

        return $reduceRatio;
    }

    public static function calculateReduceRatioSave(int $uId, int $week, string $columnReduce, string $columnScore): float {
        $tableName = self::TABLE_NAME;
        $columnUId = self::COLUMN_U_ID;
        $columnWeek = self::COLUMN_WEEK;

        $lastWeek = $week - 1;

        $dbh = DBConnect::getInstance();
        $sql = "SELECT
                    IFNULL(
                        EXP(
                            SUM(
                                LOG(
                                    1 - ($columnReduce * 0.01)
                                )
                            )
                        )
                    , 1)
                FROM
                    $tableName
                WHERE
                    ($columnUId = :uId AND $columnScore > 69 AND $columnWeek < :week)
                    OR
                    ($columnUId = :uId AND $columnWeek = :lastWeek);";
        
        $sth = $dbh->prepare($sql);

        $sth->bindParam(':uId', $uId, PDO::PARAM_INT);
        $sth->bindParam(':week', $week, PDO::PARAM_INT);
        $sth->bindParam(':lastWeek', $lastWeek, PDO::PARAM_INT);

        $sth->execute();

        $reduceRatioSave = floatval($sth->fetchColumn());

        return $reduceRatioSave;
    }

    public static function calculateScoreSave(int $uId, bool $isIOS, int $week, string $category, string $columnReduce, string $columnScore): int {
        Log::d("call: calculateScoreSave");

        $reduceRatio = self::calculateReduceRatio($uId, $week, $columnReduce, $columnScore);
        $reduceRatioSave = self::calculateReduceRatioSave($uId, $week, $columnReduce, $columnScore);
        if ($isIOS) {
            $baseline = CategoryUsageManualIOS::getBaseline($uId, $category);
            $usageTime = CategoryUsageManualIOS::getUsageTime($uId, $week, $category);
            $usageTimeLastWeek = CategoryUsageManualIOS::getUsageTime($uId, ($week - 1), $category);
            
        } else {
            $baseline = CategoryUsageManualAndroid::getBaseline($uId, $category);
            $usageTime = CategoryUsageManualAndroid::getUsageTime($uId, $week, $category);
            $usageTimeLastWeek = CategoryUsageManualAndroid::getUsageTime($uId, ($week - 1), $category);
            
        }

        $dbh = DBConnect::getInstance();
        $sth = $dbh->prepare("SELECT (
                                GREATEST(
                                    (
                                        :reduceRatio * :baseline
                                    ) - (
                                        :usageTimeLastWeek - (
                                            :reduceRatioSave * :baseline
                                        )
                                    )
                                , 0) >= :usageTime
                            ) * 100;");

        $sth->bindParam(':reduceRatio', $reduceRatio, PDO::PARAM_STR);
        $sth->bindParam(':baseline', $baseline, PDO::PARAM_INT);
        $sth->bindParam(':usageTimeLastWeek', $usageTimeLastWeek, PDO::PARAM_INT);
        $sth->bindParam(':reduceRatioSave', $reduceRatioSave, PDO::PARAM_STR);
        $sth->bindParam(':usageTime', $usageTime, PDO::PARAM_INT);

        $sth->execute();

        $scoreSave = intval($sth->fetchColumn());
        Log::d("score save ($category): $scoreSave");

        return $scoreSave;
    }

    public static function updateScoreSave(int $uId=null, int $week=null, bool $isIOS=null): bool {
        Log::d("uId: $uId; week: $week; isIOS: $isIOS");

        if (is_null($uId) || is_null($week) || is_null($isIOS)) {
            return false;
        }
        
        $tableName = self::TABLE_NAME;
        $columnScoreSaveGame = self::COLUMN_SCORE_SAVE_GAME;
        $columnScoreSaveVideo = self::COLUMN_SCORE_SAVE_VIDEO;
        $columnScoreSaveCommunication = self::COLUMN_SCORE_SAVE_COMMUNICATION;
        $columnScoreSaveEntertainment = self::COLUMN_SCORE_SAVE_ENTERTAINMENT;
        $columnScoreSaveSocial = self::COLUMN_SCORE_SAVE_SOCIAL;
        $columnScoreSaveTotal = self::COLUMN_SCORE_SAVE_TOTAL;
        $columnUId = self::COLUMN_U_ID;
        $columnWeek = self::COLUMN_WEEK;
        
        $success = false;

        $lastWeek = $week - 1;

        try {
            $dbh = DBConnect::getInstance();

            if ($isIOS) {
                $sth = $dbh->prepare("UPDATE $tableName SET $columnScoreSaveGame = :scoreSaveGame, $columnScoreSaveSocial = :scoreSaveSocial, $columnScoreSaveEntertainment = :scoreSaveEntertainment, $columnScoreSaveTotal = :scoreSaveTotal WHERE $columnUId = :uId AND $columnWeek = :week");
                $sth->bindParam(':scoreSaveGame', self::calculateScoreSave($uId, $isIOS, $week, CategoryUsageManualIOS::CATEGORY_GAME, self::COLUMN_REDUCE_GAME, self::COLUMN_SCORE_GAME), PDO::PARAM_INT);
                $sth->bindParam(':scoreSaveSocial', self::calculateScoreSave($uId, $isIOS, $week, CategoryUsageManualIOS::CATEGORY_SOCIAL, self::COLUMN_REDUCE_SOCIAL, self::COLUMN_SCORE_SOCIAL), PDO::PARAM_INT);
                $sth->bindParam(':scoreSaveEntertainment', self::calculateScoreSave($uId, $isIOS, $week, CategoryUsageManualIOS::CATEGORY_ENTERTAINMENT, self::COLUMN_REDUCE_ENTERTAINMENT, self::COLUMN_SCORE_ENTERTAINMENT), PDO::PARAM_INT);
                $sth->bindParam(':scoreSaveTotal', self::calculateScoreSave($uId, $isIOS, $week, CategoryUsageManualIOS::CATEGORY_TOTAL, self::COLUMN_REDUCE_TOTAL, self::COLUMN_SCORE_TOTAL), PDO::PARAM_INT);

            } else {
                $sth = $dbh->prepare("UPDATE $tableName SET $columnScoreSaveGame = :scoreSaveGame, $columnScoreSaveSocial = :scoreSaveSocial, $columnScoreSaveCommunication = :scoreSaveCommunication, $columnScoreSaveVideo = :scoreSaveVideo, $columnScoreSaveTotal = :scoreSaveTotal WHERE $columnUId = :uId AND $columnWeek = :week");
                $sth->bindParam(':scoreSaveGame', self::calculateScoreSave($uId, $isIOS, $week, CategoryUsageManualAndroid::CATEGORY_GAME, self::COLUMN_REDUCE_GAME, self::COLUMN_SCORE_GAME), PDO::PARAM_INT);
                $sth->bindParam(':scoreSaveVideo', self::calculateScoreSave($uId, $isIOS, $week, CategoryUsageManualAndroid::CATEGORY_VIDEO, self::COLUMN_REDUCE_VIDEO, self::COLUMN_SCORE_VIDEO), PDO::PARAM_INT);
                $sth->bindParam(':scoreSaveCommunication', self::calculateScoreSave($uId, $isIOS, $week, CategoryUsageManualAndroid::CATEGORY_COMMUNICATION, self::COLUMN_REDUCE_COMMUNICATION, self::COLUMN_SCORE_COMMUNICATION), PDO::PARAM_INT);
                $sth->bindParam(':scoreSaveSocial', self::calculateScoreSave($uId, $isIOS, $week, CategoryUsageManualAndroid::CATEGORY_SOCIAL, self::COLUMN_REDUCE_SOCIAL, self::COLUMN_SCORE_SOCIAL), PDO::PARAM_INT);
                $sth->bindParam(':scoreSaveTotal', self::calculateScoreSave($uId, $isIOS, $week, CategoryUsageManualAndroid::CATEGORY_TOTAL, self::COLUMN_REDUCE_TOTAL, self::COLUMN_SCORE_TOTAL), PDO::PARAM_INT);
                
            }
            $sth->bindParam(':uId', $uId, PDO::PARAM_INT);
            $sth->bindParam(':week', $lastWeek, PDO::PARAM_INT);

            $sth->execute();
            
            $success = ($sth->rowCount() >= 1);

        } catch (PDOException $e) {
            Log::d("PDOException: " . var_export($e->getMessage(), true));
            
        } catch(Exception $e) {
            Log::d("Exception: " . var_export($e->getMessage(), true));
            
        } finally {
            Log::d("PDO error info: " . var_export($dbh->errorInfo(), true));

            return $success;
        }
    }

}

/* Testing */
/*
var_dump(Goal::calculateReduceRatio(69, 1, Goal::COLUMN_REDUCE_GAME, Goal::COLUMN_SCORE_GAME));
// */

/*
$goal = Goal::rowToInstance();
var_dump($goal);
var_dump($goal->id);


$goal = new Goal();
var_dump($goal->id);
$goal->id = 99;
var_dump($goal->id);
// */