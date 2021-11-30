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
        $account = new Account();
        return new Methods($account, $request);
    }
}
