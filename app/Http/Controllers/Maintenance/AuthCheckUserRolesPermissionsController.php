<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthCheckUserRolesPermissionsController extends Controller
{
    //

    public function checkUserRolePermission(){
        $userAuth = Auth::user();
        $userAuthID = $userAuth->id;
        $user = User::find($userAuthID);

        $userRoles = $user->getRoleNames();
        $userPermissions = $user->getAllPermissions();

        
        return response()->json(['user_roles' => $userRoles, 'user_permissions' => $userPermissions]);
    }
}
