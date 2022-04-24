<?php

namespace Swiftly\Middleware;

use Swiftly\Middleware\MiddlewareInterface;
use Swiftly\Config\Store;
use Swiftly\Http\Server\Request;
use Swiftly\Http\Server\Response;

use function sha1;
use function is_readable;
use function filemtime;
use function time;
use function unlink;
use function file_get_contents;
use function rtrim;
use function strtolower;
use function strpos;

use const APP_ROOT;
use const DIRECTORY_SEPARATOR;

/**
 * Middleware responsible for returning cached HTML
 *
 * @author clvarley
 */
Class CacheReaderMiddleware Implements MiddlewareInterface
{

    /**
     * Reference to the main application configuration
     *
     * @var Store $config Application config
     */
    private $config;

    /**
     * Create a middleware for reading cache files
     *
     * @param Store $config Application config
     */
    public function __construct( Store $config )
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function run( Request $request, Response $response, callable $next ) : Response
    {
        // Only return cached content for GET requests with NO parameters
        if ( $request->getMethod() !== 'GET' || $request->query->all() ) {
            return $next( $request, $response );
        }

        // Client asked to avoid cache?
        if ( $this->wantsFresh( $request ) ) {
            return $next( $request, $response );
        }

        // Generate file name
        $dir = $this->getDirectory();
        $hash = sha1( $request->getPath() );
        $file = "$dir/$hash.html";

        // Cache file doesn't exist!
        if ( !is_readable( $file ) ) {
            return $next( $request, $response );
        }

        // File expired?
        $filetime = filemtime( $file );
        $lifetime = (int)$this->config->get( 'cache.lifetime', 3600 );

        $expires = $filetime + $lifetime;

        // Expired!
        if ( $expires < time() ) {
            unlink( $file );
            return $next( $request, $response );
        }

        // Use the cache!
        $content = file_get_contents( $file );
        $response->setContent( $content );

        return $response;
    }

    /**
     * Gets the path to the cache directory
     *
     * @return string Directory path
     */
    private function getDirectory() : string
    {
        // Custom directory?
        $dir = (string)$this->config->get( 'cache.root' );

        if ( !empty( $dir ) ) {
            $dir = APP_ROOT . rtrim( $dir, DIRECTORY_SEPARATOR );
        } else {
            $dir = APP_ROOT . '/data/cache/html';
        }

        return $dir;
    }

    /**
     * Check to see if the client requested fresh/uncached content
     *
     * @param Request $request Client request
     * @return bool            Wants fresh?
     */
    private function wantsFresh( Request $request ) : bool
    {
        $header = $request->headers->get( 'Cache-Control' );
        $header = $header ? strtolower( $header ) : '';

        return ( strpos( $header, 'no-cache' ) !== false
            || strpos( $header, 'no-store' ) !== false
        );
    }
}
