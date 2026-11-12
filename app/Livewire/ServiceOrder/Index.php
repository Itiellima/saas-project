<?php

namespace App\Livewire\ServiceOrder;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ServiceOrder;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{

    use WithPagination;

    // Na URL: a busca do topo da página chega aqui por ?search=
    #[Url(except: '')]
    public $search = '';

    #[Url(except: '')]
    public $status = '';

    // Um filtro novo começa da primeira página
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function render()
    {

        $serviceOrders = ServiceOrder::query()
            ->where('tenant_id', Auth::user()->tenant_id)

            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('customer_name', 'like', '%' . $this->search . '%')
                        ->orWhere('vehicle_plate', 'like', '%' . $this->search . '%');
                });
            })

            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })

            ->latest()
            ->paginate(10);

        return view('livewire.service-order.index', compact('serviceOrders'));
    }
}
