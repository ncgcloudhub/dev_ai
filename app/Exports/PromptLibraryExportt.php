<?php

namespace App\Exports;

use App\Models\PromptLibrary;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PromptLibraryExportt implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return PromptLibrary::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Get all column names for the PromptLibrary table
        return Schema::getColumnListing((new PromptLibrary)->getTable());
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row (headings row) as bold
            1 => ['font' => ['bold' => true]],
        ];
    }
}
