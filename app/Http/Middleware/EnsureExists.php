<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class EnsureExists
{
	/**
	 * Handle an incoming request.
	 *
	 * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		$key = $request->has('email') ? 'email' : ($request->has('name') ? 'name' : '');

		// return response()->json([
		//     'message_key' => $key,
		//     'message'     => 'user does not exist ',
		// ], 404);

		if ($key) {
			$user = User::where($key, $request->input($key))->get();

			if (!count($user)) {
				return response()->json([
					'message_key' => 'USER_DOESNT_EXIST',
					'message'     => __('validation.user_doesnt_exist'),
				], 404);
			}
		}

		return $next($request);
	}
}
