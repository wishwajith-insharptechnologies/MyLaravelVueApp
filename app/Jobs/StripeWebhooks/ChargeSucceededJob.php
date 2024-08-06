<?php

namespace App\Jobs\StripeWebhooks;

use App\Models\Payments;
use App\Services\PaymentServices;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Spatie\WebhookClient\Models\WebhookCall;

class ChargeSucceededJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $webhookCall;
    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::error('call web hook');
            $payload = $this->webhookCall->payload;

            PaymentServices::stripeWebHookListening($payload);

        } catch (\Exception $e) {
            Log::error('Error processing charge succeeded webhook:');
            Log::error($e->getMessage());
        }

    }
}
