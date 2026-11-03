<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_db_query().
 */
final class MysqlDbQuery
{
    public static function invoke($database, $query, $link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }
        mysqli_select_db($link, $database);
        $r = mysqli_query($link, $query);

        return $r;
    }
}
