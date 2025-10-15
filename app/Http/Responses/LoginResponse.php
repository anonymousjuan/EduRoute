<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response after successful login.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toResponse($request)
    {
        $user = $request->user();

        // Redirect based on role
        if ($user->hasRole('super_admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('dean')) {
            return redirect()->route('dean.dashboard');
        }

        if ($user->hasRole('program_head')) {
            return redirect()->route('program_head.dashboard');
        }

        if ($user->hasRole('instructor')) {
            return redirect()->route('instructor.dashboard');
        }

        // Default redirect (student or others)
        return redirect()->route('dashboard');
    }
}
