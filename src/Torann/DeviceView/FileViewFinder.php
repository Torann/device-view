<?php namespace Torann\DeviceView;

use Mobile_Detect;
use InvalidArgumentException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;

class FileViewFinder extends \Illuminate\View\FileViewFinder
{
    /**
     * View path directory
     *
     * @var string
     */
    protected $viewPath;

    /**
     * Name of device view
     *
     * @var string
     */
    protected $deviceView;

    /**
     * Valid Devices
     *
     * @var array
     */
    protected $devices = [
        'default' => 'default'
    ];

    /**
     * Default layout
     *
     * @var string
     */
    protected $defaultView = 'default';

    /**
     * Session instance
     *
     * @var array
     */
    protected $session;

    /**
     * Request instance
     *
     * @var array
     */
    protected $request;

    /**
     * Create a new file view loader instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  array  $config
     * @param  array  $extensions
     * @param  \Illuminate\Foundation\Application $app
     */
    public function __construct(Filesystem $files, array $config, array $extensions = null, Application $app)
    {
        parent::__construct($files, $config['paths'], $extensions);

        // Set session instance
        $this->session = $app['session'];

        // Set request instance
        $this->request = $app['request'];

        // Set default view
        $this->defaultView = array_get($config, 'default', $this->defaultView);

        // Set valid devices
        $this->devices = array_get($config, 'devices', array());

        // Set default device
        $this->devices['default'] = array_get($config, 'default', 'default');

        // Set location
        $this->viewPath = array_get($config, 'path');
    }

    /**
     * Add a location to the finder.
     *
     * @param  string  $location
     * @return void
     */
    public function addLocation($location)
    {
        array_unshift($this->paths, $location);
    }

    /**
     * Get the fully qualified location of the view.
     *
     * @param  string  $name
     * @return string
     */
    public function find($name)
    {
        // Detect device type
        $this->detectDevice();

        return parent::find($name);
    }

    /**
     * Find the given view in the list of paths.
     *
     * @param  string  $name
     * @param  array   $paths
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function findInPaths($name, $paths)
    {
        try
        {
            return parent::findInPaths($name, $paths);
        }
        catch (InvalidArgumentException $e)
        {
            $name = $this->deviceView ? "{$this->deviceView}.$name" : $name;

            throw new InvalidArgumentException("View [$name] not found.");
        }
    }

    /**
     * Get the device view type.
     *
     * @return string
     */
    public function getDevice()
    {
        if (! $this->deviceView) {
            $this->detectDevice();
        }

        return $this->deviceView;
    }

    /**
     * Set the view to be used over the default view.
     *
     * @param string $view
     * @return void
     */
    public function setDeviceView($view)
    {
        $this->deviceView = $view;

        $this->addLocation("{$this->viewPath}/{$this->deviceView}");
    }

    /**
     * Determine if the device is valid.
     *
     * @param string $device
     * @return bool
     */
    public function validDevice($device)
    {
        return array_key_exists($device, $this->devices);
    }

    /**
     * Detect which view to show based on device.
     *
     * @return void
     */
    public function detectDevice()
    {
        // Already set
        if ($this->deviceView) {
            return;
        }

        // Allow user to override
        if($device = $this->request->get('dv')) {
            if ($this->validDevice($device)) {
                $this->session->set('device-view', $device);
            }
        }

        // Get params
        $device = $this->session->get('device-view');

        if (! $device)
        {
            // Get device
            $detect = new Mobile_Detect;
            $device = $detect->isTablet() ? 'tablet' : ($detect->isMobile() ? 'mobile' : $this->defaultView);

            // Validate device
            $device = $this->validDevice($device) ? $this->devices[$device] : $this->defaultView;
        }

        // Set view
        $this->setDeviceView($device);
    }
}
