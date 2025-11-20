<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use App\Models\Transaction;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PurchaseHistoryModal extends Component
{
    public ?Customer $customer = null;

    protected $listeners = [
        'open-modal' => 'openModal'
    ];
    public $filterType='month';
    public $month;
    public $year;
    public $start_date;
    public $end_date;
    public $date_created = [];
    public $in_quantity = [];
    public $out_quantity = [];
    public $payment = [];

    public function mount(): void
    {
        $this->month = date('n');
        $this->year = date('Y');
    }

    public function updatedFilterType($value): void
    {
        switch ($value) {
            case 'date':
            {
                $this->reset(['month', 'year']);
                $this->start_date = today()->subMonth()->format('Y-m-d');
                $this->end_date = today()->format('Y-m-d');
                break;
            }
            case 'month':
            default:
            {
                $this->reset(['start_date', 'end_date']);
                $this->month = date('n');
                $this->year = date('Y');
                break;
            }
        }

    }

    public function openModal(Customer $customer): void
    {
        $this->loadData($customer);
        $this->month = date('n');
        $this->year = date('Y');
        $this->filterType = 'month';
    }

    public function loadData(Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function saveDateUpdate(Transaction $transaction): void
    {
        try {
            $transaction->created_at = $this->date_created[$transaction->id];
            $transaction->save();

            flash(trans('History date updated.'));

            $this->dispatch('entryUpdated');
        } catch (Exception $e) {
            toastr($e->getMessage() . "<br>Please try again!", 'error', 'Server Error');
        }
    }

    public function saveIssueUpdate(Transaction $transaction): void
    {
        try {
            $transaction->in_quantity = $this->in_quantity[$transaction->id];
            $transaction->save();

            $transaction->quantity = $transaction->in_quantity;
            $transaction->total_amount = $transaction->in_quantity * $transaction->rate;
            $transaction->save();

            flash(trans('History issue entry updated.'));

            $this->dispatch('entryUpdated');
        } catch (Exception $e) {
            toastr($e->getMessage() . "<br>Please try again!", 'error', 'Server Error');
        }
    }

    public function saveReturnUpdate(Transaction $transaction): void
    {
        try {
            $transaction->out_quantity = $this->out_quantity[$transaction->id];
            $transaction->save();

            flash(trans('History return entry updated.'));

            $this->dispatch('entryUpdated');
        } catch (Exception $e) {
            toastr($e->getMessage() . "<br>Please try again!", 'error', 'Server Error');
        }
    }

    public function savePaymentUpdate(Transaction $transaction): void
    {
        try {
            $payment = $transaction->payment;
            $payment->amount = $this->payment[$transaction->id];
            $payment->save();

            if ($transaction->payment) {
            } else {
                $payment = $transaction->payment()->create([
                    'amount' => $this->payment[$transaction->id],
                    'created_at' => $transaction->created_at,
                    'customer_id' => $transaction->customer_id,
                    'user_id' => $transaction->customer->user_id,
                    'note' => $transaction->note,
                ]);

                $transaction->update(['payment_id' => $payment->id]);
            }

            flash(trans('History payment entry updated.'));

            $this->dispatch('entryUpdated');
        } catch (Exception $e) {
            toastr($e->getMessage() . "<br>Please try again!", 'error', 'Server Error');
        }
    }

    public function render(): View
    {
        $previous_due = $this->getPreviousDue();

        $histories = $this->getHistories();

        foreach ($histories ?? [] as $history) {
            $this->in_quantity[$history->id] = $history->in_quantity;
            $this->out_quantity[$history->id] = $history->out_quantity;
            $this->date_created[$history->id] = $history->created_at?->format('Y-m-d');
            $this->payment[$history->id] = round($history->payment?->amount ?? 0);
        }

        return view('livewire.purchase-history-modal', [
            'histories'=> $histories,
            'previous_due' => $previous_due,
        ]);
    }

    private function getHistories(): Collection|array|null
    {
        if (!$this->customer) {
            return [];
        }

        return $this->customer->sales()
            ->orderBy('created_at')
            ->when($this->month, fn(Builder $query, $month) => $query->where(DB::raw('MONTH(created_at)'), $month))
            ->when($this->year, fn(Builder $query, $year) => $query->where(DB::raw('YEAR(created_at)'), $year))
            ->when($this->start_date, fn(Builder $query, $start_date) => $query->where(DB::raw('DATE(created_at)'), '>=', $start_date))
            ->when($this->end_date, fn(Builder $query, $end_date) => $query->where(DB::raw('DATE(created_at)'), '<=',$end_date))
            ->addSelect([
                'stock_qty' => $this->addSelectStockSubquery(PRODUCT_WATER),
            ])
            ->get();
    }

    private function getPreviousDue():float
    {
        $date = today();

        if ($this->filterType == 'month'){
            $date = date(sprintf("%s-%s-1", $this->year, $this->month));
            $date = Carbon::parse($date)->startOfMonth()->toDateString();
        }
        if($this->filterType == 'date') {
            $date = Carbon::parse($this->start_date)->toDateString();
        }

        $previous_sales = $this->customer?->sales()
            ->where('product_type', PRODUCT_WATER)
            ->whereDate('created_at', '<', $date)
            ->sum('total_amount');

        $previous_payments = $this->customer?->sales()
            ->whereDate('created_at', '<', $date)
            ->sum('paid_amount');

        return round($previous_sales - $previous_payments);
    }

    public function placeholder(): string
    {
        return <<<'HTML'
            <div class="animate-pulse space-y-4">
                <div class="h-6 bg-gray-300 rounded w-1/4"></div>
                <div class="h-4 bg-gray-300 rounded w-3/4"></div>
                <div class="h-4 bg-gray-300 rounded w-2/4"></div>
                <div class="h-4 bg-gray-300 rounded w-5/6"></div>
            </div>
        HTML;
    }

    private function addSelectStockSubquery(string $PRODUCT_WATER): Builder|Transaction
    {
        return Transaction::from('transactions as t2')
            ->selectRaw('IFNULL(SUM(t2.in_quantity),0)-IFNULL(SUM(t2.out_quantity),0) AS jar_stock')
            ->whereColumn('t2.customer_id', 'transactions.customer_id')
            ->where('t2.product_type', $PRODUCT_WATER)
            ->whereColumn('t2.created_at', '<=', 'transactions.created_at')
            ->orderBy('created_at')
            ->orderBy('id')
            ->limit(1);
    }
}
