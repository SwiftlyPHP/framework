<?php
/**
 * Utility functions
 *
 * @author clvarley
 */

/**
 * Checks to see if every element in the array satisfies the callback
 *
 * Returns false is any element fails the test of the $callback function,
 * otherwise returns true.
 *
 * @psalm-param array<array-key,mixed> $subject
 * @psalm-param callable(mixed):bool $callback
 *
 * @param mixed[] $subject   Subject array
 * @param callable $callback Callback function
 * @return bool              Satisfies callback?
 */
function array_satisfies( array $subject, callable $callback ) : bool
{
    foreach ( $subject as $item ) {
        if ( !$callback( $item ) ) {
            return false;
        }
    }

    return true;
}

/**
 * Formats a human readable string for this number of bytes
 *
 * Because of floating point precision issues, this function can sometimes
 * return results that are not 100% accurate. Please only use it to display
 * user friendly values, not to calculate storage or any other non-output
 * related task.
 *
 * @param int $bytes Number of bytes
 * @return string    Formatted bytes
 */
function format_bytes( int $bytes ) : string
{
    $iterations = 4;

    while( $bytes > 1024 && $iterations !== 0 ) {
        $bytes = $bytes / 1024;
        $iterations--;
    }

    switch ( $iterations ) {
        case 0:
            $formatted = sprintf( '%.2f %s', $bytes, 'tb' );
        break;

        case 1:
            $formatted = sprintf( '%.2f %s', $bytes, 'gb' );
        break;

        case 2:
            $formatted = sprintf( '%.2f %s', $bytes, 'mb' );
        break;

        case 3:
            $formatted = sprintf( '%.2f %s', $bytes, 'kb' );
        break;

        default:
        case 4:
            $formatted = sprintf( '%d %s', $bytes, 'b' );
        break;
    }

    return $formatted;
}
