<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_get_client_info().
 */
final class MysqlGetClientInfo
{
    public static function invoke()
    {
        $link = LinkContext::getCurrObj();

        return mysqli_get_client_info($link);
    }
}
