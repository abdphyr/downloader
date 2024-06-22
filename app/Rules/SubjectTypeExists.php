<?php

namespace App\Rules;

use App\Models\SubjectType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SubjectTypeExists implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!SubjectType::where('id', $value)->exists()) {
            $fail('subject_type not found');
        }
    }
}
