<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Update; //File Model
use App\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\QueryBuilder\AllowedFilter;

class CseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function cse()
    {
        $users = DB::table('users')
        ->select('name', 'email', 'type', 'id')
        ->where('users.type', 'CSE')
        ->get();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Show CSE',
            'data' => [
                'users' => $users,
            ],
        ], 200)
        ->header('Access-Control-Allow-Origin', '*');
    }
}
