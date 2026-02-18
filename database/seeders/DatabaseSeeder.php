<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@splwallet.local',
            'password' => 'password123',
            'role' => UserRole::Admin,
        ]);

        $merchant = User::create([
            'name' => 'Demo Merchant',
            'email' => 'merchant@splwallet.local',
            'password' => 'password123',
            'role' => UserRole::Merchant,
        ]);

        User::create([
            'name' => 'Demo Employee',
            'email' => 'employee@splwallet.local',
            'password' => 'password123',
            'role' => UserRole::Employee,
            'merchant_id' => $merchant->id,
        ]);

        User::create([
            'name' => 'Demo Reseller',
            'email' => 'reseller@splwallet.local',
            'password' => 'password123',
            'role' => UserRole::Reseller,
        ]);

        foreach (['payin', 'payout'] as $type) {
            Wallet::create([
                'user_id' => $merchant->id,
                'type' => $type,
                'balance' => 0,
                'currency' => 'INR',
                'status' => 'active',
            ]);
        }
    }
}
