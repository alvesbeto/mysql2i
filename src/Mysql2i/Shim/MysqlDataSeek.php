<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;
use mysqli_result;

/**
 * Shim for mysql_data_seek().
 */
final class MysqlDataSeek
{
    public static function invoke($result, $offset)
    {
        if (!$result || !($result instanceof mysqli_result)) {
            return false;
        }

        return mysqli_data_seek($result, $offset);
    }
}
