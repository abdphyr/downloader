<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectTranslationFactory extends Factory
{
    public function definition()
    {
        return [
            'object_id' => $this->faker->randomElement(Subject::all()->pluck('id')),
            'name' => $this->faker->word(),
            'language_code' => $this->faker->randomElement(Language::all()->pluck('code')),
        ];
    }
}
