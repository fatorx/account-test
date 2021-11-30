<?php

namespace App\Storage;

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
     * @return bool
     */
    public function setItem($key, $value): bool
    {
        if (!$this->redis->exists($key)) {
            return $this->redis->set($key, $value);
        }

        return false;
    }

    /**
     * @param $key
     * @return false|mixed|string
     */
    public function getItem($key)
    {
        return $this->redis->get($key);
    }
}
