<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_escape_string().
 */
final class MysqlEscapeString
{
    public static function invoke($escapestr)
    {
        // Unsafe and deprecated — use mysql_real_escape_string() or prepared statements.
        $link = LinkContext::getCurrObj();

        if (!$link) {
            return addslashes($escapestr);
        }

        return mysqli_real_escape_string($link, $escapestr);
    }
}
