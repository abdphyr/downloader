<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\SubjectType;
use App\Models\SubjectTypeTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = Language::all();
        foreach ($this->data() as $item) {
            $type = SubjectType::create();
            foreach ($languages as $lang) {
                SubjectTypeTranslation::create([
                    'name' => $item[$lang->code],
                    'language_code' => $lang->code,
                    'object_id' => $type->id
                ]);
            }
        }
    }


    public function data()
    {
        $data = [
            [
                'uz' => 'Darslik',
                'ru' => 'Учебник',
                'en' => 'Textbook',
            ],
            [
                'uz' => 'Referat',
                'ru' => 'Реферат',
                'en' => 'Referat',
            ],
            [
                'uz' => 'Lug\'at',
                'ru' => 'Словарь',
                'en' => 'Dictionary',
            ],
            [
                'uz' => 'Entsiklopediya',
                'ru' => 'Энциклопедия',
                'en' => 'Encyclopedia',
            ],
            [
                'uz' => 'Ma\'lumotnoma',
                'ru' => 'Справочник',
                'en' => 'Reference',
            ],
            [
                'uz' => 'Kurs ishi',
                'ru' => 'Курсовая работа',
                'en' => 'Course work',
            ],
            [
                'uz' => 'Adabiy izohlar',
                'ru' => 'Литературные обзоры',
                'en' => 'Literary comments',
            ],
            [
                'uz' => 'Diplom ishi',
                'ru' => 'Дипломная работа',
                'en' => 'Diploma work',
            ]
        ];
        return $data;
    }
}
