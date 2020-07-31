<?php namespace Clockwork\Support\Lumen;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Laravel\Lumen\Application;

class ClockworkMiddleware
{
	/**
	 * The Laravel Application
	 */
	protected $app;

	/**
	 * Create a new middleware instance.
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	/**
	 * Handle an incoming request.
	 */
	public function handle($request, \Closure $next)
	{
		$this->app['clockwork']->event('Controller')->begin();

		try {
			$response = $next($request);
		} catch (\Exception $e) {
			$this->app[ExceptionHandler::class]->report($e);
			$response = $this->app[ExceptionHandler::class]->render($request, $e);
		}

		return $this->app['clockwork.support']->process($request, $response);
	}
}