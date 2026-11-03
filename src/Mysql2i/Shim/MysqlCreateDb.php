<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_create_db().
 */
final class MysqlCreateDb
{
    public static function invoke($database_name, $link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        $query = "CREATE DATABASE `" . $database_name . "`";
        mysqli_query($link, $query);

        if (empty(mysqli_errno($link))) {
            return true;
        } else {
            return false;
        }
    }
}
