<?php
declare(strict_types=1);

namespace Wex\ActiveRecord;

use \Wex\ActiveRecord\Blueprint\Column;

class Blueprint
{
    public  $table;
    public  $pk         = 'id';
    public  $columns    = [];

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function &timestamp(string $name) : Column
    {
        $this->columns[ $name ] = new Column\Timestamp($name);

        return $this->columns[ $name ];
    }

    public function &string(string $name) : Column
    {
        $this->columns[ $name ] = new Column\Varchar($name);

        return $this->columns[ $name ];
    }

    public function &decimal(string $name) : Column
    {
        $this->columns[ $name ] = new Column\Decimal($name);

        return $this->columns[ $name ];
    }

    public function &boolean(string $name) : Column
    {
        $this->columns[ $name ] = new Column\Boolean($name);

        return $this->columns[ $name ];
    }

    public function &integer(string $name) : Column
    {
        $this->columns[ $name ] = new Column\Integer($name);

        return $this->columns[ $name ];
    }
}