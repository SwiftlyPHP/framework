<?php
/**
 * Main bootstrap file for SwiftlyPHP applications.
 *
 * @author Conor Varley
 * @version 1.0.0
 */

use Swiftly\Config\File\JsonFile;
use Swiftly\Http\Request\Request;
use Swiftly\Dependency\Container;
use Swiftly\Core\ServiceProvider;
use Swiftly\Core\Application;

require_once "##PATH_AUTOLOAD##";

// Load values from config.json 
$config = (new JsonFile("##PATH_CONFIG##"))->load();

// Collect user request information
$request = Request::fromGlobals();

// Create and populate the service container
$container = new Container();
$container->register(Request::class, $request);
$provider = new ServiceProvider($container);
$provider->loadDir("##PATH_SERVICES##");

/**
 * Process request and send HTTP response to client
 */
$application = new Application($config, $container);
$response = $application->process($request);
$application->send($response);
