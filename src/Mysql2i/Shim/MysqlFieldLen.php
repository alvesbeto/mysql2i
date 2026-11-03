<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_field_len().
 */
final class MysqlFieldLen
{
    public static function invoke($result, $field_offset)
    {
        $fieldInfo = mysqli_fetch_field_direct($result, $field_offset);

        return $fieldInfo->length;
    }
}
