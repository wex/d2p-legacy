<?php
declare(strict_types=1);

namespace Wex\ActiveRecord;

use \Wex\ActiveRecord\Blueprint\Column;

class Blueprint
{
    protected   $_table;
    protected   $_columns = [];

    public function __construct(string $table)
    {
        $this->_table = $table;
    }

    public function &timestamp(string $name)
    {
        $index = count( $this->_columns );
        $this->_columns[ $index ] = new Column\Timestamp($name);

        return $this->_columns[ $index ];
    }

    public function &string(string $name)
    {
        $index = count( $this->_columns );
        $this->_columns[ $index ] = new Column\Varchar($name);

        return $this->_columns[ $index ];
    }
}