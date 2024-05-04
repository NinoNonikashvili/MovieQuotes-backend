<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class EnsureGuestIsVerified
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
		// 	'message_key' => $key,
		// 	'message'     => 'User is not verified ',
		// ], 404);

		if ($key) {
			$user = User::where($key, $request->input($key))->get();

			if (count($user) && !($user[0]->email_verified_at)) {
				return response()->json([
					'message_key' => 'USER_ISNT_VERIFIED',
					'message'     => 'User is not verified ',
				], 404);
			}
		}
		return $next($request);
	}
}
