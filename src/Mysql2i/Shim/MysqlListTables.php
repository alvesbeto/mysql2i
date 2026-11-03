<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_list_tables().
 */
final class MysqlListTables
{
    public static function invoke($database, $link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }
        $query = "SHOW TABLES FROM `" . $database . "`";
        $r = mysqli_query($link, $query);

        if (empty(mysqli_errno($link))) {
            return $r;
        } else {
            return false;
        }
    }
}
