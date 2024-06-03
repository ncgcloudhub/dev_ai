<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AllUserExport1 implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Select only the columns you want to export
        return User::select('name', 'email', 'created_at')->get();
    }

    /**
     * Define the headings for the columns.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Created At'
        ];
    }

    /**
     * Apply styles to the headings.
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1    => ['font' => ['bold' => true]],
        ];
    }
}
