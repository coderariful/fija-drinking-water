<?php

namespace App\Jobs;

use App\Helpers\SMS;
use App\Models\Customer;
use App\Models\SmsSendBulk;
use App\Models\SmsTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Log;

class SendBulkSmsToAllJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array|Collection $customers;
    private null|SmsTemplate $template;

    /**
     * Create a new job instance.
     */
    public function __construct(public $groupId = null)
    {
        $this->groupId = $groupId ?? uniqid();

        $alreadySent = SmsSendBulk::select('customer_id')
            ->where('group_id', $this->groupId)
            ->where('type', 'group-chunk-sms')
            ->pluck('customer_id');

        // $query_sum_sales_total_cost = 'IFNULL((SELECT sum(sales.total_cost) FROM sales WHERE customers.id = sales.customer_id),0)';
        // $query_sum_payments_amount = 'IFNULL((SELECT sum(payments.amount) FROM payments WHERE customers.id = payments.customer_id),0)';

        $this->customers = Customer::query()
            ->withTransactions()
            // ->where(DB::raw("($query_sum_sales_total_cost - $query_sum_payments_amount)"), '>', 0)
            ->where('send_sms', 1)
            ->where('status', CUSTOMER_APPROVED)
            ->whereNotIn('id', $alreadySent)
            ->oldest()
            ->get();

        $this->template = SmsTemplate::firstWhere('template', 'due-sms');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $customers = $this->customers->filter(fn($customer) => intval($customer->phone)!=0);

        foreach ($customers->chunk(99) as $chunk) {
            $sdk = new SMS();

            foreach ($chunk as $customer) {
                $params = SMS::getParameters($customer, 'due-sms');
                $message = SMS::parseTemplate($this->template->body, $params);
                if (intval($customer->due_amount)>0 && $customer->send_sms) {
                    $sentToday = SmsSendBulk::where('customer_id', $customer->id)->whereDate('created_at', today()->format('Y-m-d'))->first();
                    if (!$sentToday) {
                        $sdk->addBulkSmsMessage([
                            'mobile' =>  $customer->phone,
                            'sms' => $message
                        ]);

                        $history = SMS::saveInHistory($customer->id, $customer->phone, $message, 'bulk:due-sms');
                        SmsSendBulk::create([
                            'group_id' => $this->groupId,
                            'customer_id' => $customer->id,
                            'history_id' => $history->id,
                            'type' => 'group-chunk-sms',
                        ]);
                    }
                }
            }

            try {
                $response = $sdk->sendBulk(mask: true);

                SmsSendBulk::create([
                    'group_id' => $this->groupId,
                    'type' => 'group-chunk-response',
                    'response' => json_encode($response),
                ]);
            } catch (\Exception $e) {
                $error = "Error: {$e->getMessage()} File: {$e->getFile()} Line: {$e->getLine()}";
                Log::error($error);
                SmsSendBulk::create(['group_id' => $this->groupId,'type' => 'group-chunk-error','response' => $error]);
            }
        }

    }
}
