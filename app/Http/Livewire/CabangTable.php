<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cabang;

class CabangTable extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $cabangs = Cabang::where('nama_cabang', 'like', "%{$this->search}%")->paginate(5);
        return view('livewire.cabang-table', ['cabangs' => $cabangs]);
    }
}

