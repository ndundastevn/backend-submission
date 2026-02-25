# Money Tracker API

A backend-only Laravel API for managing user wallets and transactions. Users can create accounts, manage multiple wallets, and record income or expense transactions. Balances are computed from transactions (income adds, expense subtracts).

## Requirements

- PHP 8.4+
- Composer
- Node.js & npm (for frontend assets if needed)
- SQLite, MySQL, or PostgreSQL

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
```

Create the SQLite database (or configure `.env` for MySQL/PostgreSQL):

```bash
touch database/database.sqlite
php artisan migrate
```

## Running the application

**API only:**

```bash
php artisan serve
```

API base URL: `http://localhost:8000/api`

**Full stack (API + queue + logs + Vite):**

```bash
composer run dev
```

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/users` | Create a user (no auth required) |
| `GET`  | `/api/users/{user}` | User profile: wallets, balance per wallet, overall balance |
| `POST` | `/api/users/{user}/wallets` | Create a wallet for the user |
| `GET`  | `/api/users/{user}/wallets/{wallet}` | Single wallet: balance and transactions |
| `POST` | `/api/users/{user}/wallets/{wallet}/transactions` | Add a transaction (income or expense) |
| `GET`  | `/api/users/{user}/wallets/{wallet}/transactions/{transaction}` | Single transaction |

### Example: Create user

```bash
curl -X POST http://localhost:8000/api/users \
  -H "Content-Type: application/json" \
  -d '{"name":"Jane","email":"jane@example.com","password":"secret123","password_confirmation":"secret123"}'
```

### Example: Create wallet

```bash
curl -X POST http://localhost:8000/api/users/1/wallets \
  -H "Content-Type: application/json" \
  -d '{"name":"Main"}'
```

### Example: Add transaction

```bash
curl -X POST http://localhost:8000/api/users/1/wallets/1/transactions \
  -H "Content-Type: application/json" \
  -d '{"amount":100.50,"type":"income","description":"Salary"}'
```

Transaction `type` must be `income` or `expense`. `amount` must be positive. `description` and `date` are optional; `date` defaults to today.

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
