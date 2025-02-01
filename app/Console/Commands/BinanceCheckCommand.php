<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BinanceCheckCommand extends Command
{
    const COMMAND = 'binance:check';

    protected $signature = self::COMMAND;

    public function handle()
    {
        $email = config("custom.email");
        $strategy = intval(config("custom.strategy"));
        $limit = config("custom.limit");
        $userId = config("custom.root_user_id");

        $endpoint = 'https://www.binance.com/bapi/futures/v1/public/future/common/strategy/landing-page/queryRoiChart';

        $response = Http::post($endpoint, [
            'rootUserId' => $userId,
            'strategyId' => $strategy,
            'streamerStrategyType' => "UM_GRID"
        ]);

        $data = array_reverse($response->json()["data"]);

        if (count($data) == 0) {
            Log::info("No data found for strategy: $strategy");
            return;
        }

        $pnl = $data[0]["pnl"];

        if ($pnl < $limit) {
            Log::info("Job Check: $strategy - $$pnl");
            return;
        }

        Mail::raw("Binance Strategy: $strategy<br/>Reached limit $limit</br>PNL: $pnl", function ($message) use ($email) {
            $message->to($email)->subject("Binance Strategy Alert");
        });
    }
}
