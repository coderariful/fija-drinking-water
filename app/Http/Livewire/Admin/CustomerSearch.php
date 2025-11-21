<?php

namespace App\Http\Livewire\Admin;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class CustomerSearch extends Component
{
    public $keyword;

    public $queryString = [
        'keyword' => 'keyword'
    ];

    protected $listeners = [
        'update-customer-index' => '$refresh',
    ];

    public function render()
    {
        $layout = user()->user_type == USER_ADMIN ? "admin" : "user";

        return view('livewire.admin.customer-search', [
            'customers' => Customer::query()
                ->withTransactions()
                ->when(auth()->user()->user_type == USER_EMPLOYEE, fn($query) => $query->where('user_id', auth()->id()))
                ->when($this->keyword, function (Builder $builder, $keyword) {
                    $builder->where('name', 'like', "%$keyword%")
                        ->orWhere('phone', 'like', "%$keyword%");
                })
                ->with(['user'])
                ->paginate(RECORDS_PER_PAGE)
        ])->extends("$layout.layouts.master", [
            'title' => 'Customer search'
        ]);
    }
}
