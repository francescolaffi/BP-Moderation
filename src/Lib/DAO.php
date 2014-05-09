<?php


namespace BPModeration\Lib;

/**
 * Class DAO
 *
 * WPDB Data Access Object
 *
 * @package BPModeration\Lib
 */
abstract class DAO
{
    const SQL_DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var string
     */
    protected static $table;

    /**
     * @var string
     */
    protected static $idField;

    /**
     *
     * @var array field => format
     */
    protected static $formats = array();

    public function getID()
    {
        return $this->{static::$idField};
    }

    /**
     * Store data with insert/update
     *
     * @return bool
     */
    public function save()
    {
        // prepare args for wpdb insert/update functions
        $values = array();
        $formats = array();

        foreach (static::$formats as $field => $format) {
            if (static::$idField === $field) {
                continue;
            }
            $values[$field] = $this->$field;
            $formats[] = $format;
        }

        if ($this->exists()) {
            // update
            $where = array(static::$idField => $this->{static::$idField});
            $whereFormat = array(static::$formats[static::$idField]);
            $result = self::wpdb()->update(static::table(), $values, $where, $formats, $whereFormat);
        } else {
            // insert
            $result = self::wpdb()->insert(static::table(), $values, $formats);
            if ($result) {
                $this->{static::$idField} = self::wpdb()->insert_id;
            }
        }

        return (bool)$result;
    }

    /**
     * @return bool
     */
    public function delete()
    {
        if (!$this->exists()) {
            return true;
        }
        $where = array(static::$idField => $this->{static::$idField});
        $whereFormat = array(static::$formats[static::$idField]);
        return (bool)self::wpdb()->delete(static::table(), $where, $whereFormat);
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return isset($this->{static::$idField});
    }

    /**
     * @param $id
     * @return bool|static
     */
    public static function findOneByID($id)
    {
        $sql = sprintf('SELECT * FROM `%s` WHERE `%s` = %s', static::table(), static::$idField, static::$formats[static::$idField]);
        $sql = self::wpdb()->prepare($sql, $id);
        $row = self::wpdb()->get_row($sql, ARRAY_A);
        return $row ? static::hydrate($row) : false;
    }

    /**
     * create and hydrate
     *
     * @param $row
     * @return static
     */
    public static function hydrate($row)
    {
        $obj = new static();

        foreach (static::$formats as $field => $format) {
            $obj->$field = isset($row[$field]) ? $row[$field] : null;
        }

        return $obj;
    }

    /**
     * @return string
     */
    public static function table()
    {
        return self::wpdb()->base_prefix . static::$table;
    }

    /**
     * @return \WPDB
     */
    private static function wpdb()
    {
        return $GLOBALS['wpdb'];
    }
} 