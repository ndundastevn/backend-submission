<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\TransactionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $demo = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => Hash::make('password'),
        ]);

        $savings = $demo->wallets()->create(['name' => 'Savings']);
        $checking = $demo->wallets()->create(['name' => 'Checking']);

        $this->seedTransactions($savings, [
            ['amount' => 1000, 'type' => TransactionType::Income, 'description' => 'Initial deposit'],
            ['amount' => 250, 'type' => TransactionType::Income, 'description' => 'Monthly savings'],
            ['amount' => 75.50, 'type' => TransactionType::Expense, 'description' => 'Withdrawal'],
        ]);
        $this->seedTransactions($checking, [
            ['amount' => 500, 'type' => TransactionType::Income, 'description' => 'Paycheck'],
            ['amount' => 45.99, 'type' => TransactionType::Expense, 'description' => 'Groceries'],
            ['amount' => 29.00, 'type' => TransactionType::Expense, 'description' => 'Subscription'],
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory(2)->create()->each(function (User $user): void {
            $wallets = $user->wallets()->saveMany([
                Wallet::factory()->make(['name' => fake()->randomElement(['Main', 'Savings', 'Cash'])]),
            ]);
            foreach ($wallets as $wallet) {
                Transaction::factory()->count(fake()->numberBetween(3, 8))->create([
                    'wallet_id' => $wallet->id,
                ]);
            }
        });
    }

    /**
     * @param  array<int, array{amount: float, type: TransactionType, description: string}>  $items
     */
    private function seedTransactions(Wallet $wallet, array $items): void
    {
        foreach ($items as $item) {
            Transaction::factory()->create([
                'wallet_id' => $wallet->id,
                'amount' => $item['amount'],
                'type' => $item['type'],
                'description' => $item['description'],
                'date' => now()->toDateString(),
            ]);
        }
    }
}
