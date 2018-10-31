<?php
declare(strict_types=1);

namespace Wex\ActiveRecord;

use Wex\ActiveRecord;


class Collection implements \Iterator
{
    protected   $_select;

    protected   $_parent;
    protected   $_class;
    protected   $_table;
    protected   $_name;
    protected   $_key;
    protected   $_joinTable = false;
    protected   $_joinKey   = false;
    protected   $_joinPivot = [];

    public function __construct(ActiveRecord &$parent, string $object, string $name, string $key = null)
    {
        $this->_parent  = $parent;
        $this->_class   = $object;
        $this->_table   = $object::table;
        $this->_name    = $name;
        $this->_key     = $key;
    }

    public function &from(string $foreignKey)
    {
        $this->key = $foreignKey;

        return $this;
    }

    public function &thru(string $tableName, string $foreignKey, array $pivot = [])
    {
        $this->_joinTable   = $tableName;
        $this->_joinKey     = $foreignKey;
        $this->_joinPivot   = $pivot;

        return $this;
    }

    public function current()
    {
        return $this->_select->current();
    }

    public function key()
    {
        return $this->_select->key();
    }

    public function next()
    {
        return $this->_select->next();
    }

    public function rewind()
    {
        $this->_select = $this->_class::select();

        if ($this->_joinTable) {
            $this->_select->join(['join_table' => $this->_joinTable], "join_table.{$this->_joinKey} = {$this->_table}.id", []);
            $this->_select->where(["join_table.{$this->_key}" => $this->_parent->id]);
        } else {
            $this->_select->where(["{$this->_table}.{$this->_key}" => $this->_parent->id]);
        }

        echo $this->_select;

        $this->_select->rewind();
    }

    public function valid()
    {
        return $this->_select->valid();
    }

    public function save()
    {
        throw new \ErrorException("TODO: Implement");
    }

    /**
     * Delete
     * 1. Mark "toBeDeleted"
     * 2. Delete on parent's save
     */

    /**
     * Save
     * 1. Only save dirty objects
     * 2. Save on parent's save
     */

    /**
     * Add
     * 1. Add instance of AnotherActiveRecord
     * 2. Set keys
     * 3. Save on parent's save
     */
}