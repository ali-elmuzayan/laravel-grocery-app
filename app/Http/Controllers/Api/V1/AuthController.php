<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(RegisterRequest $request): JsonResponse
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'api']);
        $user->assignRole('user');

        event(new Registered($user));

        return response()->json(['message' => 'User registered successfully. Please check your email for verification.'], 201);
    }

    

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $refreshToken = $this->issueRefreshToken((int) auth('api')->id());

        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
        ]);
    }

    public function refresh(Request $request): JsonResponse
    {
        $request->validate([
            'refresh_token' => ['required', 'string'],
        ]);

        $hashedToken = hash('sha256', $request->string('refresh_token'));

        $stored = RefreshToken::query()
            ->where('token_hash', $hashedToken)
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->first();

        abort_if(! $stored, 401, 'Invalid refresh token.');

        $user = User::findOrFail($stored->user_id);
        $stored->update(['revoked_at' => now()]);

        $accessToken = auth('api')->login($user);
        $newRefreshToken = $this->issueRefreshToken($user->id);

        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $newRefreshToken,
            'token_type' => 'bearer',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        auth('api')->logout();

        RefreshToken::query()->where('user_id', $request->user()->id)->update(['revoked_at' => now()]);

        return response()->json(['message' => 'Logged out']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user()->load('roles.permissions'));
    }

    private function issueRefreshToken(int $userId): string
    {
        $plain = Str::random(64);

        RefreshToken::create([
            'user_id' => $userId,
            'token_hash' => hash('sha256', $plain),
            'expires_at' => now()->addDays(7),
        ]);

        return $plain;
    }
}
