<?php

/**
 * App wide version and file path definitions.
 *
 * This file can be edited and version controlled as necessary. Update the
 * constants below if you wish to use a non-standard folder structure.
 *
 * @since 1.0.0
 */

namespace Swiftly;

/* Framework version */
const SWIFTLY_VERSION = '1.0.0';
const SWIFTLY_MIN_PHP = '7.4.0';

/* File paths */
const PATH_ROOT = __DIR__;
const PATH_PUBLIC = PATH_ROOT . '/public';
const PATH_CONFIG = PATH_ROOT . '/config';
const PATH_CACHE = PATH_ROOT . '/data/cache';
const PATH_VIEW = PATH_ROOT . '/app/view';
const PATH_SERVICES = PATH_ROOT . '/services';

/* Config files */
const FILE_AUTOLOAD = PATH_ROOT . '/vendor/autoload.php';
const FILE_CONFIG = PATH_CONFIG . '/app.json';
const FILE_ROUTES = PATH_CONFIG . '/routes.json';
