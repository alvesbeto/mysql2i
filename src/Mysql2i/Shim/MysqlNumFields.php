<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;
use mysqli_result;

/**
 * Shim for mysql_num_fields().
 */
final class MysqlNumFields
{
    public static function invoke($result)
    {
        if (!$result || !($result instanceof mysqli_result)) {
            return false;
        }

        return mysqli_num_fields($result);
    }
}
