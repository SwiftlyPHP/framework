<?php
/**
 * Main bootstrap file for SwiftlyPHP applications.
 *
 * The constants in `definitions.php` can be edited if you wish to use a
 * different folder structure than the default.
 *
 * @version 1.0.0
 */

use Swiftly\Config\File\JsonFile;
use Swiftly\Core\Application;
use Swiftly\Core\ServiceProvider;
use Swiftly\Dependency\Container;
use Swiftly\Http\Request\Request;

use const Swiftly\FILE_AUTOLOAD;
use const Swiftly\FILE_CONFIG;
use const Swiftly\PATH_SERVICES;

// Edit this path if using a different folder structure
require_once dirname(__DIR__) . '/definitions.php';

// Load composer autoloader
require_once FILE_AUTOLOAD;

// Load values from config.json 
$config = new JsonFile(FILE_CONFIG);
$config = $config->load();

// Create request from $_SERVER, $_GET, $_POST and $_COOKIE globals
$request = Request::fromGlobals();

// Create and populate the service container
$container = new Container();
$container->register(Request::class, $request);
$provider = new ServiceProvider($container);
$provider->loadDir(PATH_SERVICES);

// Process request and respond to client
$application = new Application($config, $container);
$response = $application->process($request);
$application->send($response);
