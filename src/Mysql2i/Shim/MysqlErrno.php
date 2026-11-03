<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use Mysql2i\ResolveMysqliLink;
use mysqli;

/**
 * Shim for mysql_errno().
 */
final class MysqlErrno
{
    public static function invoke($link = null)
    {
        $mysqli = ResolveMysqliLink::invoke($link);
        if ($mysqli instanceof mysqli) {
            return mysqli_errno($mysqli);
        }

        return 0;
    }
}
