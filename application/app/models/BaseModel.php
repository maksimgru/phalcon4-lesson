<?php

namespace App\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\ResultsetInterface;

/**
 * Class BaseModel
 */
class BaseModel extends Model
{
    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     *
     * @return ResultsetInterface
     */
    public static function find($parameters = null): ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     *
     * @return mixed
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
