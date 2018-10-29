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
        $errors = [];
        $this->__blueprint = $this->__blueprint ?? $this->bluePrint();

        foreach ($this->__blueprint->columns as $column) {
            $errors[$column->name] = $column->getErrors( $this->{$column->name} );
        }

        $errors = array_filter($errors, function($v) { return !!count($v); });
        
        return !count($errors);
    }
}