<?php

namespace Swiftly\Middleware;

use Swiftly\Http\Server\{
    Request,
    Response
};
use Swiftly\Middleware\MiddlewareInterface;

use function strtolower;
use function strpos;
use function sha1;
use function is_readable;
use function file_get_contents;

/**
 * Middleware responsible for returning cached HTML
 *
 * @author clvarley
 */
Class HttpCacheMiddleware Implements MiddlewareInterface
{

    /**
     * {@inheritdoc}
     */
    public function run( Request $request, Response $response, callable $next ) : Response
    {
        // Only return cached content for GET requests with NO parameters
        if ( $request->getMethod() !== 'GET' || $request->query->all() ) {
            return $next( $request, $response );
        }

        $cache_control = $request->headers->get( 'Cache-Control' );
        $cache_control = $cache_control ? strtolower( $cache_control ) : '';

        // Asked for no-cache?
        if ( strpos( $cache_control, 'no-cache' ) !== false
            || strpos( $cache_control, 'no-store' ) !== false
        ) {
            return $next( $request, $response );
        }

        $hash = sha1( $request->getPath() );
        $file = APP_DATA . "cache/html/$hash.php";

        // Cache file doesn't exist!
        if ( !is_readable( $file ) ) {
            return $next( $request, $response );
        }

        // Use the cache!
        $content = file_get_contents( $file );
        $response->setContent( $content );

        return $response;
    }
}
