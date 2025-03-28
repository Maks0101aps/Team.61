<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ParentTypeController extends Controller
{
    /**
     * Get the parent type (mother/father) for a given user ID.
     *
     * @param int $userId
     * @return JsonResponse
     */
    public function getParentType($userId): JsonResponse
    {
        // Get the user
        $user = User::find($userId);
        
        if (!$user || $user->role !== User::ROLE_PARENT) {
            return response()->json(['error' => 'User not found or not a parent'], 404);
        }
        
        // Get the parent model associated with this user (by email)
        $parent = ParentModel::where('email', $user->email)->first();
        
        if (!$parent) {
            return response()->json(['error' => 'Parent record not found'], 404);
        }
        
        return response()->json([
            'parentType' => $parent->parent_type,
            'parentTypeName' => $parent->parent_type_name,
        ]);
    }
}
