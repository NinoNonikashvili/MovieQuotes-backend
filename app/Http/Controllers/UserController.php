<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
	public function update(UpdateUserRequest $request)
	{
		$user = User::find(Auth::user()->id);
		if ($name = $request->input('name')) {
			$user->name = $name;
		}
		if ($password = $request->input('password')) {
			$user->password = bcrypt($password);
		}

		if ($request->file('image')) {
			$oldAvatar = $user->getFirstMedia();

			if ($oldAvatar) {
				$oldAvatar->delete();
			}
			$user->addMediaFromRequest('image')
			->toMediaCollection();
		}
		$user->save();
		return response()->noContent();
	}

	public function show(): JsonResponse
	{
		return response()->json([
			'user_data'     => new UserResource(auth()->user()),
		]);
	}
}
