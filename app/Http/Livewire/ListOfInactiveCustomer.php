<?php

namespace App\Http\Livewire;

use App\Jobs\SendBulkSmsToAllJob;
use App\Jobs\SendBulkSmsToInactiveCustomerJob;
use App\Models\Customer;
use App\Models\User;
use App\Traits\InactiveCustomerTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ListOfInactiveCustomer extends Component
{
    use WithPagination, InactiveCustomerTrait;

    public $title = 'List Of Inactive Customer';
    public $status;
    public $keyword;
    public $start_date;
    public $end_date;
    public $employee_id;

    protected $listeners = [
        'update-customer-index' => '$refresh',
    ];

    public array $pageCustomerIds;

    public function render(): View
    {
        $layout = auth()->user()->user_type==0 ? 'admin.layouts.master' : 'user.layouts.master';

        $customers = $this->getCustomers();

        if (auth()->user()->user_type==0) {
            $this->pageCustomerIds = collect($customers->items())->pluck('id')->toArray();
        }

        return view('livewire.list-of-inactive-customer',[
            'customers' => $customers,
            'employees' => $this->getEmployees(),
            'printUrl' => route('print.customer-list.inactive'),
        ])->layout($layout, [
            'title' =>$this->title
        ]);
    }

    public function getCustomers(): LengthAwarePaginator|array
    {
        return Customer::query()
            ->withTransactions()
            ->addSelect(DB::raw('MAX(t.created_at) as last_transaction_date'))
            ->when($this->keyword, function (Builder $builder, $keyword) {
                $builder->where(function ($query) use ($keyword) {
                    $query->where('customers.name', 'like', "%$keyword%")
                        ->orWhere('customers.phone', 'like', "%$keyword%");
                });
            })
            ->when($this->start_date, function (Builder $builder, $start_date) {
                $builder->whereDate('customers.issue_date', '>=', $start_date);
            })
            ->when($this->end_date, function (Builder $builder, $end_date) {
                $builder->whereDate('customers.issue_date', '<=', $end_date);
            })
            ->when($this->employee_id, function (Builder $builder, $employee_id) {
                $builder->where('customers.user_id', '=', $employee_id);
            })
            ->when(request('day'), function (Builder $builder) {
                $builder->where(DB::raw('DATE(created_at)'), date('Y-m-d'));
            })
            // ->havingRaw('MAX(t.created_at) > NOW() - INTERVAL 7 DAY')->orHavingRaw('MAX(t.created_at)')
            ->whereDoesntHave('sales', function (Builder $builder) {
                $builder->whereDate('created_at', '>', today()->subDays(7));
            })
            ->with(['user'])
            //->orderBy('status')
            ->latest(DB::raw('IFNULL(SUM(t.total_amount), 0) - IFNULL(SUM(t.paid_amount), 0)'))
            ->oldest(DB::raw('MAX(t.created_at)'))
            ->latest('customers.created_at')
            ->paginate(RECORDS_PER_PAGE)
            ->through(function (Customer $item) {
                $item->last_transaction_date = $item->last_transaction_date
                    ? Carbon::parse($item->last_transaction_date)
                    : null;
                return $item;
            });
    }

    public function getEmployees(): Collection|array
    {
        return User::all();
    }

    function sendToAll(): void
    {
        if (auth()->user()->user_type!=0) {
            flash('You are not authorized for this action.');
            return;
        }

        dispatch(new SendBulkSmsToInactiveCustomerJob());

        flash('SMS sent to all inactive customers');
    }

    function sendToAllInPage(): void
    {
        if (auth()->user()->user_type!=0) {
            flash('You are not authorized for this action.');
            return;
        }

        dispatch(new SendBulkSmsToInactiveCustomerJob(customerIds: $this->pageCustomerIds));

        flash('SMS sent to all inactive customers');
    }
}
