<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;
use mysqli_result;

/**
 * Shim for mysql_fetch_assoc().
 */
final class MysqlFetchAssoc
{
    public static function invoke($result)
    {
        if (!$result || !($result instanceof mysqli_result)) {
            return false;
        }

        $row = mysqli_fetch_assoc($result);

        return $row === null ? false : $row;
    }
}
