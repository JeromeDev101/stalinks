<?php

namespace App\Console\Commands;

use App\Jobs\SendDepositReminderEmail;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class SendDepositReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deposit:remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send deposit reminder to buyers without transaction';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // send deposit reminder for day 1
        $deposit_reminder_one = $this->getBuyersWithoutWalletTransactionInRegistration(1);

        $this->sendDepositReminderToBuyersWithoutTransaction($deposit_reminder_one, 1);

        if ($deposit_reminder_one->count()) {
            $this->updateBuyersWithNoTransactions($deposit_reminder_one->pluck('id')->toArray(), 1);
        }

        // send deposit reminder for day 2
        $deposit_reminder_two = $this->getBuyersWithoutWalletTransactionInRegistration(2);

        $this->sendDepositReminderToBuyersWithoutTransaction($deposit_reminder_two, 2);

        if ($deposit_reminder_two->count()) {
            $this->updateBuyersWithNoTransactions($deposit_reminder_two->pluck('id')->toArray(), 2);
        }

        // send deposit reminder for day 3
        $deposit_reminder_three = $this->getBuyersWithoutWalletTransactionInRegistration(3);

        $this->sendDepositReminderToBuyersWithoutTransaction($deposit_reminder_three, 3);

        if ($deposit_reminder_three->count()) {
            $this->updateBuyersWithNoTransactions($deposit_reminder_three->pluck('id')->toArray(), 3);
        }

        // send deposit reminder for day 4
        $deposit_reminder_four = $this->getBuyersWithoutWalletTransactionInRegistration(4);

        $this->sendDepositReminderToBuyersWithoutTransaction($deposit_reminder_four, 4);

        if ($deposit_reminder_four->count()) {
            $this->updateBuyersWithNoTransactions($deposit_reminder_four->pluck('id')->toArray(), 4);
        }

    }

    /**
     * get buyers without transaction users based on first deposit email date
     *
     * @param $days
     * @return Collection
     */
    private function getBuyersWithoutWalletTransactionInRegistration ($days) {
        $end_days = [
            1 => 2,
            2 => 3,
            3 => 4
        ];

        $no_transactions = Registration::where('type', 'Buyer')
            ->whereNull('verification_code')
            ->where('account_validation', 'valid')
            ->doesntHave('user.wallet_transactions')
            ->where('email_via_others', 'deposit_email');

        if ($days === 4) {
            $no_transactions = $no_transactions->where('deposit_reminded_at', '<=', Carbon::now()->subDays($days));
        } else {
            $no_transactions = $no_transactions->where('deposit_reminded_at', '<=', Carbon::now()->subDays($days))
                ->where('deposit_reminded_at', '>=', Carbon::now()->subDays($end_days[$days]));
        }

        // add 1 for deposit reminders 11, 21, 31
        $no_transactions = $no_transactions->where(function ($query) use ($days) {
            $query->where('reminded_days', '!=', $days . '1')
                ->orWhereNull('reminded_days');
        });

        $no_transactions = $no_transactions->get();

        return $no_transactions;
    }

    /**
     * send email reminder for users
     *
     * @param $no_transactions
     * @param $days
     */
    private function sendDepositReminderToBuyersWithoutTransaction ($no_transactions, $days) {

        if (!$no_transactions->count()) {
            $this->info('No deposit reminder email need to be sent for day ' . $days );

            return;
        }

        $this->info('Starting sending deposit reminder email for day ' . $days);

        foreach ($no_transactions as $no) {
            try {
                SendDepositReminderEmail::dispatch($no, $days)->onQueue('high');
            } catch (\Exception $e) {
                \Log::error($e);
            }
        }

        $this->info('Finished sending deposit reminder email for day ' . $days);

    }

    /**
     * update users
     *
     * @param $ids
     * @param $days
     */
    private function updateBuyersWithNoTransactions ($ids, $days) {
        Registration::whereIn('id', $ids)->update([
            'reminded_days' => $days . '1'
        ]);
    }
}
