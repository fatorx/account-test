<?php

namespace App\Account;

use App\Config\Strings;
use App\Storage\Storage;

class Account
{
    private Storage $storage;
    private bool $status = true;

    /**
     * @return bool
     */
    public function processReset(): bool
    {
        return $this->storage->reset();
    }

    /**
     * @param int $accountId
     * @return int
     */
    public function processGetBalance(int $accountId): int
    {
        $balance = $this->getBalanceById($accountId);
        if (!$balance) {
            return 0;
        }

        return $balance['amount'];
    }

    /**
     * @param array $data
     * @return array
     */
    public function processPostBalance(array $data = []): array
    {
        // @todo valid enter
        $accountId = $data['destination'];
        $account   = $this->getBalanceById($accountId);
        $amount    = $data['amount'];

        if ($account) {
            $amount = $account['amount'] + $amount;
        }

        $statusStore = $this->manageBalance($accountId, $amount);
        if (!$statusStore) {
            return [];
        }

        return [
            'destination' => [
                'id'      => $accountId,
                'balance' => $amount
            ]
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    public function processPostEvent(array $data = []): array
    {
        $typeOperation = $data['type'];
        if(method_exists($this, $typeOperation)) {
            return $this->{$typeOperation}($data);
        }

        return [];
    }

    /**
     * @param array $data
     * @return array
     */
    public function deposit(array $data): array
    {
        // @todo valid enter
        $amount    = $data['amount'];
        $accountId = $data['destination'];
        $account   = $this->getBalanceById($accountId);

        $currentAccount = $account['amount'] ?? 0;
        $newAmount = $currentAccount + $amount;

        $statusStore = $this->manageBalance($accountId, $newAmount);
        if (!$statusStore) {
            return [];
        }

        return [
            'destination' => [
                'id'      => $accountId,
                'balance' => $newAmount
            ]
        ];
    }

    /**
     * @param array $data
     * @return array|array[]
     */
    public function withdraw(array $data = []): array
    {
        // @todo valid enter
        $amount    = $data['amount'];
        $accountId = $data['destination'];

        $account   = $this->getBalanceById($accountId);

        if (!$account) {
            return [];
        }

        $currentAccount = $account['amount'] ?? 0;
        if ($currentAccount < $amount) {
            return [];
        }
        $newAmount = $currentAccount - $amount;

        $statusStore = $this->manageBalance($accountId, $newAmount);
        if (!$statusStore) {
            return [];
        }

        return [
            'destination' => [
                'id'      => $accountId,
                'balance' => $newAmount
            ]
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    public function transfer(array $data = []): array
    {
        $amount = $data['amount'];
        $origin = $data['origin'];
        $destination = $data['destination'];

        $accountFrom = $this->getBalanceById($origin);

        if(!$accountFrom) {
            return [];
        }

        $amountFromOriginal = $accountFrom['balance'];
        if($amountFromOriginal < $amount) {
            return [];
        }

        $amountFrom = $amountFromOriginal - $amount;
        $accountTo  = $this->getBalanceById($destination);
        if(!$accountTo) {
            $statusStore = $this->manageBalance($destination, $amount);
            if (!$statusStore) {
                return [];
            }
        }

        $statusStore = $this->manageBalance($origin, $amountFrom);
        if (!$statusStore) {
            $this->revert($origin, $destination, $amountFromOriginal);
        }

        $accountFrom = $this->getBalanceById($origin);
        $accountTo   = $this->getBalanceById($destination);

        return [
            'origin'      => $accountFrom,
            'destination' => $accountTo
        ];
    }

    /**
     * @param $origin
     * @param $destination
     * @param $amountFromOriginal
     */
    public function revert($origin, $destination, $amountFromOriginal)
    {
        $this->manageBalance($origin, $amountFromOriginal);
        $this->manageBalance($destination, 0);
    }

    /**
     * @param $accountId
     * @return array|mixed
     */
    private function getBalanceById($accountId)
    {
        $balances = $this->getBalances();
        foreach ($balances as $key => $balance) {
            if ($accountId == $key) {
                return $balance;
            }
        }
        return [];
    }

    /**
     * @param $amountId
     * @param $amountValue
     * @return bool
     */
    public function manageBalance($amountId, $amountValue): bool
    {
        $balance   = [
            'id'      => $amountId,
            'balance' => $amountValue
        ];
        return $this->storeBalance($amountId, $balance);
    }

    /**
     * @param $accountId
     * @param $balance
     * @return bool
     */
    public function storeBalance($accountId, $balance): bool
    {
        $balances = $this->getBalances();
        $balances[$accountId] = $balance;

        $keyAccounts = Strings::PKEY . '_accounts';
        return $this->storage->setItem($keyAccounts, $balances, true);
    }

    /**
     * @return array
     */
    public function getBalances(): array
    {
        $keyAccounts = Strings::PKEY . '_accounts';
        $balances = $this->storage->getItem($keyAccounts);
        if (!$balances) {
            $balances = [];
            $this->storage->setItem($keyAccounts, $balances);
        }
        return $balances;
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

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

}
