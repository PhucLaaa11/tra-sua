<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    // View user list
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Do not allow deleting your own account
        if ($user->id == Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully!');
    }

    // Assign role (Promote/Demote)
    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Validate input data
        $request->validate([
            'role' => 'required|in:admin,staff,customer'
        ]);

        $user->role = $request->role;
        $user->save();

        return redirect()->back()->with('success', 'Role updated successfully!');
    }
}
