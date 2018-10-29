<?php
declare(strict_types=1);

namespace Wex\ActiveRecord;

class Collection implements \ArrayAccess
{
    /**
     * From(AnotherActiveRecord, foreign_key) = source
     * pages -> pages_html
     */
    
    /**
     * Thru(TableName, foreign_key, [inherit1, inherit2, ...])
     */

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