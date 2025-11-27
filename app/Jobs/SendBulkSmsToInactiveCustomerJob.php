<?php

namespace App\Jobs;

use App\Helpers\SMS;
use App\Models\Customer;
use App\Models\SmsSendBulk;
use App\Models\SmsTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SendBulkSmsToInactiveCustomerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Collection|array $customers;
    private null|SmsTemplate|Model $template;

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

        $this->customers = Customer::query()
            ->withTransactions()
            ->addSelect(DB::raw('MAX(t.created_at) as last_transaction_date'))
            ->whereDoesntHave('sales', function (Builder $builder) {
                $builder->whereDate('created_at', '>', today()->subDays(7));
            })
            ->where('send_sms', 1)
            ->where('status', CUSTOMER_APPROVED)
            ->whereNotIn('id', $alreadySent)
            ->get()
            ->filter(fn($customer) => intval($customer->phone)!=0)
            ->toArray();

        $this->template = SmsTemplate::firstWhere('template', 'inactive-customer-sms');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $customers = $this->customers;
        // dd($customers[0]);


        foreach (array_chunk($customers, 99) as $chunk) {
            $sdk = new SMS();

            $data = [];
            foreach ($chunk as $customer) {
                $params = [
                    "{due_amount}"  => round(floatval($customer['due_amount']), 2),
                    "{jar_stock}"   => round(floatval($customer['jar_stock']), 2),
                ];
                $message = SMS::parseTemplate($this->template->body, $params);
                if (intval($customer['due_amount'])>0 && $customer['send_sms'] == 1) {
                    $sentToday = false;
                    //$sentToday = SmsSendBulk::where('customer_id', $customer['id'])->whereDate('created_at', today()->format('Y-m-d'))->first();
                    if (!$sentToday) {
                        $mobile = $customer['phone'];
                        $data[] = [
                            'mobile' => strlen($mobile) == 11 ? "88$mobile" : (strlen($mobile) == 10 ? "880$mobile" : $mobile),
                            'sms' => $message
                        ];
                        /*$sdk->addBulkSmsMessage([
                            'mobile' => $customer->phone,
                            'sms' => $message
                        ]);*/

                        /*$history = SMS::saveInHistory($customer->id, $customer->phone, $message, 'bulk:inactive-customer-sms');
                        SmsSendBulk::create([
                            'group_id' => $this->groupId,
                            'customer_id' => $customer->id,
                            'history_id' => $history->id,
                            'type' => 'group-chunk-sms',
                        ]);*/
                    }
                }
            }

            //dd($data);

            try {
                $response = $sdk->sendBulk($data, mask: true);

                SmsSendBulk::create([
                    'group_id' => $this->groupId,
                    'type' => 'group-chunk-response',
                    'response' => json_encode($response),
                ]);
            } catch (\Exception $e) {
                $error = "Error: {$e->getMessage()} File: {$e->getFile()} Line: {$e->getLine()}";
                \Log::error($error);
                SmsSendBulk::create(['group_id' => $this->groupId,'type' => 'group-chunk-error','response' => $error]);
            }
        }
    }
}
