<?php declare(strict_types=1);
/**
 * Main bootstrap file for SwiftlyPHP applications.
 *
 * @version 1.0.0
 */

use Swiftly\Config\File\JsonFile;
use Swiftly\Http\Request\Request;
use Swiftly\Dependency\Container;
use Swiftly\Core\ServiceProvider;
use Swiftly\Core\Application;

use const Swiftly\FILE_AUTOLOAD;
use const Swiftly\FILE_CONFIG;
use const Swiftly\PATH_SERVICES;

require_once dirname(__DIR__) . '/definitions.php';
require_once FILE_AUTOLOAD;

/**
 * Load user-defined config values.
 */
$config = (new JsonFile(FILE_CONFIG))->load();

/**
 * Collect HTTP request info from server.
 */
$request = Request::fromGlobals();

/**
 * Create container and populate with values from all `/services/*.php` files.
 */
$container = new Container();
$container->register(Request::class, $request);
$provider = new ServiceProvider($container);
$provider->loadDir(PATH_SERVICES);

/**
 * Hand request off to registered middlewares and route controller.
 */
$application = new Application($config, $container);
$application->send($application->process($request));
