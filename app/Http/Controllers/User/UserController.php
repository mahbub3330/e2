<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return 'hi';
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    /**
     * @param User $user
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(User $user, UserRequest $request): JsonResponse
    {
        try {
            $user->fill($request->all());
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json($user);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }

    }

    /**
     * @param User $user
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function update(User $user, UserRequest $request): JsonResponse
    {
        try {
            $user->fill($request->except('password'))->update();
            return response()->json($user);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    /**
     * @param User $user
     * @return JsonResponse
     */

    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();
            return response()->json(['message' => 'Deleted Successfully']);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    /**
     * @param User $user
     * @param Request $request
     * @return JsonResponse
     */

    public function changePassword(User $user, Request $request): JsonResponse
    {
        try {
            $user->password = Hash::make($request->password);
            $user->update();
            return response()->json(['message' => 'Deleted Successfully']);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }
}
