# SPL Wallet (Laravel Structure)

Ye project structure aapke requirement ke hisaab se design ki gayi hai:
- Multi-role authentication
- Roles: **Merchant (User)**, **Employee**, **Reseller**, **Admin**
- Merchant ke liye dual wallet:
  - **Payin Wallet** (incoming/payin amount)
  - **Payout Wallet** (outgoing/payout balance)

## 1) Core Design

### Roles
- `merchant`: primary business user jiske paas wallets honge.
- `employee`: merchant ke under linked user (`merchant_id`).
- `reseller`: independent partner role.
- `admin`: full control and monitoring.

### Wallet Model
Har merchant ke liye automatic 2 wallets create honge:
1. `payin`
2. `payout`

Tables:
- `users`
- `wallets`
- `wallet_transactions`

`wallet_transactions` ledger pattern follow karta hai:
- opening_balance
- amount
- closing_balance
- type (credit/debit)
- reference_id (unique)

## 2) API Endpoints

### Auth
- `POST /api/auth/register`
- `POST /api/auth/login`
- `GET /api/auth/me`
- `POST /api/auth/logout`

### Merchant Wallet
(Protected: `auth:sanctum` + `role:merchant`)
- `GET /api/merchant/wallet/summary`
- `POST /api/merchant/wallet/payin/credit`
- `POST /api/merchant/wallet/payout/debit`

## 3) Register Flow
Agar register request me role `merchant` hai to system automatic:
- payin wallet create karta hai
- payout wallet create karta hai

## 4) Important Notes
- Is environment me external package download blocked tha, isliye full Laravel runtime install command run nahi ho paayi.
- Code Laravel-compatible structure me diya gaya hai, aap normal internet-enabled system par:
  1. `composer install`
  2. `.env` setup
  3. `php artisan migrate --seed`
  4. `php artisan serve`

## 5) Next Recommended Steps
- Laravel Breeze/Jetstream UI auth add karein agar web-panel chahiye.
- Admin panel me merchant-wise wallet statement + manual adjustment tools add karein.
- Payout approvals ke liye maker-checker flow add karein (Employee create request, Admin approve).
