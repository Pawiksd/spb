<?php
// app/Http/Controllers/UserController.php

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
}

?>
