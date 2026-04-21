<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Evaluation;
use App\Models\Grade;

class AdminController extends Controller
{
    public function dashboard()
    {
        return response()->json([
            'users' => User::count(),
            'evaluations' => Evaluation::count(),
            'grades' => Grade::count(),
        ]);
    }

    public function users()
    {
        return User::with('roles')->get();
    }
}
