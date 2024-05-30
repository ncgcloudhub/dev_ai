<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AllUsersExport implements FromCollection, WithHeadings, WithStyles
{
    protected $columns;

    public function __construct()
    {
        // Get all columns
        $allColumns = Schema::getColumnListing('users');

        // Exclude specific columns
        $this->columns = array_diff($allColumns, ['password', 'photo', 'remember_token']);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::select($this->columns)->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return $this->columns;
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
