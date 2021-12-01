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

    /**
     * @return array
     */
    public function getReset(): array
    {
        $this->status = $this->account->processReset();
        return [];
    }

    /**
     * @return array
     */
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

    /**

        # Withdraw from non-existing account
        POST /event {"type":"withdraw", "origin":"200", "amount":10}
        404 0

     */
    public function postEvent(): array
    {
        $data = $this->request->getStreamParameters();

        try {
            $value = $this->account->processPostEvent($data);
            if (!$value) {
                $this->code = 404;
            }
            return $value;

        } catch (\Exception $e) {

        }

        return [];
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
