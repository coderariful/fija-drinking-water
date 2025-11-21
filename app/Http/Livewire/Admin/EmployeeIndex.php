<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class EmployeeIndex extends Component
{
    public $keyword;
    public $title;

    public function render(): View
    {
        return view('livewire.admin.employee-index', [
            'users' => $this->getUsers()
        ]);
    }

    private function getUsers(): LengthAwarePaginator|array
    {
        return User::query()
            ->withTransactions()
            ->when(request('keyword', $this->keyword), function (Builder $query, $keyword){
                $query->where('name', 'LIKE', '%'. $keyword .'%')
                    ->orWhere('phone', 'LIKE', '%'. $keyword .'%');
            })
            // ->havingRaw('exists ( select c.id from customers c where t.customer_id = c.id )')
            ->latest('id')
            ->paginate(RECORDS_PER_PAGE);
    }
}
