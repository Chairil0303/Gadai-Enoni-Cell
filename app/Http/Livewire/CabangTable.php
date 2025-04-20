<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cabang;

class CabangTable extends Component
{
    use WithPagination;

    public $search = '';

    protected $listeners = ['refreshCabangs' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $cabang = Cabang::findOrFail($id);
        $cabang->delete();

        session()->flash('message', 'Cabang berhasil dihapus.');

        // Refresh table setelah hapus
        $this->emit('refreshCabangs');
    }

    public function render()
    {
        $cabangs = Cabang::where('nama_cabang', 'like', "%{$this->search}%")->get();

        return view('livewire.cabang-table', [
            'cabangs' => $cabangs,
        ]);
    }
}
