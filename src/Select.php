<?php

namespace Wex;

use \Zend\Db\Sql\Select AS ZendSelect;
use \Zend\Db\Sql\Expression;
use \Wex\App;

class Select extends ZendSelect implements \Iterator
{
    protected   $_iterator;

    public function getSql()
    {
        return \Wex\App::$sql->buildSqlString( $this );
    }

    public function __toString()
    {
        return $this->getSql();
    }

    public function where($condition, $parameters = null)
    {
        if (is_null($parameters)) {
            return parent::where($condition);
        } else if (strpos($condition, '?') !== false) {
            return parent::where( $this->quoteInto($condition, $parameters) );
        } else {
            throw new \LogicException("Not implemented.");
            /**
             * @todo Throw in some cool :placeholder -stuff
             */
        }
    }

    public function expr($sql)
    {
        return new Expression($sql);
    }

    public function quote($value)
    {
        if (is_array($value)) {
            return App::$db->getPlatform()->quoteValueList($value);
        } else {
            return App::$db->getPlatform()->quoteValue($value);
        }
    }

    public function quoteInto($condition, $value)
    {
        return str_replace('?', $this->quote($value), $condition);
    }

    public function quoteIdentifier($value)
    {
        return App::$db->getPlatform()->quoteIdentifier($value);
    }

    public function query($sql)
    {
        return App::$db->query($sql);
    }

    public function first()
    {
        $statement = $this->query( $this->getSql() );
        $results = $statement->execute();
        
        $value = $results->current();
        return is_array($value) ? $value : null;
    }

    public function all()
    {
        $statement = $this->query( $this->getSql() );
        $results = $statement->execute();
        
        $data = [];
        foreach ($results as $row) {
            $data[] = $row;
        }

        return $data;
    }

    public function column()
    {
        $statement = $this->query( $this->getSql() );
        $results = $statement->execute();
        
        $data = $results->current();

        return array_shift($data);
    }

    public function current()
    {
        $value = $this->_iterator->current();
        return is_array($value) ? $value : null;
    }

    public function next()
    {
        return $this->_iterator->next();
    }

    public function key()
    {
        return $this->_iterator->key();
    }

    public function valid()
    {
        $this->_iterator->current();
        return $this->_iterator->valid();
    }

    public function rewind()
    {
        $this->_iterator = $this->query( $this->getSql() )->execute();
    }
}