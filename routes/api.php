<?php

use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

Route::post('users', [UserController::class, 'store']);
Route::get('users/{user}', [UserController::class, 'show']);

Route::post('users/{user}/wallets', [WalletController::class, 'store']);
Route::get('users/{user}/wallets/{wallet}', [WalletController::class, 'show'])
    ->scopeBindings();

Route::post('users/{user}/wallets/{wallet}/transactions', [TransactionController::class, 'store'])
    ->scopeBindings();
Route::get('users/{user}/wallets/{wallet}/transactions/{transaction}', [TransactionController::class, 'show'])
    ->scopeBindings();
