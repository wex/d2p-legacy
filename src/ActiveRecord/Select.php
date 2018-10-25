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

    public function all() : array
    {
        return array_map(function($v) { return $this->_class::create($v); }, parent::all());
    }

    public function current()
    {
        return $this->_class::create( parent::current() );
    }


}