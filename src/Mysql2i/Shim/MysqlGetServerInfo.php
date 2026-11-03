<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_get_server_info().
 */
final class MysqlGetServerInfo
{
    public static function invoke($link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        return mysqli_get_server_info($link);
    }
}
