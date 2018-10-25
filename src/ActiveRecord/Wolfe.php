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
        $this->__blueprint = $this->__blueprint ?? $this->bluePrint();

        /**
         * @todo FIX ME!
         */
        return true;
    }
}