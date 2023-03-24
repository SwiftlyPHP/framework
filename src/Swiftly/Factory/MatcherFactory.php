<?php

namespace Swiftly\Factory;

use Swiftly\Routing\Matcher\StaticMatcher;
use Swiftly\Routing\Matcher\RegexMatcher;
use Swiftly\Routing\Matcher\SeriesMatcher;

/**
 * Provides the default matcher used for routing in *most* applications
 * 
 * @since 1.0-beta To reduce clutter in services.php
 */
final class MatcherFactory
{
    /**
     * Function to create a series matcher
     * 
     * The order of the array is important for performance. Having static first
     * means URLs without variable components - like the homepage - can be
     * returned without having to compile any regex.
     */
    public static function create(
        StaticMatcher $static,
        RegexMatcher $regex
    ): SeriesMatcher {
        return new SeriesMatcher([$static, $regex]);
    }
}
