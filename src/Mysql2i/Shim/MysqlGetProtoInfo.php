<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_get_proto_info().
 */
final class MysqlGetProtoInfo
{
    public static function invoke($link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        return mysqli_get_proto_info($link);
    }
}
