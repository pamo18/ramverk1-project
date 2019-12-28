<?php

namespace Pamo\MyActiveRecord;

use Anax\DatabaseActiveRecord\Exception\ActiveRecordException;
use Anax\DatabaseActiveRecord\ActiveRecordModel;
use Anax\DatabaseQueryBuilder\DatabaseQueryBuilder;

/**
 * An implementation of the Active Record pattern to be used as
 * base class for database driven models.
 */
class MyActiveRecord extends ActiveRecordModel
{
    /**
     *
     * @param string $where to use in where statement.
     * @param mixed  $value to use in where statement.
     * @param mixed  $orderBy.
     *
     * @return array of object of this class
     */
    public function findAllWhereOrder($where, $value, $orderBy)
    {
        $this->checkDb();
        $params = is_array($value) ? $value : [$value];
        return $this->db->connect()
                        ->select()
                        ->from($this->tableName)
                        ->where($where)
                        ->orderBy($orderBy)
                        ->execute($params)
                        ->fetchAllClass(get_class($this));
    }


    /**
     *
     * @param mixed  $orderBy.
     *
     * @return array of object of this class
     */
    public function findAllOrder($orderBy, $limit = 1000)
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from($this->tableName)
                        ->orderBy($orderBy)
                        ->limit($limit)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }


    /**
     *
     * @param string $select.
     * @param string  $groupBy.
     * @param string  $orderBy.
     * @param int  $limit.
     *
     * @return array of object of this class
     */
    public function findAllGroupOrder($select, $groupBy, $orderBy, $limit = 1000)
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select($select)
                        ->from($this->tableName)
                        ->groupBy($groupBy)
                        ->orderBy($orderBy)
                        ->limit($limit)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }


    /**
     *
     * @param string $count.
     *
     * @return object
     */
    public function count($count = "*")
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select("COUNT ($count) AS count")
                        ->from($this->tableName)
                        ->execute()
                        ->fetch();
    }



    /**
     *
     * @param string $table.
     * @param string  $condition.
     * @param string $groupConcat.
     * @param string  $groupBy.
     * @param string  $sortBy.
     *
     * @return array of object of this class
     */
    public function joinConcatOrder($table, $condition, $groupConcat, $groupBy, $sortBy)
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select("*, " . $groupConcat)
                        ->from($this->tableName)
                        ->join($table, $condition)
                        ->groupBy($groupBy)
                        ->orderBy($sortBy)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }



    /**
     *
     * @param string $where.
     * @param string  $value.
     * @param string $table.
     * @param string  $condition.
     * @param string  $select.
     *
     * @return array of object of this class
     */
    public function joinWhere($where, $value, $table, $condition, $select = "*")
    {
        $this->checkDb();
        $params = is_array($value) ? $value : [$value];
        return $this->db->connect()
                        ->select($select)
                        ->from($this->tableName)
                        ->where($where)
                        ->join($table, $condition)
                        ->execute($params)
                        ->fetchAllClass(get_class($this));
    }



    /**
     *
     * @param string  $where.
     * @param string  $value.
     * @param string $table.
     * @param string  $condition.
     * @param string $groupConcat.
     * @param string  $groupBy.
     * @param string  $orderBy.
     *
     * @return array of object of this class
     */
    public function joinWhereConcatOrder($where, $value, $table, $condition, $groupConcat, $groupBy, $orderBy)
    {
        $this->checkDb();
        $params = is_array($value) ? $value : [$value];
        return $this->db->connect()
                        ->select("*, " . $groupConcat)
                        ->from($this->tableName)
                        ->where($where)
                        ->join($table, $condition)
                        ->groupBy($groupBy)
                        ->orderBy($orderBy)
                        ->execute($params)
                        ->fetchAllClass(get_class($this));
    }
}
