<?php

namespace App\Repositories\Contracts;

interface PaymentRepositoryInterface
{
    // Define database specific methods here, e.g.
    public function saveTransaction(array $data);
    public function updateTransactionStatus(string $transactionId, string $status);
}
