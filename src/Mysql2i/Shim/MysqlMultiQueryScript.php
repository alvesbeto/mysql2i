<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use Mysql2i\ResolveMysqliLink;
use mysqli;

/**
 * Shim for mysql_multi_query_script().
 */
final class MysqlMultiQueryScript
{
    public static function invoke($link, $script)
    {
        $mysqli = ResolveMysqliLink::invoke($link);
        if (!($mysqli instanceof mysqli)) {
            return false;
        }

        if (!mysqli_multi_query($mysqli, $script)) {
            return false;
        }

        do {
            if ($result = mysqli_store_result($mysqli)) {
                mysqli_free_result($result);
            }
            if (mysqli_errno($mysqli)) {
                return false;
            }
        } while (mysqli_more_results($mysqli) && mysqli_next_result($mysqli));

        return mysqli_errno($mysqli) === 0;
    }
}
