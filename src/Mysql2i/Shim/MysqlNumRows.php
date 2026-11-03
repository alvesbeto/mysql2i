<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;
use mysqli_result;

/**
 * Shim for mysql_num_rows().
 */
final class MysqlNumRows
{
    public static function invoke($result)
    {
        if (!$result || !($result instanceof mysqli_result)) {
            return false;
        }

        return mysqli_num_rows($result);
    }
}
