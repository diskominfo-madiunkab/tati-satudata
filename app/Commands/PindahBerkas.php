<?php

namespace App\Commands;

use App\Models\Berkas;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PindahBerkas extends Command
{
    protected $signature = 'metadata:pindah-berkas';

    protected $description = 'Pindah berkas dari storage/app/berkas/ ke public/exports/nama-data/';

    public function handle()
    {
        if (!$this->confirm('Apakah Anda yakin akan memindah berkas?')) {
            return 0;
        }
        $stats = [
            'skipped' => 0,
            'moved' => 0,
            'error' => 0,
        ];

        $allBerkas = Berkas::with('data')->cursor();

        if (!Storage::exists('public/exports')) {
            Storage::makeDirectory('public/exports');
        }

        foreach ($allBerkas as $berkas) {
            if (!Storage::exists($berkas->path)) {
                $stats['skipped']++;
                $this->warn('[SKIP] File does not exist: ' . $berkas->path);
                continue;
            }

            if (!Str::startsWith($berkas->path, 'berkas/')) {
                $stats['skipped']++;
                $this->warn('[SKIP] File already moved: ' . $berkas->path);
                continue;
            }

            $dataDir = Str::slug($berkas->data->nama_data);
            if (Storage::exists('public/exports/' . $dataDir)) {
                Storage::makeDirectory('public/exports/' . $dataDir);
            }

            try {
                DB::beginTransaction();

                Storage::move($berkas->path, 'public/exports/' . $dataDir . '/' . $berkas->name);
                $newLocation = str_replace('berkas/', 'public/exports/' . $dataDir . '/', $berkas->path);

                $this->info('[MOVED] From: ' . $berkas->path . ' | To: ' . $newLocation);

                $berkas->update(['path' => $newLocation]);
                $stats['moved']++;

                DB::commit();
            } catch (Exception $exception) {
                DB::rollBack();

                $stats['error']++;
                $this->error('[ERROR] Cannot move file: ' . $berkas->path . ' | ' . $exception->getMessage());
            }
        }

        $this->newLine();
        $this->info("[MOVED]\t" . $stats['moved']);
        $this->warn("[SKIP]\t" . $stats['skipped']);
        $this->error("[ERROR]\t" . $stats['error']);
        $this->line('-----');
        $this->info('Done. Processed Total: ' . (array_sum(array_values($stats))));

        return 0;
    }
}
