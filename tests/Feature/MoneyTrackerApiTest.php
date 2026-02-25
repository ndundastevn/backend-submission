<?php

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can create a user', function () {
    $response = $this->postJson('/api/users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertCreated();
    $response->assertJsonPath('data.name', 'Test User');
    $response->assertJsonPath('data.email', 'test@example.com');
    $response->assertJsonStructure(['data' => ['id', 'name', 'email']]);

    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
});

test('can create a wallet for a user', function () {
    $user = User::factory()->create();

    $response = $this->postJson("/api/users/{$user->id}/wallets", [
        'name' => 'My Wallet',
    ]);

    $response->assertCreated();
    $response->assertJsonPath('data.name', 'My Wallet');
    $response->assertJsonPath('data.user_id', $user->id);
    $response->assertJsonStructure(['data' => ['id', 'user_id', 'name', 'balance']]);

    $this->assertDatabaseHas('wallets', ['user_id' => $user->id, 'name' => 'My Wallet']);
});

test('can add income transaction to a wallet', function () {
    $wallet = Wallet::factory()->create();

    $response = $this->postJson("/api/users/{$wallet->user_id}/wallets/{$wallet->id}/transactions", [
        'amount' => 100.50,
        'type' => 'income',
        'description' => 'Salary',
    ]);

    $response->assertCreated();
    $response->assertJsonPath('data.amount', '100.50');
    $response->assertJsonPath('data.type', 'income');
    $response->assertJsonPath('data.description', 'Salary');

    $this->assertDatabaseHas('transactions', [
        'wallet_id' => $wallet->id,
        'amount' => 100.50,
        'type' => 'income',
    ]);
});

test('can add expense transaction to a wallet', function () {
    $wallet = Wallet::factory()->create();

    $response = $this->postJson("/api/users/{$wallet->user_id}/wallets/{$wallet->id}/transactions", [
        'amount' => 25.00,
        'type' => 'expense',
        'description' => 'Groceries',
    ]);

    $response->assertCreated();
    $response->assertJsonPath('data.type', 'expense');

    $this->assertDatabaseHas('transactions', [
        'wallet_id' => $wallet->id,
        'type' => 'expense',
    ]);
});

test('profile returns user with all wallets and balances', function () {
    $user = User::factory()->create();
    $wallet1 = Wallet::factory()->for($user)->create(['name' => 'Wallet A']);
    $wallet2 = Wallet::factory()->for($user)->create(['name' => 'Wallet B']);

    Transaction::factory()->for($wallet1)->create(['amount' => 100, 'type' => 'income']);
    Transaction::factory()->for($wallet1)->create(['amount' => 30, 'type' => 'expense']);
    Transaction::factory()->for($wallet2)->create(['amount' => 50, 'type' => 'income']);

    $response = $this->getJson("/api/users/{$user->id}");

    $response->assertSuccessful();
    $response->assertJsonPath('data.name', $user->name);
    $response->assertJsonCount(2, 'data.wallets');
    $response->assertJsonPath('data.overall_balance', '120.00'); // 70 + 50
});

test('single wallet show returns balance and transactions', function () {
    $wallet = Wallet::factory()->create(['name' => 'Savings']);
    Transaction::factory()->for($wallet)->count(2)->create(['type' => 'income', 'amount' => 50]);

    $response = $this->getJson("/api/users/{$wallet->user_id}/wallets/{$wallet->id}");

    $response->assertSuccessful();
    $response->assertJsonPath('data.name', 'Savings');
    $response->assertJsonPath('data.balance', '100.00');
    $response->assertJsonCount(2, 'data.transactions');
});

test('balance calculation: income adds and expense subtracts', function () {
    $wallet = Wallet::factory()->create();

    Transaction::factory()->for($wallet)->create(['amount' => 100, 'type' => 'income']);
    Transaction::factory()->for($wallet)->create(['amount' => 30, 'type' => 'expense']);

    $response = $this->getJson("/api/users/{$wallet->user_id}/wallets/{$wallet->id}");

    $response->assertSuccessful();
    $response->assertJsonPath('data.balance', '70.00');
});

test('can show a single transaction', function () {
    $transaction = Transaction::factory()->create([
        'amount' => 99.99,
        'type' => 'income',
        'description' => 'Refund',
    ]);
    $wallet = $transaction->wallet;

    $response = $this->getJson("/api/users/{$wallet->user_id}/wallets/{$wallet->id}/transactions/{$transaction->id}");

    $response->assertSuccessful();
    $response->assertJsonPath('data.amount', '99.99');
    $response->assertJsonPath('data.description', 'Refund');
});
