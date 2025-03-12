<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $role = $request->input('role');

        $users = User::query()
            ->where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                    ->orWhere('last_name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->when($role, function ($q) use ($role) {
                $q->whereHas('roles', function ($q) use ($role) {
                    $q->where('name', $role);
                });
            })
            ->select('id', 'first_name', 'last_name', 'email')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'full_name' => $user->first_name . ' ' . $user->last_name,
                    'email' => $user->email,
                ];
            });

        return response()->json($users);
    }
} 