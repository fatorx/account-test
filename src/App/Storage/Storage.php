<?php

namespace App\Storage;

use App\Config\Strings;
use Redis;

class Storage
{
    private Redis $redis;

    public function __construct()
    {
        try {
            $this->redis = new Redis();
            $this->redis->connect('redis', 6379);
        } catch (\Exception $e) {

        }
    }

    /**
     * @param $key
     * @param $value
     * @param bool $list
     * @return bool
     */
    public function setItem($key, $value, bool $list = false): bool
    {
        if (!$this->redis->exists($key) || $list) {
            return $this->redis->set($key, serialize($value));
        }

        return false;
    }

    /**
     * @param $key
     * @return false|mixed|string
     */
    public function getItem($key)
    {
        $item = $this->redis->get($key);
        if ($item) {
            return unserialize($item);
        }

        return null;
    }

    /**
     * @return bool
     */
    public function reset(): bool
    {
        $keys = $this->redis->keys( Strings::PKEY . '*');
        foreach ($keys as $key) {
            $this->redis->del($key);
        }

        return true;
    }
}
