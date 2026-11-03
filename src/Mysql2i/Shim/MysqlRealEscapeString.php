<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_real_escape_string().
 */
final class MysqlRealEscapeString
{
    public static function invoke($escapestr, $link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        if (!$link || !($link instanceof mysqli) || LinkContext::isClosing()) {
            return addslashes($escapestr); // Fallback when the connection is closed or invalid.
        }

        return mysqli_real_escape_string($link, $escapestr);
    }
}
