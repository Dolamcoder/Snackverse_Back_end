<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $data = Role::all();
        if ($data) {
            return response()->json([
                'success' => true,
                'roles' => $data,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No roles found',
            ], 404);
        }
    }
}
