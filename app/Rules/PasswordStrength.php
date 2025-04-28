<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PasswordStrength implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen($value) < 8) {
            $fail(':attribute harus memiliki minimal 8 karakter.');
        }

        // Pastikan ada setidaknya satu huruf besar
        if (!preg_match('/[A-Z]/', $value)) {
            $fail(':attribute harus memiliki setidaknya satu huruf besar.');
        }

        // Pastikan ada setidaknya satu huruf kecil
        if (!preg_match('/[a-z]/', $value)) {
            $fail(':attribute harus memiliki setidaknya satu huruf kecil.');
        }

        // Pastikan ada setidaknya satu karakter khusus (misalnya: @, #, $, dll.)
        if (!preg_match('/[@$!#%*?&]/', $value)) {
            $fail(':attribute harus memiliki setidaknya satu karakter khusus.');
        }
    }
}
