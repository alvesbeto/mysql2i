<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_drop_db().
 */
final class MysqlDropDb
{
    public static function invoke($database, $link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }
        $query = "DROP DATABASE `" . $database . "`";
        mysqli_query($link, $query);

        if (empty(mysqli_errno($link))) {
            return true;
        } else {
            return false;
        }
    }
}
