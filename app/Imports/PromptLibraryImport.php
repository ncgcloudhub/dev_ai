<?php

namespace App\Imports;

use App\Models\PromptLibrary;
use App\Models\PromptLibraryCategory;
use App\Models\PromptLibrarySubCategory;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class PromptLibraryImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Check if the slug is empty and generate it from prompt_name
        $slug = !empty($row['slug']) ? $row['slug'] : Str::slug($row['prompt_name']);

        // Find the category by name or create it if it doesn't exist
        $category = PromptLibraryCategory::firstOrCreate(
            ['category_name' => $row['category_name']],
            [
                'category_icon' => !empty($row['category_icon']) ? $row['category_icon'] : 'default_icon', // Provide a default value
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Find the subcategory by name or create it if it doesn't exist
        $subcategory = PromptLibrarySubCategory::firstOrCreate(
            ['sub_category_name' => $row['sub_category_name'], 'category_id' => $category->id],
            ['created_at' => now(), 'updated_at' => now()]
        );

        // Log the creation of new categories/subcategories
        if ($category->wasRecentlyCreated) {
            Log::info('Created new category', ['category_name' => $row['category_name']]);
        }

        if ($subcategory->wasRecentlyCreated) {
            Log::info('Created new subcategory', ['sub_category_name' => $row['sub_category_name']]);
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
                Log::info('Updated existing record', ['row' => $row]);
                return $promptLibrary;
            }
        }

        // If no ID is provided or the record does not exist, create a new one
        $newPromptLibrary = new PromptLibrary([
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
        Log::info('Created new record', ['row' => $row]);
        return $newPromptLibrary;
    }

}
