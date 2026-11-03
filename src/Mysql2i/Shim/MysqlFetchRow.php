<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_fetch_row().
 */
final class MysqlFetchRow
{
    public static function invoke($result)
    {
        $row = mysqli_fetch_row($result);

        return $row === null ? false : $row;
    }
}
