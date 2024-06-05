<?php

namespace App\Imports;

use App\Models\PromptLibrary;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PromptLibraryImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new PromptLibrary([
            'prompt_name'    => $row['prompt_name'],
            'slug'           => $row['slug'],
            'icon'           => $row['icon'],
            'category_id'    => $row['category_id'],
            'description'    => $row['description'],
            'actual_prompt'  => $row['actual_prompt'],
            'created_at'     => $row['created_at'],
            'updated_at'     => $row['updated_at'],
            'sub_category_id' => $row['sub_category_id'],
        ]);
    }
}
