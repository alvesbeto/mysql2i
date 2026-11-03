<?php

namespace Mysql2i\Shim;

use Mysql2i\LinkContext;
use mysqli;

/**
 * Shim for mysql_select_db().
 */
final class MysqlSelectDb
{
    public static function invoke($dbname, $link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        } elseif ($link instanceof mysqli && !$link->connect_errno) {
            LinkContext::setCurrObj($link);
        }

        if (!$link || !($link instanceof mysqli)) {
            return false;
        }

        if (empty($dbname)) {
            return false;
        }

        try {
            return mysqli_select_db($link, $dbname);
        } catch (\Exception $e) {
            return false;
        }
    }
}
