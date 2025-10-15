<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 🔔 Send welcome email here
        $receiverEmail = $user->email;
        $receiverName = $user->name;
        $mailer = 'smtp_default'; // or choose dynamically

        Mail::mailer($mailer)
            ->to($receiverEmail, $receiverName)
            ->send(new WelcomeMail($user));

        // Continue with login or redirect
        auth()->login($user);

        return redirect()->route('dashboard')->with('success', 'Welcome email sent!');
    }
}
