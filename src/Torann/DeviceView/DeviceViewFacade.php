<?php namespace Torann\DeviceView;

use Illuminate\Support\Facades\Facade;

class DeviceViewFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	 protected static function getFacadeAccessor() { return 'view.finder'; }

}
