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
}
