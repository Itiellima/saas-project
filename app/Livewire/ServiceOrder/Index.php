<?php

namespace App\Livewire\ServiceOrder;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ServiceOrder;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{

    use WithPagination;

    public $search = '';

    public $status = '';

    public function render()
    {

        $serviceOrders = ServiceOrder::query()
            ->where('tenant_id', Auth::user()->tenant_id)

            ->when($this->search, function ($query) {
                $query->where('customer_name', 'like', '%' . $this->search . '%')
                    ->orWhere('vehicle_plate', 'like', '%' . $this->search . '%');
            })

            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })

            ->latest()
            ->paginate(10);

        return view('livewire.service-order.index', compact('serviceOrders'));
    }
}
