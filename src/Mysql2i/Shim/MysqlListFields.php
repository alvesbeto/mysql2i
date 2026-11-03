<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_list_fields().
 */
final class MysqlListFields
{
    public static function invoke($database_name, $table_name, $link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }
        $query = "SHOW COLUMNS FROM `" . $table_name . "`";
        $r = mysqli_query($link, $query);

        if (empty(mysqli_errno($link))) {
            return $r;
        } else {
            return false;
        }
    }
}
