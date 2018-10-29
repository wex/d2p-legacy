<?php
declare(strict_types=1);

namespace Wex;

use \Zend\Db\Sql\Sql;
use \Zend\Db\Adapter\Adapter;
use \Wex\ActiveRecord\Blueprint;
use \Wex\ActiveRecord\Select;
use \Wex\ActiveRecord\Exception\NotFound;
use \Wex\ActiveRecord\SoftDelete;
use \Wex\ActiveRecord\Timestamps;

abstract class ActiveRecord
{
    use ActiveRecord\Describe;
    use ActiveRecord\Harry;
    use ActiveRecord\Wolfe;
    use ActiveRecord\Database;

    const       table           = null;

    protected   $__dirty        = [];
    protected   $__data         = [];
    protected   $__blueprint    = null;

    static      $adapter        = null;
    static      $sql            = null;
    static      $platform       = null;
    
    public static function setAdapter(Adapter $adapter) : void
    {
        static::$adapter = $adapter;
        static::$platform = $adapter->getPlatform();
    }

    public static function setSql(Sql $sql) : void
    {
        static::$sql = $sql;
    }

    /**
     * Table structure describer
     *
     * @param  Blueprint &$table    Table's Brueprint - used for SQL generating + validating
     *
     * @return void
     */
    abstract protected function describe(Blueprint &$table) : void;

    /**
     * Build ActiveRecord from raw data
     */
    public function __construct(array $values = [])
    {
        $this->__data = $values;
    }

    /**
     * Magical getter for properties
     */
    public function __get(string $key)
    {
        return $this->__data[ $key ] ?? null;
    }

    /**
     * Magical setter for properties
     * Flags properties dirty
     */
    public function __set(string $key, $value) : void
    {
        if (!isset($this->__dirty[ $key ])) {
            $this->__dirty[ $key ] = $this->__data[ $key ] ?? null;
        }
        $this->__data[ $key ] = $value;
    }

    
    /**
     * Load ActiveRecord by PK
     *
     * @param  mixed $id
     * @param  bool  $strict    Strict mode throws NotFound exception - otherwise FALSE is returned.
     *
     * @return self
     */
    public static function load($id, bool $strict = true)
    {
        try {
            return static::select()->where('id = ?', (string) $id)->first();
        } catch (NotFound $e) {
            if ($strict) throw $e;
            return false;
        }
    }

    
    /**
     * Save ActiveRecord
     *
     * @param  bool $reload     Reload ActiveRecord after saving - avoid desync if updated via triggers
     *
     * @return bool
     */
    public function save(bool $reload = true) : bool
    {
        $this->__blueprint = $this->__blueprint ?? $this->bluePrint();

        if ($this->validate()) {

            $defaults   = [];
            foreach ( $this->__blueprint->columns as $column) {
                $defaults[$column->name] = $column->default;
            }
            $keys       = array_map(function($v) { return $v->name; }, $this->__blueprint->columns);
            $data       = [];
            
            if ($this->id > 0) {

                foreach ($keys as $key) {
                    if (!isset($this->__dirty[$key])) continue;
                    $data[$key] = $this->__data[$key] ?? null;
                }
                return count($data) ? $this->_update($data, $this->id) : true;

            } else {

                foreach ($keys as $key) {
                    $data[$key] = $this->__data[$key] ?? $defaults[$key];
                }
                return count($data) ? $this->_insert($data) : true;

            }
            
        }

        return false;
    }

    /**
     * Get Select for current ActiveRecord
     *
     * @return Select
     */
    public static function select() : Select
    {
        return new Select(static::class);
    }

    /**
     * Mixed type create for ActiveRecord - used by Select
     *
     * @param  mixed $data
     *
     * @return self
     */
    public static function create($data) : self
    {
        if (!is_array($data)) {
            throw new NotFound;
        } else {
            return new static($data);
        }
    }

}