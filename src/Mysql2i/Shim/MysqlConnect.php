<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_connect().
 */
final class MysqlConnect
{
    public static function invoke($host = '', $username = '', $passwd = '', $new_link = false, $client_flags = 0)
    {
        if (LinkContext::getCurrObj() instanceof mysqli && !LinkContext::getCurrObj()->connect_errno) {
            return LinkContext::getCurrObj();
        }

        $link = mysqli_connect($host, $username, $passwd);
        LinkContext::setCurrObj($link);
        LinkContext::setClosing(false);

        return $link;
    }
}
