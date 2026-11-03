<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_field_seek().
 */
final class MysqlFieldSeek
{
    public static function invoke($result, $fieldnr)
    {
        return mysqli_field_seek($result, $fieldnr);
    }
}
