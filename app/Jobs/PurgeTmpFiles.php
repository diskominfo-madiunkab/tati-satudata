<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class PurgeTmpFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $tmpFiles;

    public function __construct($tmpFiles)
    {
        $this->tmpFiles = $tmpFiles;
    }

    public function handle()
    {
        foreach ($this->tmpFiles as $file) {
            File::delete($file);
        }
    }
}
