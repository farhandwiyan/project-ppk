<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function homeAdmin(UserController $userController)
    {
        return $userController->index();
    }

    public function homePetugas()
    {
        return view('petugas.dashboard');
    }

    public function showAllUser(UserController $userController, Request $request)
    {
        return $userController->getAllUser($request);
    }

    public function showAllFasilitas(FasilitasController $fasilitasController, Request $request) 
    {
        return $fasilitasController->getAllFasilitas($request);
    }
}
