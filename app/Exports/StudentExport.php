<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Excel;

class StudentExport implements FromCollection, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;

    protected $data;
    protected $columnFor = [];
    /**
     * It's required to define the fileName within
     * the export class when making use of Responsable.
     */
    private $fileName = '学生信息.xlsx';

    /**
     * Optional Writer Type
     */
    private $writerType = Excel::XLSX;

    public function __construct($data, $columnFor)
    {
        $this->data = $data;
        $this->columnFor = $columnFor;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return new Collection($this->data);
    }

    public function columnFormats(): array
    {
        return $this->columnFor;
    }
}
