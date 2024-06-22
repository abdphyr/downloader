<?php

namespace App\Rules;

use App\Models\Language;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LanguageExists implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!Language::where('id', $value)->exists()) {
            $fail('language not found');
        }
    }
}
