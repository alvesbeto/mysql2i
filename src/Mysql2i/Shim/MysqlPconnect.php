<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_pconnect().
 */
final class MysqlPconnect
{
    public static function invoke($host = '', $username = '', $passwd = '', $new_link = false, $client_flags = 0)
    {
        $link = mysqli_connect('p:' . $host, $username, $passwd);
        if (!$link) {
            $errorMsg = mysqli_connect_error();
            error_log('MySQL Persistent Connection Error: ' . $errorMsg);
            echo $errorMsg . '<br>';
        }
        LinkContext::setCurrObj($link);

        return $link;
    }
}
