<?php

namespace App\Imports;

use App\Models\PromptLibrary;
use Maatwebsite\Excel\Concerns\ToModel;

class PromptLibraryImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new PromptLibrary([
            'prompt_name'     => $row[1],
            'icon'   => $row[3], 
            'category_id'   => $row[4], 
            'actual_prompt'   => $row[6], 
       ]);
    }
}
