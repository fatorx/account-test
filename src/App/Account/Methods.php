<?php

namespace App\Account;

use App\Account\Account;
use App\Http\Request;

class Methods
{
    private Account $account;
    private Request $request;

    public function __construct(Account $account, Request $request)
    {
        $this->account = $account;
        $this->request = $request;
    }

    public function getReset()
    {
        //$this->account->processGetReset();
        echo "getReset";
    }

    public function getBalance()
    {
        //$this->account->processPostBalance();
        echo "getReset";
    }

    public function postReset()
    {
        //$this->account->processPostReset();
        echo "postReset";
    }

    public function postBalance()
    {
        //$this->account->processPostBalance();
        echo "postReset";
    }

    public function postEvent()
    {
        echo "postReset";
    }
}
