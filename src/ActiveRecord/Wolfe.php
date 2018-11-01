<?php
declare(strict_types=1);

namespace Wex\ActiveRecord;

use \Wex\App;

/**
 * Wolfe the Cleaner
 * Winston is broken :(
 */

trait Wolfe {
    public function validate()
    {
        $this->errors = [];
        $this->__blueprint = $this->__blueprint ?? $this->bluePrint();

        foreach ($this->__blueprint->columns as $column) {
            $this->errors[$column->name] = $column->getErrors( $this->{$column->name} );
        }

        $this->errors = array_filter($this->errors, function($v) { return !!count($v); });
        
        return !count($this->errors);
    }
}