<?php

namespace App\Jobs;

use App\Models\EscrowHold;
use App\Models\WalletAccount;
use App\Models\WalletTransaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProcessVendorPayoutBatch implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        EscrowHold::query()
            ->where('status', 'held')
            ->where('release_at', '<=', now())
            ->chunkById(100, function ($holds): void {
                foreach ($holds as $hold) {
                    DB::transaction(function () use ($hold): void {
                        $wallet = WalletAccount::query()->lockForUpdate()->firstOrCreate(
                            ['user_id' => $hold->vendor_id],
                            ['currency' => 'USD', 'available_balance' => 0, 'pending_balance' => 0]
                        );

                        $wallet->pending_balance = max(0, (float) $wallet->pending_balance - (float) $hold->amount);
                        $wallet->available_balance = (float) $wallet->available_balance + (float) $hold->amount;
                        $wallet->save();

                        WalletTransaction::create([
                            'wallet_account_id' => $wallet->id,
                            'reference' => 'REL-'.strtoupper(Str::random(12)),
                            'type' => 'escrow_release',
                            'amount' => $hold->amount,
                            'status' => 'posted',
                            'meta' => ['escrow_hold_id' => $hold->id],
                        ]);

                        $hold->update([
                            'status' => 'released',
                            'released_at' => now(),
                        ]);
                    });
                }
            });
    }
}
