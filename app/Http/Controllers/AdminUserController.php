<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Update a user's role.
     */
    public function updateRole(Request $request, User $user)
    {
        // Super Admin নিজে নিজের role পরিবর্তন করতে পারবে না
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $validated = $request->validate([
            'role' => [
                'required',
                Rule::enum(UserRole::class),
            ],
        ]);

        $user->role = $validated['role'];
        $user->save();

        return back()->with(
            'success',
            "{$user->name}'s role has been updated successfully."
        );
    }
}