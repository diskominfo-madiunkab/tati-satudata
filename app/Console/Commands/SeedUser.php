<?php

namespace App\Console\Commands;

use App\Imports\SeedUsersImport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SeedUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = storage_path('app/private/password.csv');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        try {
            Excel::import(new SeedUsersImport(), $filePath);
            $this->info("Users imported successfully!");
            return 0;
        } catch (\Exception $e) {
            $this->error("ERROR: " . $e->getMessage());
            return 1;
        }
    }
}
