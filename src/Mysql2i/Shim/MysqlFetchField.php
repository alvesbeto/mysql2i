<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_fetch_field().
 */
final class MysqlFetchField
{
    public static function invoke($result, $field_offset = null)
    {
        if (!empty($field_offset)) {
            for ($x = 0; $x < $field_offset; $x++) {
                mysqli_fetch_field($result);
            }
        }

        return mysqli_fetch_field($result);
    }
}
