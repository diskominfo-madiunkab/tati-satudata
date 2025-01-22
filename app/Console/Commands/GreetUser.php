<?php

namespace App\Console\Commands;

use App\Exports\ExcelExport;
use App\Models\Berkas;
use App\Models\Data;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class GreetUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'greet:user';

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
        ini_set('memory_limit', '256M');
        // Asking for user input
        // $name = $this->ask('What is your name?');

        // // Asking for confirmation
        // if ($this->confirm('Do you want a personalized greeting?')) {
        //     $timeOfDay = $this->choice(
        //         'File: ... =',
        //         ['1|sdasd/sasfaa.xlsx', 'Afternoon', 'Evening'],
        //         0
        //     );
        //     $this->info("Good $timeOfDay, $name!");
        // } else {
        //     $this->info("Hello, $name!");
        // }

        //xlsx
        $data = Data::query()
            ->with(['berkas' => function ($query) {
                return $query->where('name', 'LIKE', '%.xlsx');
            }, 'visualtable.header.isi'])
            ->whereHas('berkas', function ($query) {
                $query->where('name', 'LIKE', '%.xlsx');
            })
            ->get()
            ->toArray();

        foreach ($data as $key => $d) {
            foreach ($d['visualtable'] as $item) {
                $array = [];
                $berkas = null;
                if (count($d['berkas']) > 1) {
                    $listBerkas = [];
                    foreach ($d['berkas'] as $key => $value) {
                        $listBerkas[] = $value['id'] . '|' . $value['name'];
                    }

                    $timeOfDay = $this->choice(
                        'File: ' . $item['namatabel'] . ' =',
                        $listBerkas,
                        0
                    );
                    $berkas = Berkas::find(explode('|', $timeOfDay)[0]);
                } else {
                    $berkas = $d['berkas'][0];
                }
                foreach ($item['header'] as $key => $value) {
                    $loop = 0;
                    foreach ($value['isi'] as $key => $value2) {
                        $array[$loop][$value['header']] = $value2['isi'] ?? "-";
                        $loop++;
                    }
                }
                //export

                if (!Storage::exists($berkas['path'])) {
                    Excel::store(new ExcelExport($array, $item['namatabel'], collect($item['header'])->pluck('header')->toArray()), $berkas['path']);
                }
            }
        }

        //csv
        $data = Data::query()
            ->with(['berkas' => function ($query) {
                return $query->where('name', 'LIKE', '%.csv');
            }, 'visualtable.header.isi'])
            ->whereHas('berkas', function ($query) {
                $query->where('name', 'LIKE', '%.csv');
            })
            ->get()
            ->toArray();

        foreach ($data as $key => $d) {
            foreach ($d['visualtable'] as $item) {
                $array = [];
                $berkas = null;
                if (count($d['berkas']) > 1) {
                    $listBerkas = [];
                    foreach ($d['berkas'] as $key => $value) {
                        $listBerkas[] = $value['id'] . '|' . $value['name'];
                    }

                    $timeOfDay = $this->choice(
                        'File: ' . $item['namatabel'] . ' =',
                        $listBerkas,
                        0
                    );
                    $berkas = Berkas::find(explode('|', $timeOfDay)[0]);
                } else {
                    $berkas = $d['berkas'][0];
                }
                foreach ($item['header'] as $key => $value) {
                    $loop = 0;
                    foreach ($value['isi'] as $key => $value2) {
                        $array[$loop][$value['header']] = $value2['isi'] ?? "-";
                        $loop++;
                    }
                }
                //export

                if (!Storage::exists($berkas['path'])) {
                    Excel::store(new ExcelExport($array, $item['namatabel'], collect($item['header'])->pluck('header')->toArray()), $berkas['path']);
                }
            }
        }
        return 0;
    }
}
