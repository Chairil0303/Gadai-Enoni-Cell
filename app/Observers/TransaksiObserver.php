<?php

namespace App\Observers;

use App\Models\Transaksi;
use App\Models\SaldoCabang;

class TransaksiObserver
{
    /**
     * Handle the Transaksi "created" event.
     */
    public function created(Transaksi $transaksi)
    {
        $saldo = SaldoCabang::where('id_cabang', $transaksi->id_cabang)->first();
        if (!$saldo || !$transaksi->jumlah) return;

        if ($transaksi->arus_kas === 'masuk') {
            $saldo->saldo_saat_ini += $transaksi->jumlah;
        } elseif ($transaksi->arus_kas === 'keluar') {
            $saldo->saldo_saat_ini -= $transaksi->jumlah;
        }

        $saldo->save();
    }

    /**
     * Handle the Transaksi "updated" event.
     */
    public function updated(Transaksi $transaksi): void
    {
        //
    }

    /**
     * Handle the Transaksi "deleted" event.
     */
    public function deleted(Transaksi $transaksi): void
    {
        //
    }

    /**
     * Handle the Transaksi "restored" event.
     */
    public function restored(Transaksi $transaksi): void
    {
        //
    }

    /**
     * Handle the Transaksi "force deleted" event.
     */
    public function forceDeleted(Transaksi $transaksi): void
    {
        //
    }
}
