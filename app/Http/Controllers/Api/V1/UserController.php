<?php

namespace App\Http\Controllers\Api\V1;

use App\Facades\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserIndexRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\V1\UserCollection;
use App\Http\Resources\V1\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function index(UserIndexRequest $request)
    {
        $data = $request->validated();

        $offset = $data['offset'] ?? null;
        $count = $data['count'] ?? 5;

        try {
            if (isset($offset)) {
                $users = User::skip($offset)->take($count)->orderBy('id')->get();
            } else {
                $users = User::orderBy('id')->paginate($count);
            }
        } catch (\Exception) {
            return response()->json(['success' => false, 'message' => 'Page not found'], 404);
        }

        if ($users->count() > 0) {
            return new UserCollection($users);
        } else {
            return response()->json(['success' => false, 'message' => 'Page not found'], 404);
        }
    }

    public function show($id)
    {
        if (!ctype_digit($id) || !is_numeric($id) || (int)$id <= 0) {  // корректный id должен быть не только integer, но и > 0
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'fails' => [
                    'user_id' => ['The user_id must be an integer'],
                ],
            ], 400);
        }

        try {
            $user = User::find($id);
        } catch (\Exception) {
            return response()->json(['success' => false, 'message' => 'Page not found'], 404);
        }

        if ($user) {
            return new UserResource($user);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'The user with the requested identifier does not exist',
                'fails' => [
                    'user_id' => ['User not found'],
                ],
            ], 404);
        }

    }

    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();
        if (User::where('email', $data['email'])->orWhere('phone', $data['phone'])->get()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'User with this phone or email already exist',
            ], 409);
        } else {
            $user = UserService::store($data);
            return response()->json([
                'success' => true,
                'user_id' => $user->id,
                'message' => 'New user successfully registered',
            ], 200);
        }
    }
}
