<?php

namespace App;

use App\Account\Account;
use App\Account\Methods;
use App\Http\Request;

/**
 * @class Factory
 */
class Factory
{
    /**
     * @param Request $request
     * @return Methods
     */
    public function buildClassMethods(Request $request): Methods
    {
        $account = $this->buildAccount();
        return new Methods($account, $request);
    }

    /**
     * @return Account
     */
    public function buildAccount(): Account
    {
        $storage = new Storage\Storage();
        $account = new Account();
        return $account->setStorage($storage);
    }
}
