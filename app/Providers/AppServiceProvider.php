<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Http\Livewire\CabangTable;
use Carbon\Carbon;
use App\Models\BarangGadai;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\Transaksi;
use App\Observers\TransaksiObserver;


class AppServiceProvider extends ServiceProvider
{


    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');

        //
        Livewire::component('cabang-table', CabangTable::class);

        View::composer('*', function ($view) {
            $query = BarangGadai::where('no_bon', 'LIKE', '%DM%');

            if (auth()->check() && auth()->id() != 1 && Schema::hasColumn('barang_gadai', 'id_cabang')) {
                $query->where('id_cabang', auth()->user()->id_cabang);
            }

            $jumlahUbahNoBon = $query->count();

            $view->with('jumlahUbahNoBon', $jumlahUbahNoBon);
        });

        Transaksi::observe(TransaksiObserver::class);
    }
}
// app providers
