<?php

namespace Swiftly\Factory;

use Swiftly\Template\FileFinder;

use const APP_VIEW;

/**
 * Creates the FileFinder used by the templating system
 * 
 * @since 1.0-beta To reduce clutter in services.php
 */
final class TemplateFinderFactory
{
    /**
     * Creates a finder rooted in the default app/view folder
     */
    public static function create(): FileFinder
    {
        return new FileFinder(APP_VIEW . '/');
    } 
}
