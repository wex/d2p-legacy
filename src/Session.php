<?php
declare(strict_types=1);

namespace Wex;

use \Ramsey\Uuid\Uuid;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class Session extends \SessionHandler
{
    const       table   = 'sessions';
    const       cipher  = 'AES-256-OFB';
    const       name    = 'DSESSIONID';

    protected   $key;
    protected   $name;

    public function __construct(string $key = null)
    {
        if (is_null($key) || strlen($key) < 20) throw new \ErrorException("Misconfigured application key.");
        $this->key  = $key;
    }

    public static function initialize(string $key, string $name = '') : void
    {
        $handler = new static($key, $name);
        session_name(static::name . $name);
        session_set_save_handler($handler, false);
        session_start();
    }

    public function encrypt($data)
    {
        $iv     = openssl_random_pseudo_bytes(openssl_cipher_iv_length(static::cipher));
        
        return base64_encode(openssl_encrypt(
            $data,
            static::cipher,
            $this->key,
            0,
            $iv
        ) . $iv);
    }

    public function decrypt($data)
    {
        $data   = base64_decode($data);
        $ivlen  = openssl_cipher_iv_length(static::cipher);
        $raw    = substr($data, 0, -$ivlen);
        $iv     = substr($data, -$ivlen);

        return openssl_decrypt(
            $raw,
            static::cipher,
            $this->key,
            0,
            $iv
        );
    }

    public function create_sid() : string
    {
        return Uuid::uuid4()->toString();
    }

    public function open($path, $name)
    {
        return true;
    }

    public function close()
    {
        return true;
    }

    public function destroy($id)
    {
        return App::$db->query(sprintf(
            'DELETE FROM %s WHERE `id` = %s',
            App::$db->getPlatform()->quoteIdentifier(static::table),
            App::$db->getPlatform()->quoteValue($id)
        ), Adapter::QUERY_MODE_EXECUTE);

        return true;
    }

    public function read($id) 
    {
        $data = \Wex\App::$db->query(sprintf(
            'SELECT `data` FROM %s WHERE `id` = %s',
            \Wex\App::$db->getPlatform()->quoteIdentifier(static::table),
            \Wex\App::$db->getPlatform()->quoteValue($id)
        ))->execute()->current();

        return ($data === false) ? '' : $this->decrypt($data['data']);
    }

    public function write($id, $data)
    {
        $data = $this->encrypt($data);

        App::$db->query(sprintf(
            'INSERT INTO %s (`id`, `data`, `created_at`) VALUES (%s, %s, NOW()) ON DUPLICATE KEY UPDATE `id` = %s, `data` = %s, `updated_at` = NOW()',
            App::$db->getPlatform()->quoteIdentifier(static::table),
            App::$db->getPlatform()->quoteValue($id),
            App::$db->getPlatform()->quoteValue($data),
            App::$db->getPlatform()->quoteValue($id),
            App::$db->getPlatform()->quoteValue($data)
        ), Adapter::QUERY_MODE_EXECUTE);

        return true;
    }

    public function gc($lifetime)
    {
        App::$db->query(sprintf(
            'DELETE FROM %s WHERE `updated_at` < NOW() - INTERVAL %d SECOND',
            App::$db->getPlatform()->quoteIdentifier(static::table),
            $lifetime
        ));

        return true;
    }
}