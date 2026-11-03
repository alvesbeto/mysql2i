<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_affected_rows().
 */
final class MysqlAffectedRows
{
    public static function invoke($link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        if (!$link || !($link instanceof mysqli) || $link->connect_errno || !isset($link->host_info)) {
            return false;
        }

        return mysqli_affected_rows($link);
    }
}
