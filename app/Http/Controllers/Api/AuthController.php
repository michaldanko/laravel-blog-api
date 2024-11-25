<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Generate token.
     */
    public function generateToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Incorrect credentials.'],
            ]);
        }

        $token = $user->createToken('app-token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    /**
     * User logout and token revocation
     */
    public function logout(Request $request)
    {
		// Revoke all tokens...
		$request->user()->tokens()->delete();

		// Revoke the current token
		$request->user()->currentAccessToken()->delete();

		return response()->json(['message' => 'You have been successfully logged out.'], 200);
    }
}
