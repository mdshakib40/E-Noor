<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Mail\PasswordResetCodeMail;
use App\Models\PasswordResetCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ],
            [
                'name.required' => 'Please enter your name.',
                'email.required' => 'Please enter your email.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email is already registered.',
                'password.required' => 'Please enter your password.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.confirmed' => 'Passwords do not match.',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('name', 'email'))
                ->with('open_auth_modal', 'register');
        }

        $validated = $validator->validated();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => UserRole::CUSTOMER,
        ]);

        return redirect('/')
            ->with('success', 'Account created successfully!');
    }


    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
            ],
            [
                'email.required' => 'Please enter your email.',
                'email.email' => 'Please enter a valid email address.',
                'password.required' => 'Please enter your password.',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'))
                ->with('open_auth_modal', 'login');
        }

        $credentials = $validator->validated();

        /*
        |--------------------------------------------------------------------------
        | Find User
        |--------------------------------------------------------------------------
        */

        $user = User::where('email', $credentials['email'])->first();

        if (! $user) {
            return back()
                ->withErrors([
                    'email' => 'Email address not found. Please register first.',
                ])
                ->withInput($request->only('email'))
                ->with('open_auth_modal', 'login');
        }

        /*
        |--------------------------------------------------------------------------
        | Attempt Login
        |--------------------------------------------------------------------------
        */

        if (! Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {
            return back()
                ->withErrors([
                    'password' => 'Incorrect password. Please try again.',
                ])
                ->withInput($request->only('email'))
                ->with('open_auth_modal', 'login');
        }

        /*
        |--------------------------------------------------------------------------
        | Regenerate Session
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Get Authenticated User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Role Based Redirect
        |--------------------------------------------------------------------------
        */

        if ($user->role === UserRole::SUPER_ADMIN) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === UserRole::RESTAURANT_OWNER) {
            return redirect()->route('resturent.dashboard');
        }

        if ($user->role === UserRole::RESTAURANT_STAFF) {
            return redirect()->route('resturent.staff.dashboard');
        }

        if ($user->role === UserRole::RIDER) {
            return redirect()->route('rider.dashboard');
        }

        if ($user->role === UserRole::CUSTOMER) {
            return redirect()->route('customer.dashboard');
        }

        Auth::logout();

        abort(403, 'Unauthorized role.');
    }


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }


    /*
    |--------------------------------------------------------------------------
    | Forgot Password
    |--------------------------------------------------------------------------
    */

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => ['required', 'email'],
            ],
            [
                'email.required' => 'Please enter your email.',
                'email.email' => 'Please enter a valid email address.',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'))
                ->with('open_auth_modal', 'forgot');
        }

        $user = User::where(
            'email',
            $request->email
        )->first();

        if (! $user) {
            return back()
                ->withErrors([
                    'forgot_email' => 'This email is not registered.',
                ])
                ->withInput($request->only('email'))
                ->with('open_auth_modal', 'forgot');
        }

        PasswordResetCode::where(
            'email',
            $user->email
        )->delete();

        $code = (string) random_int(100000, 999999);

        PasswordResetCode::create([
            'email' => $user->email,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($user->email)->send(
                new PasswordResetCodeMail($code)
            );

            return back()
                ->withInput($request->only('email'))
                ->with('open_auth_modal', 'verification');

        } catch (\Throwable $e) {
            dd([
                'mail_error' => $e->getMessage(),
                'mail_class' => get_class($e),
            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Verify Password Code
    |--------------------------------------------------------------------------
    */

    public function verifyPasswordCode(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => ['required', 'email'],
                'code' => ['required', 'digits:6'],
            ],
            [
                'email.required' => 'Please enter your email.',
                'email.email' => 'Please enter a valid email address.',
                'code.required' => 'Please enter the verification code.',
                'code.digits' => 'Verification code must be 6 digits.',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_auth_modal', 'verification');
        }

        $resetCode = PasswordResetCode::where(
            'email',
            $request->email
        )
            ->where(
                'code',
                $request->code
            )
            ->first();

        if (! $resetCode) {
            return back()
                ->withErrors([
                    'verification_code' => 'Invalid verification code.',
                ])
                ->withInput()
                ->with('open_auth_modal', 'verification');
        }

        if ($resetCode->expires_at->isPast()) {
            return back()
                ->withErrors([
                    'verification_code' => 'This verification code has expired.',
                ])
                ->withInput()
                ->with('open_auth_modal', 'verification');
        }

        /*
        |--------------------------------------------------------------------------
        | OTP Verified
        |--------------------------------------------------------------------------
        */

        $request->session()->put(
            'password_reset_verified_email',
            $request->email
        );

        /*
        |--------------------------------------------------------------------------
        | Delete Used OTP
        |--------------------------------------------------------------------------
        */

        $resetCode->delete();

        return back()
            ->with('open_auth_modal', 'reset-password');
    }


    /*
    |--------------------------------------------------------------------------
    | Update Password
    |--------------------------------------------------------------------------
    */

    public function updatePassword(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Make Sure OTP Was Verified
        |--------------------------------------------------------------------------
        */

        $verifiedEmail = $request->session()->get(
            'password_reset_verified_email'
        );

        if (! $verifiedEmail) {
            return redirect('/')
                ->with('open_auth_modal', 'forgot');
        }

        /*
        |--------------------------------------------------------------------------
        | Validate New Password
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make(
            $request->all(),
            [
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                ],
            ],
            [
                'password.required' => 'Please enter your new password.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.confirmed' => 'Passwords do not match.',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('open_auth_modal', 'reset-password');
        }

        /*
        |--------------------------------------------------------------------------
        | Find User
        |--------------------------------------------------------------------------
        */

        $user = User::where(
            'email',
            $verifiedEmail
        )->first();

        if (! $user) {
            $request->session()->forget(
                'password_reset_verified_email'
            );

            return redirect('/');
        }

        /*
        |--------------------------------------------------------------------------
        | Update Password
        |--------------------------------------------------------------------------
        */

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Clear Password Reset Session
        |--------------------------------------------------------------------------
        */

        $request->session()->forget(
            'password_reset_verified_email'
        );

        /*
        |--------------------------------------------------------------------------
        | Password Reset Success
        |--------------------------------------------------------------------------
        */

        return redirect('/')
            ->with('open_auth_modal', 'password-success');
    }
}