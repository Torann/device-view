<?php namespace Torann\DeviceView;

use Illuminate\View\ViewServiceProvider;

class DeviceViewServiceProvider extends ViewServiceProvider {

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registerViewFinder()
    {
        $this->app->bind('view.finder', function($app) {
            return new FileViewFinder($app['files'], $app['config']['view'], null, $app);
		});
	}

}
