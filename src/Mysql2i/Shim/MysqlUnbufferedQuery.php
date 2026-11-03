<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_unbuffered_query().
 */
final class MysqlUnbufferedQuery
{
    public static function invoke($query, $link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        $r = mysqli_query($link, $query, MYSQLI_USE_RESULT);

        return $r;
    }
}
