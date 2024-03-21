<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;


class AuthenticatedSessionController extends Controller
{
    use HttpResponses;
    /**
     * Handle an incoming authentication request.
     */
    
    // public function store(LoginRequest $request): Response
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     return response()->noContent();
    // }

    public function store(LoginRequest $request)
    {
        $request->validated();

        if (Auth::attempt($request->only('email', 'password'))) {
            

            // Check the user's role and redirect accordingly
            $user = Auth::user();

            if ($user->role == 'teacher') { // Assuming 1 is the role for teachers
                //return response()->json(['message' => 'Teacher logged in']);  
                return $this->success([
                    'user'  => $user,
                    'token' => $user->createToken('Api Token Of' . $user->name, ['role' => $user->role])->plainTextToken,
                    'role' => 'teacher'
                ]);
            } else if($user->role == 'student') {
                return $this->success([
                    'user'  => $user,
                    'token' => $user->createToken('Api Token Of' . $user->name, ['role' => $user->role])->plainTextToken,
                    'role' => 'student'
                ]);
                //return response()->json(['message' => 'Student logged in']);
            }
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->success([
            'message' => 'You have successfully been logged out and your token has been deleted',
        ]);
    }
}
