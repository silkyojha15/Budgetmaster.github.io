<?php

namespace ContentEgg\application\models;

/**
 * Model class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link http://www.keywordrush.com/
 * @copyright Copyright &copy; 2015 keywordrush.com
 */
abstract class Model {

    public static $db;
    private static $models = array();
    protected $charset_collate = '';

    abstract public function tableName();

    abstract public function getDump();

    public function __construct()
    {
        if (!empty($this->getDb()->charset))
            $this->charset_collate = 'DEFAULT CHARACTER SET ' . $this->getDb()->charset;
        if (!empty($this->getDb()->collate))
            $this->charset_collate .= ' COLLATE ' . $this->getDb()->collate;
        if (!$this->charset_collate)
            $this->charset_collate = '';
    }

    public function attributeLabels()
    {
        return array();
    }

    public function getDb()
    {
        if (self::$db !== null)
            return self::$db;
        else
        {
            self::$db = $GLOBALS['wpdb'];
            return self::$db;
        }
    }

    public static function model($className = __CLASS__)
    {
        if (isset(self::$models[$className]))
            return self::$models[$className];
        else
        {
            return self::$models[$className] = new $className;
        }
    }

    public function getAttributeLabel($attribute)
    {
        $labels = $this->attributeLabels();
        if (isset($labels[$attribute]))
            return $labels[$attribute];
        else
            return $this->generateAttributeLabel($attribute);
    }

    public function generateAttributeLabel($name)
    {
        return ucwords(trim(strtolower(str_replace(array('-', '_', '.'), ' ', preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $name)))));
    }

    public function findAll(array $params)
    {
        $values = array();
        $sql = 'SELECT ';

        if (!empty($params['select']))
            $sql .= $params['select'];
        else
            $sql .= ' *';
        $sql .= ' FROM ' . $this->tableName();
        if ($params)
        {
            if (!empty($params['where']))
            {
                if (is_array($params['where']) && isset($params['where'][0]) && isset($params['where'][1]))
                {
                    $sql .= ' WHERE ' . $params['where'][0];
                    $values += $params['where'][1];
                } elseif (!is_array($params['where']))
                    $sql .= ' WHERE ' . $params['where'];
            }
            if (!empty($params['order']))
            {
                $sql .= ' ORDER BY ' . $params['order'];
            }
            if (!empty($params['limit']))
            {
                $sql .= ' LIMIT %d';
                $values[] = $params['limit'];
            }
            if (!empty($params['offset']))
            {
                $sql .= ' OFFSET %d';
                $values[] = $params['offset'];
            }

            if ($values)
                $sql = $this->getDb()->prepare($sql, $values);
        }

        return $this->getDb()->get_results($sql, \ARRAY_A);
    }

    public function findByPk($id)
    {
        return $this->getDb()->get_row($this->getDb()->prepare('SELECT * FROM ' . $this->tableName() . ' WHERE id = %d', $id), ARRAY_A);
    }

    public function delete($id)
    {
        return $this->getDb()->delete($this->tableName(), array('id' => $id), array('%d'));
    }

    public function deleteAll($where)
    {
        $values = array();
        $sql = 'DELETE FROM ' . $this->tableName();
        if (is_array($where) && isset($where[0]) && isset($where[1]))
        {
            $sql .= ' WHERE ' . $where[0];
            $values += $where[1];
        } elseif (is_string($where))
            $sql .= ' WHERE ' . $where;
        else
            throw new \Exception('Wrong deleteAll params.');
        if ($values)
            $sql = $this->getDb()->prepare($sql, $values);
        return $this->getDb()->query($sql);
    }

    public function count($where = null)
    {
        $sql = "SELECT COUNT(*) FROM " . $this->tableName();
        if ($where)
            $sql .= ' WHERE ' . $where;

        return $this->getDb()->get_var($sql);
    }

    public function save(array $item)
    {
        $item['id'] = (int) $item['id'];
        if (!$item['id'])
        {
            $item['id'] = 0;
            $this->getDb()->insert($this->tableName(), $item);
            return $this->getDb()->insert_id;
        } else
        {
            $this->getDb()->update($this->tableName(), $item, array('id' => $item['id']));
            return $item['id'];
        }
    }

}
