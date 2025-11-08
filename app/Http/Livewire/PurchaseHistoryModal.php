<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Sale;
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
        'open-modal' => 'loadData'
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

    public function loadData(Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function saveDateUpdate(Purchase $purchase): void
    {
        try {
            $purchase->created_at = $this->date_created[$purchase->id];
            $purchase->save();

            if ($purchase->sale) {
                $purchase->sale->created_at = $purchase->created_at;
                $purchase->sale->save();
            }

            if ($purchase->payment) {
                $purchase->payment->created_at = $purchase->created_at;
                $purchase->payment->save();
            }

            flash(trans('History date updated.'));

            $this->dispatch('entryUpdated');
        } catch (Exception $e) {
            toastr($e->getMessage() . "<br>Please try again!", 'error', 'Server Error');
        }
    }

    public function saveIssueUpdate(Purchase $purchase): void
    {
        try {
            $purchase->in_quantity = $this->in_quantity[$purchase->id];
            $purchase->save();

            if ($purchase->sale) {
                $sale = $purchase->sale;
                $sale->quantity = $purchase->in_quantity;
                $sale->total_cost = $purchase->in_quantity * $sale->rate;
                $sale->save();
            }

            flash(trans('History issue entry updated.'));

            $this->dispatch('entryUpdated');
        } catch (Exception $e) {
            toastr($e->getMessage() . "<br>Please try again!", 'error', 'Server Error');
        }
    }

    public function saveReturnUpdate(Purchase $purchase): void
    {
        try {
            $purchase->out_quantity = $this->out_quantity[$purchase->id];
            $purchase->save();

            flash(trans('History return entry updated.'));

            $this->dispatch('entryUpdated');
        } catch (Exception $e) {
            toastr($e->getMessage() . "<br>Please try again!", 'error', 'Server Error');
        }
    }

    public function savePaymentUpdate(Purchase $purchase): void
    {
        try {
            if ($purchase->payment) {
                $payment = $purchase->payment;
                $payment->amount = $this->payment[$purchase->id];
                $payment->save();
            } else {
                $payment = $purchase->payment()->create([
                    'amount' => $this->payment[$purchase->id],
                    'created_at' => $purchase->created_at,
                    'customer_id' => $purchase->customer_id,
                    'user_id' => $purchase->customer->user_id,
                    'note' => $purchase->note,
                ]);

                $purchase->update(['payment_id' => $payment->id]);
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
        return $this->customer?->purchase()
            ->with(['sale', 'payment'])
            ->orderBy('created_at')
            ->where('product_type', PRODUCT_WATER)
            ->when($this->month, fn(Builder $query, $month) => $query->where(DB::raw('MONTH(created_at)'), $month))
            ->when($this->year, fn(Builder $query, $year) => $query->where(DB::raw('YEAR(created_at)'), $year))
            ->when($this->start_date, fn(Builder $query, $start_date) => $query->where(DB::raw('DATE(created_at)'), '>=', $start_date))
            ->when($this->end_date, fn(Builder $query, $end_date) => $query->where(DB::raw('DATE(created_at)'), '<=',$end_date))
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
            ->sum('total_cost');

        $previous_payments = $this->customer?->payments()
            ->whereDate('created_at', '<', $date)
            ->sum('amount');

        return round($previous_sales - $previous_payments);
    }
}
