<?php

namespace App\Account;

use App\Http\Request;

class Methods
{
    private Account $account;
    private Request $request;

    private string $message = '';
    private bool $status    = true;
    private int $code       = 200;

    /**
     * @param Account $account
     * @param Request $request
     */
    public function __construct(Account $account, Request $request)
    {
        $this->account = $account;
        $this->request = $request;
    }

    public function getReset(): array
    {
        //$this->account->processReset();
        return [];
    }

    public function getBalance(): array
    {
        $accountId = $this->request->getQueryString('account_id', 0);
        $data      = [
            'value' => 0
        ];

        try {
            $value = $this->account->processGetBalance($accountId);
            if (!$value) {
                $this->code = 404;
            }
            $data['value'] = $value;
        } catch (\Exception $e) {
            $this->code = 500;
        }

        return $data;
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

    public function getState(): array
    {
        return [
            $this->status,
            $this->message,
            $this->code
        ];
    }
}
