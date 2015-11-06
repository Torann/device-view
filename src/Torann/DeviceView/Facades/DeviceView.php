<?php namespace Torann\DeviceView\Facades;

use Illuminate\Support\Facades\Facade;

class DeviceView extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'view.finder';
    }
}
