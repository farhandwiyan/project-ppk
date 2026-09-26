<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ValidTimeSlot implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $time = Carbon::createFromFormat('H:i', $value);
        $open = Carbon::createFromFormat('H:i', '07:00');
        $close = Carbon::createFromFormat('H:i', '20:00');

        if ($time->lt($open) || $time->gt($close)) {
            $fail('Waktu harus berada dalam jam operasional 07:00 - 20:00');
            return;
        }

        if ($time->minute % 30 !== 0) {
            $fail('Waktu harus kelipatan 30 menit');
        }
    }
}
