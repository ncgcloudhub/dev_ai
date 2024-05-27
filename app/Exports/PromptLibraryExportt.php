<?php

namespace App\Exports;

use App\Models\PromptLibrary;
use Maatwebsite\Excel\Concerns\FromCollection;

class PromptLibraryExportt implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PromptLibrary::all();
    }
}
