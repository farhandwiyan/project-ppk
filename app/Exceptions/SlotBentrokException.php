<?php

namespace App\Exceptions;

use Exception;

class SlotBentrokException extends Exception
{
    protected $code = 409;

    public function render($request)
    {
        return back()
            ->withInput()
            ->withErrors(['start_time' => $this->getMessage() ?: 'Slot waktu yang dipilih sudah dipesan atau menunggu persetujuan.']);
    }
}