<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;

class SetLangage
{
	/**
	 * Handle an incoming request.
	 *
	 * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		$lang = $request->input('lang');
		if ($lang) {
			App::setLocale($lang);
		}
		// return response()->json([
		// 	'data'=> App::getLocale(),
		// ]);

		return $next($request);
	}
}
