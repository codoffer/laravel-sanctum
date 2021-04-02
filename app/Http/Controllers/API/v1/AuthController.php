<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Http\Requests\API\v1\{Login, Register};

use App\Http\Resources\User as UserResource;

use Hash;
use Illuminate\Auth\Events\Validated;

class AuthController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:sanctum', ['except' => ['register', 'login']]);
    }

    /**
     * Register user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Register $request)
    {
        $validated_data = $request->validated();
        $validated_data['password'] = Hash::make($validated_data['password']);

        $user = User::create($validated_data);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->respondWithToken($token);
    }


    /**
     * Login process.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Login $request)
    {
        $validated_data = $request->validated();

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = User::where('email', $request->email)->firstOrFail();

            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->respondWithToken($token);
        } else {
            return response()->json(['message' => 'Please check your email or password.'], 401);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = new UserResource(auth()->user());
        return response()->json($user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer'
        ], 200);
    }
}
