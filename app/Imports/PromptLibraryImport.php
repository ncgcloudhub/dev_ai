<?php

namespace App\Imports;

use App\Models\PromptLibrary;
use App\Models\PromptLibraryCategory;
use App\Models\PromptLibrarySubCategory;
use Illuminate\Support\Str;
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

        // Check if the slug is empty and generate it from prompt_name
        $slug = !empty($row['slug']) ? $row['slug'] : Str::slug($row['prompt_name']);

        // Find the category and subcategory IDs by name
        $category = PromptLibraryCategory::where('category_name', $row['category_name'])->first();
        $subcategory = PromptLibrarySubCategory::where('sub_category_name', $row['sub_category_name'])->first();

        // If no category or subcategory found, you might want to handle this case according to your application logic.
        if (!$category || !$subcategory) {
            // Handle error or return null
            return null;
        }

        // Find the existing record by ID if it exists
        if (!empty($row['id'])) {
            $promptLibrary = PromptLibrary::find($row['id']);
            if ($promptLibrary) {
                // Update the existing record
                $promptLibrary->update([
                    'prompt_name'     => $row['prompt_name'],
                    'slug'            => $slug,
                    'icon'            => $row['icon'],
                    'category_id'     => $category->id,
                    'description'     => $row['description'],
                    'actual_prompt'   => $row['actual_prompt'],
                    'created_at'      => $row['created_at'],
                    'updated_at'      => $row['updated_at'],
                    'sub_category_id' => $subcategory->id,
                ]);
                return $promptLibrary;
            }
        }

        // If no ID is provided or the record does not exist, create a new one
        return new PromptLibrary([
            'prompt_name'     => $row['prompt_name'],
            'slug'            => $slug,
            'icon'            => $row['icon'],
            'category_id'     => $category->id,
            'description'     => $row['description'],
            'actual_prompt'   => $row['actual_prompt'],
            'created_at'      => $row['created_at'],
            'updated_at'      => $row['updated_at'],
            'sub_category_id' => $subcategory->id,
        ]);
    }
}
