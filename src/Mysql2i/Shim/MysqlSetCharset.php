<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_set_charset().
 */
final class MysqlSetCharset
{
    public static function invoke($charset, $link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        return mysqli_set_charset($link, $charset);
    }
}
