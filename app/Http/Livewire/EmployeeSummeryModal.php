<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\CustomerQueryTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EmployeeSummeryModal extends Component
{
    use CustomerQueryTrait;

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

    public function getDatesForFilter(): array
    {
        return [
            'today' => today()->format('Y-m-d'),
            'yesterday' => today()->subDay()->format('Y-m-d'),
            'last_three_days' => today()->subDays(3)->format('Y-m-d'),
            'last_seven_days' => today()->subDays(7)->format('Y-m-d'),
        ];
    }

    public function render(): View|string
    {
        if (!$this->user) {
            return '<div class="text-center py-5">
                <div class="fa fa-spinner fa-spin fa-5x"></div>
                <div class="mt-3 font-weight-bold">Loading...</div>
            </div>';
        }

        $histories = $this->getHistories();

        $groups = $histories?->groupBy('customer_id') ?? [];
        $previous = $this->getPreviousHistories();

        $transactionQuery = fn() => Transaction::query()
            ->when($this->customer_id, fn(Builder $query, $customer_id) => $query->where('customer_id', $customer_id))
            ->where('user_id', $this->user?->id);

        $total_sell = $transactionQuery()->sum('total_amount');
        $total_paid = $transactionQuery()->sum('paid_amount');

        $jar_stock = Transaction::query()
            ->whereProductType(Product::WATER)
            ->selectRaw('(SUM(IFNULL(in_quantity, 0)) - SUM(IFNULL(out_quantity, 0))) AS stock_qty')
            ->where('user_id', $this->user?->id)
            ->first()
            ->stock_qty;

        //dd($jar_stock);

        $jar_in_count = $histories?->sum('in_quantity');
        $jar_out_count = $histories?->sum('out_quantity');

        // $jar_stock = ($jar_in_count+$previous->jar_in)-($jar_out_count+$previous->jar_out);

        return view('livewire.employee-summery-modal', [
            'groups' => $groups,
            'histories' => $histories,
            'customers' => $this->user?->customer ?? [],
            'jar_stock' => $jar_stock ?? 0,
            'jar_in_previous' => $previous->jar_in,
            'jar_out_previous' => $previous->jar_out,
            'jar_in_count' => $jar_in_count,
            'jar_out_count' => $jar_out_count,
            'sell_amount' => $histories->sum('total_amount'),
            'collection_amount' => $histories->sum('paid_amount'),
            'customer'=> Customer::when($this->customer_id, fn($q, $id) => $q->where('id', $id))->first(),
            'showCurrentFilter' => $this->showCurrentFilter(),
            'total_due' => max($total_sell - $total_paid, 0),
        ]);
    }

    private function getHistories(): ?Collection
    {
        $dates = $this->getDatesForFilter();

        return $this->user?->sales()
            // ->where('product_type', PRODUCT_WATER)
            ->when($this->month, fn(Builder $query, $month) => $query->where(DB::raw('MONTH(created_at)'), $month))
            ->when($this->year, fn(Builder $query, $year) => $query->where(DB::raw('YEAR(created_at)'), $year))
            ->when($this->start_date, fn(Builder $query, $start_date) => $query->where(DB::raw('DATE(created_at)'), '>=', $start_date))
            ->when($this->end_date, fn(Builder $query, $end_date) => $query->where(DB::raw('DATE(created_at)'), '<=',$end_date))
            ->when($this->day, fn (Builder $query, $day) => $query->where(DB::raw('DATE(created_at)'), '>=', $dates[$day]))
            ->when($this->customer_id, fn(Builder $query, $customer_id) => $query->where('customer_id', $customer_id))
            ->with(['customer'])
            ->addSelect([
                'stock_qty' => $this->addSelectStockSubquery(PRODUCT_WATER),
                'due_till_date' => $this->addSelectDueTillDateSubquery(),
            ])
            ->get();
    }

    private function getPreviousHistories(): object
    {
        $dates = $this->getDatesForFilter();
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
            ->when($this->customer_id, fn(Builder $query, $customer_id) => $query->where('customer_id', $customer_id))
            // ->where('product_type', PRODUCT_WATER)
            ->whereDate('created_at', '<', $date)
            ->sum('total_amount');

        $previous_payments = $this->user?->sales()
            ->when($this->customer_id, fn(Builder $query, $customer_id) => $query->where('customer_id', $customer_id))
            ->whereDate('created_at', '<', $date)
            ->sum('paid_amount');

        $purchases = $this->user?->sales()
            ->when($this->customer_id, fn(Builder $query, $customer_id) => $query->where('customer_id', $customer_id))
            ->whereDate('created_at', '<', $date);

        return (object)[
            'sales' => round($previous_sales, 2),
            'payments' => round($previous_payments, 2),
            'jar_in' => (int)$purchases?->sum('in_quantity'),
            'jar_out' => (int)$purchases?->sum('out_quantity'),
        ];
    }
}
