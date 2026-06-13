<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $user = User::create($data);

        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'api']);
        $user->assignRole('user');

        $accessToken = auth('api')->login($user);
        $refreshToken = $this->issueRefreshToken($user->id);

        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
        ], 201);
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
