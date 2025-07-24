<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Registers a new user.
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->createUser($request);
        $token = $this->createToken($user);

        return $this->successResponse([
            'user' => new UserResource($user),
            'token' => $token
        ], 'Registration successful', 201);
    }

    /**
     * Creates a new user.
     *
     * @param RegisterRequest $request
     * @return \App\Models\User
     */
    private function createUser(RegisterRequest $request)
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'is_admin' => false,
        ]);
    }

    /**
     * Creates a new token for a user.
     *
     * @param \App\Models\User $user
     * @return string
     */
    private function createToken(User $user)
    {
        return $user->createToken('auth_token')->plainTextToken;
    }

    /**
     * Login a user and issue a token.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->errorResponse('Invalid credentials', 401);
        }

        $user = Auth::user();
        $token = $this->createToken($user);

        return $this->successResponse([
            'user' => new UserResource($user),
            'token' => $token
        ], 'Login successful');
    }

    /**
     * Revoke the authenticated user's current access token, effectively logging them out.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logged out successfully');
    }

    /**
     * Get the authenticated user's information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        return $this->successResponse($request->user());
    }
}
