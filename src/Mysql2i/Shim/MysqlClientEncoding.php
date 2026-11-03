<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_client_encoding().
 */
final class MysqlClientEncoding
{
    public static function invoke($link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        return mysqli_character_set_name($link);
    }
}
