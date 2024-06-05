<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureExists;
use App\Http\Middleware\EnsureGuestIsVerified;
use App\Http\Middleware\SetLangage;

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		web: __DIR__ . '/../routes/web.php',
		api: __DIR__ . '/../routes/api.php',
		commands: __DIR__ . '/../routes/console.php',
		health: '/up',
	)
	->withBroadcasting(
		__DIR__ . '/../routes/channels.php',
		['prefix' => 'api', 'middleware' => ['auth:sanctum']],
	)
	->withMiddleware(function (Middleware $middleware) {
		$middleware->statefulApi();
		$middleware->alias([
			'ensure-exists'         => EnsureExists::class,
			'ensure-guest-verified' => EnsureGuestIsVerified::class,
			'lang'                  => SetLangage::class,
		]);
	})
	->withExceptions(function (Exceptions $exceptions) {
	})->create();
