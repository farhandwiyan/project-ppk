<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class MinimumH7 implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $tanggalReservasi = Carbon::parse($value)->startOfDay();
        $batasMinimal = Carbon::today()->addDays(7);

        if ($tanggalReservasi->lt($batasMinimal)) {
            $fail('Reservasi hanya bisa diajukan minimal H-7');
        }
    }
}
