<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class MetadataDatasetExport implements FromArray, ShouldAutoSize, WithTitle, WithHeadings, WithMapping
{
    private $data;
    private $title;
    private array $headings;

    public function __construct($data, $title = 'Metadata Dataset', array $headings = [])
    {
        $this->data = $data;
        $this->title = $title;
        $this->headings = $headings;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function map($row): array
    {
        $mapped = [];
        foreach ($row as $key => $value) {
            $mapped[] = $row[$key];
        }
        return $mapped;
    }
}
