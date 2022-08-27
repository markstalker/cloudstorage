<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Hash;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Process a user login.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::firstWhere('email', $request->email);

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['Неверный пароль!'],
            ]);
        }

        $params = $request->remember ? ['remember'] : [];

        return response()->json([
            'data' => [
                'token' => $user->createToken('browser', $params)->plainTextToken,
            ],
        ]);
    }

    /**
     * Register new user.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $status = 201;
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $msg = 'Пользователь успешно зарегистрирован';
        } catch (QueryException $ex) {
            $status = 500;
            $msg = 'Произошла ошибка';
        }

        return response()->json([
            'data' => [
                'msg' => $msg,
            ],
        ], $status);
    }

    /**
     * Process a user logout.
     *
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request): Response
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->user()->currentAccessToken()->delete();
        return response()->noContent();
    }
}
