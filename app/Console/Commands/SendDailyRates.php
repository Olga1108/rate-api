<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Models\Subscription;

class SendDailyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-rates';
    // protected $signature = 'send:daily-rates';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily exchange rate to subscribed emails';

    public function handle()
    {
        $req_url = 'https://v6.exchangerate-api.com/v6/cfd1e3042fba93ce4da3bc25/latest/USD';
        $response_json = file_get_contents($req_url);
        if ($response_json) {
            $response = json_decode($response_json);
            $rate = $response->conversion_rates->UAH;
            $subscriptions = Subscription::all();
            foreach ($subscriptions as $subscription) {
                Mail::raw("Today's exchange rate is: $rate UAH/USD", function ($message) use ($subscription) {
                    $message->to($subscription->email)
                            ->subject('Daily Exchange Rate');
                });
            }
            $this->info('Daily rates sent successfully.');
        } else {
            $this->error('Failed to fetch exchange rates.');
        }
    }
}
    