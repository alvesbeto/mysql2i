<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;
use mysqli_result;

/**
 * Shim for mysql_tablename().
 */
final class MysqlTablename
{
    public static function invoke($result, $row, $field = null)
    {
        if (!$result || !($result instanceof mysqli_result)) {
            return false;
        }

        mysqli_data_seek($result, $row);

        $f = mysqli_fetch_array($result);

        return $f[0];
    }
}
