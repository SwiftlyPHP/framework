<?php

namespace Swiftly\Middleware;

use Swiftly\Http\Server\Request;
use Swiftly\Http\Server\Response;
use Swiftly\Middleware\MiddlewareInterface;

use function strtolower;
use function strpos;
use function sha1;
use function is_file;
use function is_dir;
use function mkdir;
use function file_put_contents;

use const APP_DATA;

/**
 * Middleware responsible for caching HTML responses
 *
 * @author clvarley
 */
Class CacheWriterMiddleware Implements MiddlewareInterface
{

    /**
     * {@inheritdoc}
     */
    public function run( Request $request, Response $response, callable $next ) : Response
    {
        // Only cache successful (HTTP 200) GET requests with NO parameters
        if ( $request->getMethod() !== 'GET'
            || $response->getStatus() !== 200
            || $request->query->all()
        ) {
            return $next( $request, $response );
        }

        // Is "Cache-Control: no-store" set?
        $cache_control = $request->headers->get( 'Cache-Control' );
        $cache_control = $cache_control ? strtolower( $cache_control ) : '';

        if ( strpos( $cache_control, 'no-store' ) !== false ) {
            return $next( $request, $response );
        }

        $hash = sha1( $request->getPath() );
        $dir  = APP_DATA . 'cache/html';
        $file = "$dir/$hash.html";

        // Already cached!
        if ( is_file( $file ) ) {
            return $next( $request, $response );
        }

        // Failed to make directory
        if ( !is_dir( $dir ) && !@mkdir( $dir, 0764, true ) ) {
            return $next( $request, $response );
        }

        // Cache the content!
        $content = $response->getContent();
        file_put_contents( $file, $content );

        return $next( $request, $response );
    }
}
