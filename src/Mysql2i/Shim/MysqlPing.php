<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;
use mysqli_result;

/**
 * Shim for mysql_ping().
 */
final class MysqlPing
{
    public static function invoke($link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        if (!$link || !($link instanceof mysqli)) {
            return false;
        }

        if ($link->connect_errno || !isset($link->host_info)) {
            return false;
        }

        $result = @mysqli_query($link, 'SELECT 1');

        if ($result instanceof mysqli_result) {
            mysqli_free_result($result);
        }

        return $result !== false;
    }
}
