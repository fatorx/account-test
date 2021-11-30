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

    public function getReset()
    {
        //$this->account->processReset();
        echo "getReset";
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

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function getCode(): int
    {
        return $this->code;
    }
}
