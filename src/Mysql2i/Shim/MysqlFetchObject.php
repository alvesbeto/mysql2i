<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;
use mysqli_result;

/**
 * Shim for mysql_fetch_object().
 */
final class MysqlFetchObject
{
    public static function invoke($result, $class_name = 'stdClass', $params = [])
    {
        if (!$result || !($result instanceof mysqli_result)) {
            return false;
        }

        if (empty($params)) {
            $row = mysqli_fetch_object($result, $class_name);
        } else {
            $row = mysqli_fetch_object($result, $class_name, $params);
        }

        return $row === null ? false : $row;
    }
}
