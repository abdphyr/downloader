<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'O\'zbekcha', 'code' => 'uz', 'is_active' => 1],
            ['name' => 'Русский', 'code' => 'ru', 'is_active' => 1],
            ['name' => 'English', 'code' => 'en', 'is_active' => 1],
        ];

        Language::truncate();

        Language::insert($data);
    }
}
