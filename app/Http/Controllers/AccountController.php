<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Display all accounts
     */
    public function index()
    {
        $accounts = User::all();
        return view('accounts.index', compact('accounts'));
    }

    /**
     * Show create account form
     */
    public function create()
    {
        return view('accounts.create');
    }

    /**
     * Store new account
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:instructor,dean,programhead,admin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => true,
        ]);

        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }

    /**
     * Show edit account form
     */
    public function edit(User $account)
    {
        return view('accounts.edit', compact('account'));
    }

    /**
     * Update account
     */
    public function update(Request $request, User $account)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $account->id,
            'role' => 'required|in:instructor,dean,programhead,admin',
        ]);

        $account->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('accounts.index')->with('success', 'Account updated successfully.');
    }

    /**
     * Delete account
     */
    public function destroy(User $account)
    {
        $account->delete();
        return redirect()->route('accounts.index')->with('success', 'Account deleted successfully.');
    }

    /**
     * Toggle account status (Active / Inactive)
     */
    public function toggle(User $account)
    {
        $account->status = !$account->status;
        $account->save();

        return redirect()->back()->with('success', 'Account status updated.');
    }
}
