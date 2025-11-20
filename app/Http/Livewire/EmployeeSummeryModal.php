<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EmployeeSummeryModal extends Component
{
    public ?User $user = null;

    public $filterType='day';
    public $month;
    public $year;
    public $start_date;
    public $end_date;
    public $day;
    public $customer_id;

    public $days = [
        'today' => 'Today',
        'yesterday' => 'Yesterday',
        'last_three_days' => 'Last Three Days',
        'last_seven_days' => 'Last Seven Days',
    ];

    protected $listeners = [
        'open_modal' => 'loadData'
    ];

    public function mount(): void
    {
        $this->day = 'today';
    }

    public  function updatedCustomerId($value): void
    {
        $this->customer_id = !empty($value) ? $value : null;
    }

    public function updatedFilterType($value): void
    {
        switch ($value) {
            case 'day':
                $this->reset(['month', 'year', 'start_date', 'end_date']);
                $this->day = 'today';
                break;
            case 'date':
                $this->reset(['month', 'year', 'day']);
                $this->start_date = today()->subMonth()->format('Y-m-d');
                $this->end_date = today()->format('Y-m-d');
                break;
            case 'month':
            default:
                $this->reset(['day', 'start_date', 'end_date']);
                $this->month = date('n');
                $this->year = date('Y');
                break;
        }
    }

    public function loadData(User $user): void
    {
        $this->user = $user;
    }

    protected function showCurrentFilter(): array|string|null
    {
        return match ($this->filterType) {
            'day' => match ($this->day) {
                'today' => trans('Today'),
                'yesterday' => trans('Yesterday'),
                'last_three_days' => trans('Last Three Days'),
                'last_seven_days' => trans('Last Seven Days'),
            },
            'date' => join(' ', [
                Carbon::parse($this->start_date)->format('d-M-Y'),
                trans('to'),
                Carbon::parse($this->end_date)->format('d-M-Y')
            ]),
            'month' => join(', ', [
                Carbon::create()->day(1)->month($this->month)->monthName,
                $this->year
            ]),
        };
    }

    public function render(): View
    {
        $dates = [
            'today' => today()->format('Y-m-d'),
            'yesterday' => today()->subDay()->format('Y-m-d'),
            'last_three_days' => today()->subDays(3)->format('Y-m-d'),
            'last_seven_days' => today()->subDays(7)->format('Y-m-d'),
        ];

        $histories = $this->getHistories($dates);
        $saleIds = $histories?->pluck('sale_id') ?? [];
        $paymentIds = $histories?->pluck('payment_id') ?? [];

        $groups = $histories?->groupBy('customer_id') ?? [];
        $previous = $this->getPreviousHistories($dates);

        $total_sell = Transaction::where('user_id', $this->user?->id)->sum('total_cost');
        $total_paid = Transaction::where('user_id', $this->user?->id)->sum('amount');

        return view('livewire.employee-summery-modal', [
            'groups' => $groups,
            'histories' => $histories,
            'customers' => $this->user?->customer ?? [],
            'jar_in_previous' => $previous->jar_in,
            'jar_out_previous' => $previous->jar_out,
            'jar_in_count' => $histories?->sum('in_quantity'),
            'jar_out_count' => $histories?->sum('out_quantity'),
            'sell_amount' => Transaction::whereIn('id', $saleIds)->sum('total_cost'),
            'collection_amount' => Transaction::whereIn('id', $paymentIds)->sum('amount'),
            'customer'=> Customer::when($this->customer_id, fn($q, $id) => $q->where('id', $id))->first(),
            'showCurrentFilter' => $this->showCurrentFilter(),
            'total_due' => max($total_sell - $total_paid, 0),
        ]);
    }

    private function getHistories(array $dates): ?Collection
    {
        return $this->user?->purchases()
            ->where('product_type', PRODUCT_WATER)
            ->when($this->month, fn(Builder $query, $month) => $query->where(DB::raw('MONTH(purchases.created_at)'), $month))
            ->when($this->year, fn(Builder $query, $year) => $query->where(DB::raw('YEAR(purchases.created_at)'), $year))
            ->when($this->start_date, fn(Builder $query, $start_date) => $query->where(DB::raw('DATE(purchases.created_at)'), '>=', $start_date))
            ->when($this->end_date, fn(Builder $query, $end_date) => $query->where(DB::raw('DATE(purchases.created_at)'), '<=',$end_date))
            ->when($this->day, fn (Builder $query, $day) => $query->where(DB::raw('DATE(purchases.created_at)'), '>=', $dates[$day]))
            ->when($this->customer_id, fn(Builder $query, $customer_id) => $query->where(DB::raw('customers.id'), $customer_id))
            ->with(['customer', 'payment', 'sale'])
            ->get();
    }

    private function getPreviousHistories(array $dates): object
    {
        $date = today();

        if ($this->filterType == 'month'){
            $date = date(sprintf("%s-%s-1", $this->year, $this->month));
            $date = Carbon::parse($date)->startOfMonth()->toDateString();
        }
        if($this->filterType == 'date') {
            $date = Carbon::parse($this->start_date)->toDateString();
        }
        if ($this->filterType == 'day') {
            $date = $dates[$this->day];
        }

        $previous_sales = $this->user?->sales()
            ->where('product_type', PRODUCT_WATER)
            ->whereDate('sales.created_at', '<', $date)
            ->sum('total_cost');

        $previous_payments = $this->user?->payments()
            ->whereDate('payments.created_at', '<', $date)
            ->sum('amount');

        $purchases = $this->user?->purchases()
            ->whereDate('purchases.created_at', '<', $date);

        return (object)[
            'sales' => round($previous_sales),
            'payments' => round($previous_payments),
            'jar_in' => (int)$purchases?->sum('in_quantity'),
            'jar_out' => (int)$purchases?->sum('out_quantity'),
        ];
    }
}
