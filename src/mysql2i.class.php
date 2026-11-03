<?php

/**
 * @package alvesbeto/mysql2i
 * @see     https://github.com/alvesbeto/mysql2i
 */

if (!class_exists('mysql2i', false)) {

class mysql2i
{
    public static $currObj;
    private static $isClosing = false;

    public static function mysql_affected_rows($link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        }

        if (!$link || !($link instanceof mysqli) || $link->connect_errno || !isset($link->host_info)) {
            return false;
        }

        return mysqli_affected_rows($link);
    }

    public static function mysql_client_encoding($link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        }

        return mysqli_character_set_name($link);
    }

    public static function mysql_close($link = null)
    {
        if (self::$isClosing) {
            return true;
        }

        if (empty($link)) {
            $link = self::$currObj;
        }

        if (!$link || !($link instanceof mysqli) || $link->connect_errno) {
            return true;
        }

        self::$isClosing = true;
        // Do not close the connection here for backward compatibility.
        // The connection is closed automatically at the end of the script.
        return true;
    }

    public static function mysql_connect($host = '', $username = '', $passwd = '', $new_link = false, $client_flags = 0)
    {
        if (self::$currObj instanceof mysqli && !self::$currObj->connect_errno) {
            return self::$currObj;
        }

        $link = mysqli_connect($host, $username, $passwd);
        self::$currObj = $link;
        self::$isClosing = false;

        if ($link && ($link instanceof mysqli) && !$link->connect_errno) {
            $link->set_charset("utf8mb4");
        }

        return $link;
    }

    public static function mysql_create_db($database_name, $link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        }

        $query = "CREATE DATABASE `" . $database_name . "`";
        mysqli_query($link, $query);

        if (empty(mysqli_errno($link))) {
            return true;
        } else {
            return false;
        }
    }

    public static function mysql_data_seek($result, $offset)
    {
        if (!$result || !($result instanceof mysqli_result)) {
            return false;
        }

        return mysqli_data_seek($result, $offset);
    }

    public static function mysql_db_name($result, $row, $field = null)
    {
        if (!$result || !($result instanceof mysqli_result)) {
            return false;
        }

        mysqli_data_seek($result, $row);

        $f = mysqli_fetch_row($result);

        return $f[0];
    }

    public static function mysql_db_query($database, $query, $link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        }
        mysqli_select_db($link, $database);
        $r = mysqli_query($link, $query);

        return $r;
    }

    public static function mysql_drop_db($database, $link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        }
        $query = "DROP DATABASE `" . $database . "`";
        mysqli_query($link, $query);

        if (empty(mysqli_errno($link))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Normalizes a legacy mysql_* link to mysqli (PHP 8 / mysql2i).
     *
     * @param mixed $link
     * @return mysqli|null
     */
    public static function resolveMysqliLink($link = null)
    {
        if ($link instanceof mysqli) {
            return $link;
        }

        if (self::$currObj instanceof mysqli) {
            return self::$currObj;
        }

        return null;
    }

    /**
     * Runs a script with multiple statements (mysqli_multi_query).
     *
     * @param mixed  $link
     * @param string $script
     * @return bool
     */
    public static function mysql_multi_query_script($link, $script)
    {
        $mysqli = self::resolveMysqliLink($link);
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

    public static function mysql_errno($link = null)
    {
        $mysqli = self::resolveMysqliLink($link);
        if ($mysqli instanceof mysqli) {
            return mysqli_errno($mysqli);
        }

        return 0;
    }

    public static function mysql_error($link = null)
    {
        $mysqli = self::resolveMysqliLink($link);
        if ($mysqli instanceof mysqli) {
            return mysqli_error($mysqli);
        }

        return '';
    }

    public static function mysql_escape_string($escapestr)
    {
        // Unsafe and deprecated — use mysql_real_escape_string() or prepared statements.
        $link = self::$currObj;

        if (!$link) {
            return addslashes($escapestr);
        }

        return mysqli_real_escape_string($link, $escapestr);
    }

    public static function mysql_fetch_array($result, $resulttype = MYSQLI_BOTH)
    {
        if (!($result instanceof mysqli_result)) {
            trigger_error('mysql_fetch_array(): supplied resource is not a valid MySQL result resource', E_USER_WARNING);
            return false;
        }
        $row = mysqli_fetch_array($result, $resulttype);

        return $row === null ? false : $row;
    }

    public static function mysql_fetch_assoc($result)
    {
        if (!$result || !($result instanceof mysqli_result)) {
            return false;
        }

        $row = mysqli_fetch_assoc($result);

        return $row === null ? false : $row;
    }

    public static function mysql_fetch_field($result, $field_offset = null)
    {
        if (!empty($field_offset)) {
            for ($x = 0; $x < $field_offset; $x++) {
                mysqli_fetch_field($result);
            }
        }

        return mysqli_fetch_field($result);
    }

    public static function mysql_fetch_lengths($result)
    {
        return mysqli_fetch_lengths($result);
    }

    public static function mysql_fetch_object($result, $class_name = 'stdClass', $params = [])
    {
        if (!$result || !($result instanceof mysqli_result)) {
            return false;
        }

        if (empty($params)) {
            $row = mysqli_fetch_object($result, $class_name);
        } else {
            $row = mysqli_fetch_object($result, $class_name, $params);
        }

        return $row === null ? false : $row;
    }

    public static function mysql_fetch_row($result)
    {
        $row = mysqli_fetch_row($result);

        return $row === null ? false : $row;
    }

    /*
    credit to andre at koethur dot de from php.net and NinjaKC from stackoverflow.com
    */
    public static function mysql_field_flags($result, $field_offset)
    {
        static $flags;

        $flags_num = mysqli_fetch_field_direct($result, $field_offset)->flags;

        if (!isset($flags)) {
            $flags = array();
            $constants = get_defined_constants(true);
            foreach ($constants['mysqli'] as $c => $n) {
                if (preg_match('/MYSQLI_(.*)_FLAG$/', $c, $m)) {
                    if (!array_key_exists($n, $flags)) {
                        $flags[$n] = $m[1];
                    }
                }
            }
        }

        $result = array();
        foreach ($flags as $n => $t) {
            if ($flags_num & $n) {
                $result[] = $t;
            }
        }

        $return = implode(' ', $result);
        $return = str_replace('PRI_KEY', 'PRIMARY_KEY', $return);
        $return = strtolower($return);

        return $return;
    }

    public static function mysql_field_len($result, $field_offset)
    {
        $fieldInfo = mysqli_fetch_field_direct($result, $field_offset);

        return $fieldInfo->length;
    }

    public static function mysql_field_name($result, $field_offset)
    {
        $fieldInfo = mysqli_fetch_field_direct($result, $field_offset);

        return $fieldInfo->name;
    }

    public static function mysql_field_seek($result, $fieldnr)
    {
        return mysqli_field_seek($result, $fieldnr);
    }

    public static function mysql_field_table($result, $field_offset)
    {
        $fieldInfo = mysqli_fetch_field_direct($result, $field_offset);

        return $fieldInfo->table;
    }

    /*
    credit to andre at koethur dot de from php.net and NinjaKC from stackoverflow.com
    */
    public static function mysql_field_type($result, $field_offset)
    {
        static $types;

        $type_id = mysqli_fetch_field_direct($result, $field_offset)->type;

        if (!isset($types)) {
            $types = array();
            $constants = get_defined_constants(true);
            foreach ($constants['mysqli'] as $c => $n) {
                if (preg_match('/^MYSQLI_TYPE_(.*)/', $c, $m)) {
                    $types[$n] = $m[1];
                }
            }
        }

        return array_key_exists($type_id, $types) ? $types[$type_id] : null;
    }

    public static function mysql_free_result($result)
    {
        if (!$result || !($result instanceof mysqli_result)) {
            return true;
        }

        mysqli_free_result($result);

        return true;
    }

    public static function mysql_get_client_info()
    {
        $link = self::$currObj;

        return mysqli_get_client_info($link);
    }

    public static function mysql_get_host_info($link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        }

        return mysqli_get_host_info($link);
    }

    public static function mysql_get_proto_info($link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        }

        return mysqli_get_proto_info($link);
    }

    public static function mysql_get_server_info($link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        }

        return mysqli_get_server_info($link);
    }

    public static function mysql_info($link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        }

        return mysqli_info($link);
    }

    public static function mysql_insert_id($link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        }

        return mysqli_insert_id($link);
    }

    public static function mysql_list_dbs($link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        }

        if (!$link || !($link instanceof mysqli) || $link->connect_errno) {
            return false;
        }

        $query = "SHOW DATABASES";
        $r = mysqli_query($link, $query);

        if (empty(mysqli_errno($link))) {
            return $r;
        } else {
            return false;
        }
    }

    public static function mysql_list_fields($database_name, $table_name, $link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        }
        $query = "SHOW COLUMNS FROM `" . $table_name . "`";
        $r = mysqli_query($link, $query);

        if (empty(mysqli_errno($link))) {
            return $r;
        } else {
            return false;
        }
    }

    public static function mysql_list_processes($link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        }

        return mysqli_thread_id($link);
    }

    public static function mysql_list_tables($database, $link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        }
        $query = "SHOW TABLES FROM `" . $database . "`";
        $r = mysqli_query($link, $query);

        if (empty(mysqli_errno($link))) {
            return $r;
        } else {
            return false;
        }
    }

    public static function mysql_num_fields($result)
    {
        if (!$result || !($result instanceof mysqli_result)) {
            return false;
        }

        return mysqli_num_fields($result);
    }

    public static function mysql_num_rows($result)
    {
        if (!$result || !($result instanceof mysqli_result)) {
            return false;
        }

        return mysqli_num_rows($result);
    }

    public static function mysql_pconnect($host = '', $username = '', $passwd = '', $new_link = false, $client_flags = 0)
    {
        $link = mysqli_connect('p:' . $host, $username, $passwd);
        if (!$link) {
            $errorMsg = mysqli_connect_error();
            error_log('MySQL Persistent Connection Error: ' . $errorMsg);
            echo $errorMsg . '<br>';
        } else {
            // Set charset on persistent connections as well.
            if ($link instanceof mysqli) {
                $link->set_charset("utf8mb4");
            }
        }
        self::$currObj = $link;

        return $link;
    }

    public static function mysql_ping($link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        }

        if (!$link || !($link instanceof mysqli)) {
            return false;
        }

        if ($link->connect_errno || !isset($link->host_info)) {
            return false;
        }

        $result = @mysqli_query($link, 'SELECT 1');

        if ($result instanceof mysqli_result) {
            mysqli_free_result($result);
        }

        return $result !== false;
    }

    public static function mysql_query($query, $link = null)
    {
        if (empty($query) || !is_string($query)) {
            trigger_error('mysql_query(): Query cannot be empty', E_USER_WARNING);
            return false;
        }

        if (empty($link)) {
            $link = self::$currObj;
        }

        if (!$link || !($link instanceof mysqli) || $link->connect_errno || !isset($link->host_info)) {
            $errorMsg = $link && ($link instanceof mysqli) ? $link->connect_error : 'No MySQL connection';
            error_log('MySQL Connection Error: ' . $errorMsg);
            return false;
        }

        $r = mysqli_query($link, $query);
        return $r;
    }

    public static function mysql_real_escape_string($escapestr, $link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        }

        if (!$link || !($link instanceof mysqli) || self::$isClosing) {
            return addslashes($escapestr); // Fallback when the connection is closed or invalid.
        }

        return mysqli_real_escape_string($link, $escapestr);
    }

    public static function mysql_result($result, $row, $field = null)
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

    public static function mysql_select_db($dbname, $link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        } elseif ($link instanceof mysqli && !$link->connect_errno) {
            self::$currObj = $link;
        }

        if (!$link || !($link instanceof mysqli)) {
            return false;
        }

        if (empty($dbname)) {
            return false;
        }

        try {
            return mysqli_select_db($link, $dbname);
        } catch (Exception $e) {
            return false;
        }
    }

    public static function mysql_set_charset($charset, $link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        }

        return mysqli_set_charset($link, $charset);
    }

    public static function mysql_stat($link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        }

        return mysqli_stat($link);
    }

    public static function mysql_tablename($result, $row, $field = null)
    {
        if (!$result || !($result instanceof mysqli_result)) {
            return false;
        }

        mysqli_data_seek($result, $row);

        $f = mysqli_fetch_array($result);

        return $f[0];
    }

    public static function mysql_thread_id($link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        }

        return mysqli_thread_id($link);
    }

    public static function mysql_unbuffered_query($query, $link = null)
    {
        if (empty($link)) {
            $link = self::$currObj;
        }

        $r = mysqli_query($link, $query, MYSQLI_USE_RESULT);

        return $r;
    }

    /**
     * Compatible with legacy mysql links (resource) and mysqli (PHP 8+ / mysql2i).
     */
    public static function mysql_is_valid_link($link = null)
    {
        if ($link === null || $link === false) {
            $link = self::$currObj;
        }

        if ($link instanceof mysqli) {
            return !$link->connect_errno && isset($link->host_info);
        }

        return is_resource($link);
    }
}

}