<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_close().
 */
final class MysqlClose
{
    public static function invoke($link = null)
    {
        if (LinkContext::isClosing()) {
            return true;
        }

        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        if (!$link || !($link instanceof mysqli) || $link->connect_errno) {
            return true;
        }

        LinkContext::setClosing(true);
        // Do not close the connection here for backward compatibility.
        // The connection is closed automatically at the end of the script.
        return true;
    }
}
