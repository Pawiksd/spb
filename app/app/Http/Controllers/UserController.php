<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function assignRole($userId, $role)
    {
        $user = User::find($userId);
        $user->assignRole($role);

        return redirect()->back()->with('success', 'Role assigned successfully!');
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }
}
