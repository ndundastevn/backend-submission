<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Resources\WalletResource;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    public function store(StoreWalletRequest $request, User $user): JsonResponse
    {
        $wallet = $user->wallets()->create($request->validated());

        return (new WalletResource($wallet))
            ->response()
            ->setStatusCode(201);
    }

    public function show(User $user, Wallet $wallet): WalletResource
    {
        $wallet->load('transactions');

        return new WalletResource($wallet);
    }
}
