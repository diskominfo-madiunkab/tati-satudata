<?php

namespace App\Exports;

use Exception;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

abstract class MetadataExport
{
    protected $metadata;
    protected $opd;
    protected $spreadsheet;
    protected $sheet;
    protected string $type;
    protected string $outputFileName;

    /**
     * @throws Exception
     */
    public function __construct($metadata, $opd)
    {
        $this->metadata = $metadata;
        $this->opd = $opd;
        if (!file_exists($this->templatePath())) {
            throw new Exception('Template Metadata ' . $this->type . ' tidak ditemukan');
        }

        $this->spreadsheet = IOFactory::load($this->templatePath());
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    protected function templatePath(): string
    {
        return '';
    }

    protected function mappedCells(): array
    {
        return [];
    }

    /**
     * @throws Exception
     */
    protected function populateCells()
    {
        if (!$this->sheet) {
            throw new Exception('Tidak dapat mendeteksi sheet yang aktif');
        }

        foreach ($this->mappedCells() as $cell => $value) {
            $this->sheet->setCellValue($cell, $value);
        }
    }

    public function getOutputFilePath(): string
    {
        return $this->outputFileName;
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws Exception
     */
    public function export($outputFileName = null): bool
    {
        $this->populateCells();

        $this->outputFileName = $outputFileName ?: storage_path('app/tmp/metadata-') . Str::slug($this->metadata->nama);
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($this->outputFileName);

        return file_exists($this->outputFileName);
    }
}
