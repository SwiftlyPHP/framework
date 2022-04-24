<?php

namespace Swiftly\Middleware;

use Swiftly\Middleware\MiddlewareInterface;
use Swiftly\Http\Server\Request;
use Swiftly\Http\Server\Response;

use function sha1;
use function is_file;
use function is_dir;
use function mkdir;
use function file_put_contents;
use function strtolower;
use function strpos;

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
        if ( $response->getStatus() !== 200 || !$this->isCacheable( $request ) ) {
            return $next( $request, $response );
        }

        // Is "Cache-Control: no-store" set?
        if ( !$this->canStore( $request ) ) {
            return $next( $request, $response );
        }

        $hash = sha1( $request->getPath() );
        $dir  = APP_DATA . '/cache/html';
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

    /**
     * Check to see if this request is considered cacheable
     *
     * @param Request $request Client request
     * @return bool            Is cacheable?
     */
    private function isCacheable( Request $request ) : bool
    {
        return ( $request->getMethod() === 'GET'
            && empty( $request->query->all() )
        );
    }

    /**
     * Check to see if the client has requested we don't store this content
     *
     * @param Request $request Client request
     * @return bool            Can store?
     */
    private function canStore( Request $request ) : bool
    {
        $header = $request->headers->get( 'Cache-Control' );
        $header = $header ? strtolower( $header ) : '';

        return strpos( $header, 'no-store' ) === false;
    }
}
