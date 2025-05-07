<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use function Laravel\Prompts\select;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('user_id', '!=', Auth::id())
            ->select('user_id', 'name', 'lastname', 'email')->get();

        return response()->json($users);
    }
}