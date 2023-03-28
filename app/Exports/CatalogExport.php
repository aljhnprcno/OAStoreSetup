<?php

namespace App\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CatalogExport implements FromView, ShouldAutoSize, ShouldQueue
{
    use Exportable;

    protected $data;
    protected $date_from;
    protected $date_to;

    public function __construct($data, $date_from, $date_to)
    {
        $this->data = $data;
        $this->date_from = $date_from;
        $this->date_to = $date_to;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function view(): View
    {
        return view('exports.library.catalog_reports', ['data' => $this->data, 'date_from' => $this->date_from, 'date_to' => $this->date_to]);
    }
}
