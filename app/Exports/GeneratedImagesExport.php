<?php

namespace App\Exports;

use App\Models\DalleImageGenerate;
use App\Models\GeneratedImage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GeneratedImagesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return DalleImageGenerate::with('user')->get()->map(function ($image) {
            return [
                'ID' => $image->id,
                'Prompt' => $image->prompt,
                'User' => $image->user ? $image->user->name : 'N/A',
                'Image URL' => asset($image->image),
                'Status' => $image->status,
                'Created At' => $image->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID', 'Prompt', 'User', 'Image URL', 'Status', 'Created At'
        ];
    }
}
