<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
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
            ->where('user_type', '=', USER_EMPLOYEE)
            ->when(request('keyword', $this->keyword), function (Builder $query, $keyword){
                $query->where('name', 'LIKE', '%'. $keyword .'%')
                    ->orWhere('phone', 'LIKE', '%'. $keyword .'%');
            })
            ->latest('id')
            ->paginate(RECORDS_PER_PAGE);
    }
}
