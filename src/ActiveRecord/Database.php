<?php
declare(strict_types=1);

namespace Wex\ActiveRecord;

use Zend\Db\Adapter\Adapter;
use Wex\App;
use Wex\ActiveRecord\Timestamps;
use Wex\ActiveRecord\Exception\Failed;

trait Database 
{
    protected function _insert(array $data)
    {
        if ($this instanceof Timestamps) {
            $data['created_at'] = static::now();        
        }

        $insert = static::$sql->insert(static::table);
        $insert->values($data);

        $sql = static::$sql->buildSqlString($insert);

        try {
            $result = static::$adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
            $this->id = $result->getGeneratedValue();
        } catch (\Exception $e) {
            throw new Failed($e->getMessage());
        }

        return true;
    }

    protected function _update(array $data, $id)
    {
        if ($this instanceof Timestamps) {
            $data['updated_at'] = static::now();
        }

        $update = static::$sql->update(static::table);
        $update->set($data);
        $update->where(['id' => $id]);

        $sql = static::$sql->buildSqlString($update);

        try {
            $result = static::$adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        } catch (\Exception $e) {
            throw new Failed($e->getMessage());
        }

        return true;
    }

    protected function _delete($id)
    {
        $delete = static::$sql->delete(static::table);
        $delete->where(['id' => $id]);

        $sql = static::$sql->buildSqlString($delete);

        try {
            $result = static::$adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function refresh()
    {
        $this->__data = static::select()->where(['id' => $this->id])->fetchFirst();
    }

    public static function now(string $format = 'Y-m-d H:i:s')
    {
        return date($format);
    }

    public function destroy()
    {
        if ($this->id > 0) {
            if ($this instanceof SoftDelete) {

                return $this->_update(['deleted_at' => static::now()], $this->id);

            } else {

                return $this->_delete($this->id);

            }
        }
    }

}