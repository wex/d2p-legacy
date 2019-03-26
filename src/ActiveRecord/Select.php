<?php
declare(strict_types=1);

namespace Wex\ActiveRecord;

use Wex\ActiveRecord;
use Wex\Select as RawSelect;

class Select extends RawSelect // \Countable
{
    protected   $_class;
    protected   $_table;
    protected   $_iterator;

    public function __construct(string $class)
    {
        $this->_class   = $class;
        $this->_table   = $class::table;

        parent::__construct( $this->_table );
    }

    public function first() : ActiveRecord
    {
        return $this->_class::create( parent::first() );
    }

    public function fetchFirst() 
    {
        return parent::first();
    }

    public function all() : array
    {
        return array_map(function($v) { return $this->_class::create($v); }, parent::all());
    }

    public function fetchAll()
    {
        return parent::all();
    }

    public function current()
    {
        return $this->_class::create( parent::current() );
    }

    public function getSql()
    {
        return $this->_class::$sql->buildSqlString( $this );
    }

    public function quote($value)
    {
        if (is_array($value)) {
            return $this->_class::$platform->quoteValueList($value);
        } else {
            return $this->_class::$platform->quoteValue($value);
        }
    }

    public function quoteIdentifier($value)
    {
        return $this->_class::$platform->quoteIdentifier($value);
    }

    public function query($sql)
    {
        return $this->_class::$adapter->query($sql);
    }

}