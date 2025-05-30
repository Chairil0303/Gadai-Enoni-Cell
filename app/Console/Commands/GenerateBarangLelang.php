<?php
namespace App\Console\Commands;

use Illuminate\Console\Scheduling\Attributes\AsScheduled;
use Illuminate\Console\Command;
use App\Models\BarangGadai;
use App\Models\BarangLelang;
use Carbon\Carbon;

#[AsScheduled('daily')] // <== Ini kunci auto-scheduling di Laravel 12!
class GenerateBarangLelang extends Command
{
    protected $signature = 'barang:generate-lelang';
    protected $description = 'Generate entri barang lelang untuk barang tergadai yang melewati tempo lebih dari 7 hari';

    public function handle()
    {
        $barangTerlambat = BarangGadai::where('status', 'Tergadai')
            ->whereDate('tempo', '<', Carbon::now()->subDays(7))
            ->whereDoesntHave('lelang')
            ->get();

        foreach ($barangTerlambat as $barang) {
            BarangLelang::create([
                'no_bon' => $barang->no_bon,
                'id_cabang' => $barang->id_cabang,
                'status_lelang' => 'Menunggu Konfirmasi'
            ]);

            $this->info("Barang no_bon {$barang->no_bon} masuk ke barang_lelang.");
        }

        $this->info('Generate selesai.');
    }
}
