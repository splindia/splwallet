<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\AdjustWalletRequest;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function summary(Request $request): JsonResponse
    {
        $wallets = $request->user()->wallets()->get()->keyBy('type');

        return response()->json([
            'payin_wallet' => $wallets->get('payin'),
            'payout_wallet' => $wallets->get('payout'),
        ]);
    }

    public function creditPayin(AdjustWalletRequest $request): JsonResponse
    {
        return $this->adjustBalance($request->user()->id, 'payin', 'credit', $request->validated());
    }

    public function debitPayout(AdjustWalletRequest $request): JsonResponse
    {
        return $this->adjustBalance($request->user()->id, 'payout', 'debit', $request->validated());
    }

    private function adjustBalance(int $userId, string $walletType, string $transactionType, array $payload): JsonResponse
    {
        return DB::transaction(function () use ($userId, $walletType, $transactionType, $payload) {
            $wallet = Wallet::where('user_id', $userId)
                ->where('type', $walletType)
                ->lockForUpdate()
                ->firstOrFail();

            $opening = (float) $wallet->balance;
            $amount = (float) $payload['amount'];

            if ($transactionType === 'debit' && $opening < $amount) {
                return response()->json(['message' => 'Insufficient wallet balance.'], 422);
            }

            $closing = $transactionType === 'credit'
                ? $opening + $amount
                : $opening - $amount;

            $wallet->update(['balance' => $closing]);

            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => $transactionType,
                'amount' => $amount,
                'opening_balance' => $opening,
                'closing_balance' => $closing,
                'reference_id' => $payload['reference_id'],
                'meta' => ['remark' => $payload['remark'] ?? null],
                'status' => 'success',
            ]);

            return response()->json([
                'message' => 'Wallet updated successfully.',
                'wallet_type' => $walletType,
                'opening_balance' => $opening,
                'closing_balance' => $closing,
            ]);
        });
    }
}
