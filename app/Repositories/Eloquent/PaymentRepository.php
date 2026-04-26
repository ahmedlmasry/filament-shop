<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Http\Request;

class PaymentRepository implements PaymentRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function saveTransaction(array $data) { 
        
    }
    public function updateTransactionStatus(string $transactionId, string $status) { 
        
    }
}
