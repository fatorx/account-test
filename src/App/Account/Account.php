<?php

namespace App\Account;

use App\Storage\Storage;

class Account
{
    private Storage $storage;

    /**
     * @param int $accountId
     * @return int
     */
    public function processGetBalance(int $accountId): int
    {
        return 20;
    }

    /**
     * @param Storage $storage
     * @return Account
     */
    public function setStorage(Storage $storage): Account
    {
        $this->storage = $storage;
        return $this;
    }
}
