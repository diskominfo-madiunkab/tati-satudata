<?php

namespace App\Jobs;

use App\Exports\IndikatorExport;
use App\Exports\VariabelExport;
use App\Services\CkanApi\Facades\CkanApi;
use Exception;
use GuzzleHttp\Psr7\MimeType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SendFilesToCKAN implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    private $datasetId;

    public function __construct($data, $datasetId)
    {
        $this->data = $data;
        $this->datasetId = $datasetId;
    }

    public function handle()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        Storage::makeDirectory('public/exports/' . Str::slug($this->data->nama_data));

        $jenisData = strtolower($this->data->jenis_data);
        $relativePath = 'public/exports/' . Str::slug($this->data->nama_data) . '/' . $this->data->tahun;
        $metadataPath = Storage::path($relativePath . '/Metadata.xlsx');

        Storage::makeDirectory($relativePath);

        if ($jenisData === 'indikator') {
            $metadata = new IndikatorExport($this->data->indikator, $this->data->opd);
        } else if ($jenisData === 'variabel') {
            $metadata = new VariabelExport($this->data->variabel, $this->data->opd);
            $metadata->standarData($this->data->standar);
        }

        try {
            if ($metadata->export($metadataPath)) {
                $sendToCKAN = CkanApi::resource()->create([
                    'package_id' => $this->datasetId,
                    'url' => asset(Storage::url('public/exports/' . Str::slug($this->data->nama_data) . '/' . $this->data->tahun . '/Metadata.xlsx')),
                    'name' => 'Metadata.xlsx',
                    'format' => 'XLSX',
                    'mimetype' => MimeType::fromExtension('xlsx')
                ]);
                if (!$sendToCKAN) {
                    throw new Exception('Gagal mengirim metadata ke CKAN: ' . json_encode($sendToCKAN));
                }
            } else {
                throw new Exception('Gagal export file metadata');
            }
        } catch (Exception $exception) {
            Log::error(sprintf('[SendFilesToCKAN] Failed to upload metadata (ID: %s) to ckan: %s', $this->datasetId, $exception->getMessage()), [$this->datasetId, $jenisData, $metadataPath]);
        }

        foreach ($this->data->berkas as $berkas) {
            if (!Storage::exists($berkas->path)) {
                Log::error(sprintf('[SendFilesToCKAN] File not found | ID: %s - Path: %s', $this->data->id, $berkas->path));
                continue;
            }

            $res = CkanApi::resource()->create(array_filter([
                'package_id' => $this->datasetId,
                'url' => asset(safe_storage_url($berkas->path)),
                'name' => $berkas->name,
                'format' => $ext = pathinfo($berkas->name, PATHINFO_EXTENSION),
                'mimetype' => MimeType::fromExtension($ext)
            ]));

            if (isset($res['result'])) {
                $berkas->update([
                    'resource_id' => $res['result']['id']
                ]);
            } else {
                Log::error('[SendFilesToCKAN] Failed to upload file to ckan ' . $berkas->id, [json_encode($res)]);
            }
        }
    }
}

function safe_storage_url($path)
{
    $url = Storage::url($path);
    $parsed = parse_url($url);
    $encodedPath = implode('/', array_map('rawurlencode', explode('/', $parsed['path'] ?? '')));
    return asset($encodedPath);
}
