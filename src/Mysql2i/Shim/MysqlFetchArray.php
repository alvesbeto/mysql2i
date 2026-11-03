<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;
use mysqli_result;

/**
 * Shim for mysql_fetch_array().
 */
final class MysqlFetchArray
{
    public static function invoke($result, $resulttype = MYSQLI_BOTH)
    {
        if (!($result instanceof mysqli_result)) {
            trigger_error('mysql_fetch_array(): supplied resource is not a valid MySQL result resource', E_USER_WARNING);
            return false;
        }
        $row = mysqli_fetch_array($result, $resulttype);

        return $row === null ? false : $row;
    }
}
