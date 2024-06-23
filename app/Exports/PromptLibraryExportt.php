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
        // Load PromptLibrary with related category and subcategory names
        $prompts = PromptLibrary::with(['category', 'subcategory'])->get();

        // Map the data to replace category_id and sub_category_id with their names
        return $prompts->map(function ($prompt) {
            return [
                'id' => $prompt->id,
                'prompt_name' => $prompt->prompt_name,
                'slug' => $prompt->slug,
                'icon' => $prompt->icon,
                'category_name' => $prompt->category ? $prompt->category->category_name : null,
                'sub_category_name' => $prompt->subcategory ? $prompt->subcategory->sub_category_name : null,
                'description' => $prompt->description,
                'actual_prompt' => $prompt->actual_prompt,
                'created_at' => $prompt->created_at,
                'updated_at' => $prompt->updated_at,

            ];
        });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Prompt Name',
            'Slug',
            'Icon',
            'Category Name',
            'Sub Category Name',
            'Description',
            'Actual Prompt',
            'Created At',
            'Updated At',

        ];
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
