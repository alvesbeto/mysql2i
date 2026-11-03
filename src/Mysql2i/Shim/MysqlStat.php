<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_stat().
 */
final class MysqlStat
{
    public static function invoke($link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        return mysqli_stat($link);
    }
}
