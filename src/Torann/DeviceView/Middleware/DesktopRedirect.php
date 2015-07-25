<?php namespace Torann\DeviceView\Middleware;

use Closure;
use Illuminate\Contracts\View\Factory;

class DesktopRedirect
{
    /**
     * The view factory.
     *
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;

    /**
     * @var bool Whether or not a host part has been normalized
     */
    protected $isNormalized = false;

    /**
     * Create a new filter instance.
     *
     * @param  \Illuminate\Contracts\View\Factory  $view
     */
    public function __construct(Factory $view)
    {
        $this->view = $view;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $routeString
     * @return mixed
     */
    public function handle($request, Closure $next, $routeString)
    {
        if (! $request->ajax()) {
            if ($this->view->getFinder()->getDevice() === 'default') {
                return redirect(route($routeString));
            }
        }

        return $next($request);
    }

}
