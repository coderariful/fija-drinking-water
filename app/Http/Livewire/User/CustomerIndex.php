<?php

namespace App\Http\Livewire\User;

use App\Models\Customer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerIndex extends Component
{
    use WithPagination;

    public $title;
    public $status;
    public $keyword;
    public $start_date;
    public $end_date;
    public $showDue=false;

    protected $listeners = [
        'update-customer-index' => '$refresh',
    ];

    public function render(): Factory|View|Application
    {
        return view('livewire.user.customer-index', [
            'customers' => $this->getCustomers(),
        ]);
    }

    public function filterDue($val): void
    {
        $this->showDue = $val;
    }

    public function getCustomers(): LengthAwarePaginator|array
    {
        $t = "customers";

        return Customer::query()
            ->withTransactions()
            ->where("$t.user_id", '=', auth()->id())
            ->where("$t.status", '=', $this->status)
            ->when($this->keyword, function (Builder $builder, $keyword) use ($t) {
                $builder->where("$t.name", 'like', "%$keyword%")
                    ->orWhere("$t.phone", 'like', "%$keyword%");
            })
            ->when($this->start_date, function (Builder $builder, $start_date) use ($t) {
                $builder->whereDate("$t.issue_date", '>=', $start_date);
            })
            ->when($this->end_date, function (Builder $builder, $end_date) use ($t) {
                $builder->whereDate("$t.issue_date", '<=', $end_date);
            })
            ->when($this->showDue, function (Builder $builder) {
                $builder->having(DB::raw("(IFNULL(SUM(t.total_amount),0) - IFNULL(SUM(t.paid_amount),0))"), '>', 0);
            })
            ->orderBy("$t.status")
            ->with('user')
            ->latest('id')
            ->paginate(RECORDS_PER_PAGE);
    }
}
