<?php

namespace Database\Seeders;

use App\Models\ButtonStyle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ButtonStyleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buttonStyles = [
            ['button_type' => 'save', 'class_name' => 'btn gradient-btn-1', 'is_selected' => false],
            ['button_type' => 'save', 'class_name' => 'btn gradient-btn-2', 'is_selected' => true], // Default
            ['button_type' => 'save', 'class_name' => 'btn gradient-btn-3'],

            ['button_type' => 'add', 'class_name' => 'btn btn-outline-primary', 'is_selected' => false],
            ['button_type' => 'add', 'class_name' => 'btn btn-info shadow', 'is_selected' => true], // Default
            ['button_type' => 'add', 'class_name' => 'btn btn-dark border'],

            ['button_type' => 'edit', 'class_name' => 'btn btn-warning rounded', 'is_selected' => false],
            ['button_type' => 'edit', 'class_name' => 'btn btn-secondary shadow', 'is_selected' => true], // Default
            ['button_type' => 'edit', 'class_name' => 'btn btn-danger border'],
        ];

        foreach ($buttonStyles as $style) {
            ButtonStyle::create($style);
        }
    }
}
