<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;
use mysqli_result;

/**
 * Shim for mysql_result().
 */
final class MysqlResult
{
    public static function invoke($result, $row, $field = null)
    {
        if (!$result || !($result instanceof mysqli_result)) {
            return false;
        }

        mysqli_data_seek($result, $row);
        if (!empty($field)) {
            mysqli_field_seek($result, 0);
            while ($finfo = mysqli_fetch_field($result)) {
                if ($field == $finfo->name) {
                    $f = mysqli_fetch_assoc($result);
                    return $f[$field];
                }
            }
        }

        $f = mysqli_fetch_array($result);
        return $f[0];
    }
}
