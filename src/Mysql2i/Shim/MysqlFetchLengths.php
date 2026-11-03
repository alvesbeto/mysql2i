<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_fetch_lengths().
 */
final class MysqlFetchLengths
{
    public static function invoke($result)
    {
        return mysqli_fetch_lengths($result);
    }
}
