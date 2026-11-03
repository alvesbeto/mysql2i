<?php

namespace Mysql2i;

use mysqli;

/**
 * Normalizes a legacy mysql_* link to mysqli (PHP 8 / mysql2i).
 */
final class ResolveMysqliLink
{
    /**
     * @param mixed $link
     */
    public static function invoke($link = null): ?mysqli
    {
        if ($link instanceof mysqli) {
            return $link;
        }

        $curr = LinkContext::getCurrObj();
        if ($curr instanceof mysqli) {
            return $curr;
        }

        return null;
    }
}
