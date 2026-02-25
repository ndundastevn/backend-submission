<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    public function store(StoreTransactionRequest $request, User $user, Wallet $wallet): JsonResponse
    {
        $data = array_merge($request->validated(), [
            'wallet_id' => $wallet->id,
            'date' => $request->validated('date') ?? now()->toDateString(),
        ]);

        $transaction = Transaction::query()->create($data);

        return (new TransactionResource($transaction))
            ->response()
            ->setStatusCode(201);
    }

    public function show(User $user, Wallet $wallet, Transaction $transaction): TransactionResource
    {
        return new TransactionResource($transaction);
    }
}
