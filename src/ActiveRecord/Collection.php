<?php
declare(strict_types=1);

namespace Wex\ActiveRecord;

use Wex\ActiveRecord;
use Wex\ActiveRecord\Exception\NotFound;


class Collection implements \Iterator, \ArrayAccess
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

    protected   $__iterator = null;
    protected   $__cache    = null;
    protected   $__data;

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
        return $this->__cache[ $this->__iterator ];
    }

    public function key()
    {
        return $this->__iterator;
    }

    public function next()
    {
        $this->__iterator++;
    }

    public function rewind()
    {
        $this->__iterator   = 0;
        $this->_select      = $this->_class::select();

        if ($this->_joinTable) {
            $this->_select->join(['join_table' => $this->_joinTable], "join_table.{$this->_joinKey} = {$this->_table}.id", []);
            $this->_select->where(["join_table.{$this->_key}" => $this->_parent->id]);
        } else {
            $this->_select->where(["{$this->_table}.{$this->_key}" => $this->_parent->id]);
        }

        $this->_select->limit(1);
        $this->_select->reset('offset');
    }

    public function valid()
    {
        $this->_select->offset( $this->__iterator );

        try {
            $object = $this->_select->first();
        } catch (NotFound $e) {
            return false;
        }

        $this->__cache[ $this->__iterator ] = $object;

        return true;
    }

    public function save()
    {
        throw new \ErrorException("TODO: Implement");
    }

    public function offsetExists($offset)
    {
        $this->__iterator = $offset;

        return $this->valid();
    }

    public function offsetGet($offset)
    {
        $this->__iterator = $offset;

        if (!$this->valid()) {
            throw new NotFound("Entry not found: {$offset}");
        }

        return $this->current();
    }

    public function offsetUnset($offset)
    {
        throw new \ErrorException("TODO: Implement delete from collection [{$offset}]");
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            // Add new
        } else {
            // Update existing (maybe)
        }
        throw new \ErrorException("TODO: Implement set into collection [{$offset} = object]");
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