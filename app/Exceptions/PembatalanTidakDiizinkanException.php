<?php

namespace App\Exceptions;

use Exception;

class PembatalanTidakDiizinkanException extends Exception
{   
    public function render($request)
    {
        return back()->with('error', $this->getMessage());
    }
}