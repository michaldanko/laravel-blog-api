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
     *
     * @OA\Post(
     *     path="/api/auth/token",
     *     tags={"Authentication"},
     *     summary="Generate token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="test@example.com"),
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token generated",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="your_generated_token")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid credentials")
     * )
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
     *
     * @OA\Post(
     *     path="/api/auth/logout",
     *     tags={"Authentication"},
     *     summary="Logout user and revoke token",
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You have been successfully logged out.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
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
