<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_list_dbs().
 */
final class MysqlListDbs
{
    public static function invoke($link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        if (!$link || !($link instanceof mysqli) || $link->connect_errno) {
            return false;
        }

        $query = "SHOW DATABASES";
        $r = mysqli_query($link, $query);

        if (empty(mysqli_errno($link))) {
            return $r;
        } else {
            return false;
        }
    }
}
