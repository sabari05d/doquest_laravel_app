<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    # --------------------- ONBOARD 
    public function onboardIndex()
    {
        return view('onboard');
    }
    # --------------------- ONBOARD 


    # --------------------- REGISTER 
    public function registerIndex()
    {
        return view('auth.register');
    }
    public function register(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')],
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
                'mobile' => ['nullable', 'digits_between:10,15', Rule::unique('users', 'mobile')],
                'password' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'mobile' => $validated['mobile'] ?? null,
                'password' => Hash::make($validated['password']),
            ]);

            return response()->json([
                'message' => 'Account created successfully!',
                'redirect' => route('login')
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
    # --------------------- REGISTER 


    # --------------------- LOGIN 

    public function loginIndex()
    {
        return view('auth.login');
    }

    public function checkUserOld(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $loginField = $request->username;
        $password = $request->password;
        $remember = $request->has('remember_me');

        $fieldType = filter_var($loginField, FILTER_VALIDATE_EMAIL) ? 'email'
            : (is_numeric($loginField) ? 'mobile' : 'username');

        if (Auth::attempt([$fieldType => $loginField, 'password' => $password], $remember)) {
            $request->session()->regenerate();

            // Create a unique re-login token for next time
            $user = Auth::user();
            $token = Str::random(60);
            $user->relogin_token = $token;
            $user->save();

            // Save token in a cookie (7 days)
            cookie()->queue('doquest_relogin', $token, 60 * 24 * 7);

            return redirect()->intended('dashboard')->with('success', 'Login successful!');
        }

        return back()->with('error', 'Invalid credentials.');
    }
    public function checkUser(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $loginField = $request->username;
        $password = $request->password;
        $remember = $request->has('remember_me');

        $fieldType = filter_var($loginField, FILTER_VALIDATE_EMAIL) ? 'email'
            : (is_numeric($loginField) ? 'mobile' : 'username');

        if (Auth::attempt([$fieldType => $loginField, 'password' => $password], $remember)) {
            $request->session()->regenerate();

            // Create relogin token
            $user = Auth::user();
            $token = Str::random(60);
            $user->relogin_token = $token;
            $user->save();
            cookie()->queue('doquest_relogin', $token, 60 * 24 * 7);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => 'Login successful!',
                    'redirect' => route('dashboard')
                ]);
            }

            return redirect()->intended('dashboard')->with('success', 'Login successful!');
        }

        if ($request->expectsJson()) {
            return response()->json(['error' => 'Invalid credentials.'], 422);
        }

        return back()->with('error', 'Invalid credentials.');
    }

    # --------------------- LOGIN 
    # --------------------- LOGOUT 
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }

    # --------------------- LOGOUT



    # --------------------- VALIDATE 
    public function checkUserIsUnique(Request $request)
    {
        $field = $request->field; // username / email / mobile
        $value = $request->value;

        $exists = User::where($field, $value)->exists();

        return response()->json(['exists' => $exists]);
    }
    # --------------------- VALIDATE 


}
