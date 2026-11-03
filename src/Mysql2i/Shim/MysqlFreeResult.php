<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;
use mysqli_result;

/**
 * Shim for mysql_free_result().
 */
final class MysqlFreeResult
{
    public static function invoke($result)
    {
        if (!$result || !($result instanceof mysqli_result)) {
            return true;
        }

        mysqli_free_result($result);

        return true;
    }
}
