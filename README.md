# Laravel Device View

[![Total Downloads](https://poser.pugx.org/torann/geoip/downloads.png)](https://packagist.org/packages/torann/device-view)
[![Patreon donate button](https://img.shields.io/badge/patreon-donate-yellow.svg)](https://www.patreon.com/torann)
[![Donate weekly to this project using Gratipay](https://img.shields.io/badge/gratipay-donate-yellow.svg)](https://gratipay.com/~torann)
[![Donate to this project using Flattr](https://img.shields.io/badge/flattr-donate-yellow.svg)](https://flattr.com/profile/torann)
[![Donate to this project using Paypal](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4CJA2A97NPYVU)

- [Device View on Packagist](https://packagist.org/packages/torann/device-view)
- [Device View on GitHub](https://github.com/Torann/device-view)

Dynamically change Laravel views based on the visitor's device.

## Installation

### Composer

From the command line run:

```
composer require torann/device-view
```

### Setup

This package extends Laravel's built in `ViewServiceProvider`, so that provider must be replaced in `app/app.php`.
Replace the instance of `'Illuminate\View\ViewServiceProvider',` with `'Torann\DeviceView\DeviceViewServiceProvider',`.


### Publish the configurations

Run this on the command line from the root of your project:

```
$ php artisan vendor:publish --provider="Torann\DeviceView\DeviceViewServiceProvider"
```

A configuration file will be publish to `config/device-view.php`.


## Configuration

The default settings are for the device views to be in the `views` directory in `resources/` with the default theme called `default`.

```
resources/
    views/
        default/
        mobile/
        tablet/
```

## Usage

A standard call to `View::make('index')` will look for an index view in `resources/views/default/`. However, if a theme is specified with
`$app['view.finder']->setDeviceView('mobile');` prior to calling `View::make()` then the view will first be looked for in `resources/views/mobile/views`.
If the view is not found for the current theme the default theme will then be searched.

### Facade

The `DeviceView` facade can also be used if preferred `DeviceView::setDeviceView('mobile')` by adding an entry for `Torann\DeviceView\Facades\DeviceView` to `config/app.php`.

### Helper Methods

**DeviceView::getPlatform()**

Return the user's operating system.

## Example

Given a directory structure of:

```
resources/
    views/
        default/
            layout.blade.php
            admin.blade.php
        mobile/
            layout.blade.php
```

```
View::make('layout'); // Loads resources/views/default/layout.blade.php

$app['view.finder']->setDeviceView('default');

View::make('layout'); // Loads resources/views/mobile/layout.blade.php
View::make('admin'); // Loads resources/views/default/admin.blade.php
```