<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_thread_id().
 */
final class MysqlThreadId
{
    public static function invoke($link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        return mysqli_thread_id($link);
    }
}
