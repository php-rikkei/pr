<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    /**
     * Search autocomplete.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Json
     */
    public function search(Request $request)
    {
        $users = User::select('id','name')->where('name', 'like', '%'.$request->term.'%')->get();

        return $users->toJson();
    }
}
