<?php

namespace Cyouliao\Goaleval;

require_once '../vendor/autoload.php';
// require_once '../source/connect_db.php';

use Cyouliao\Goaleval\DBConnect;

class RegisterUser
{

    const TABLE_NAME = 'register_user';
    const COLUMN_ID = 'id';
    const COLUMN_STD_ID = 'std_id';
    const COLUMN_IS_ADDICTED = 'is_addicted';
    const COLUMN_IS_EXPERIMENT = 'is_experiment';

    const IS_ADDICTED_T = 1;
    const IS_ADDICTED_F = 0;

    const IS_EXPERIMENT_T= 1;
    const IS_EXPERIMENT_F = 0;
    private $dbh;

    public function __construct(string $std_id)
    {
        $this->dbh = DBConnect::getInstance();
    }


}