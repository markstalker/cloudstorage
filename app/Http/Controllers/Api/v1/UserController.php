<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display current authenticated user.
     *
     * @param Request $request
     * @return UserResource
     */
    public function index(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}
