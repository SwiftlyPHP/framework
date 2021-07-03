<?php

namespace Swiftly\Base;

use Swiftly\Template\TemplateInterface;
use Swiftly\Dependency\Container;
use Swiftly\Base\AbstractModel;
use Swiftly\Http\Server\Response;
use Swiftly\Http\Server\RedirectResponse;

use function is_file;
use function class_exists;

use const APP_MODEL;
use const APP_VIEW;

/**
 * The abstract class all controllers should inherit
 *
 * @author clvarley
 */
Abstract Class AbstractController
{

    /**
     * @var Container $dependencies Dependency manager
     */
    private $dependencies;

    /**
     * @var TemplateInterface $renderer Internal renderer
     */
    private $renderer;

    /**
     * @var AbstractModel[] $models DB Models
     */
    private $models = [];

    /**
     * Load the services into the base controller
     *
     * @param Container $container Dependency manager
     */
    public function __construct( Container $container, TemplateInterface $renderer )
    {
        $this->dependencies = $container;
        $this->renderer = $renderer;
    }

    /**
     * Attempts to get a DB model
     *
     * @param string $name Model name
     * @return AbstractModel|null  DB model (Or null)
     */
    public function getModel( string $name ) : ?AbstractModel
    {
        if ( isset( $this->models[$name] ) ) {
            return $this->models[$name];
        }

        $model = $this->createModel( $name );

        if ( $model === null ) {
            return null;
        }

        $this->models[$name] = $model;

        return $this->models[$name];
    }

    /**
     * Tries to create a model of the given type
     *
     * @param string $name Model name
     * @return AbstractModel|null  Db model (Or null)
     */
    private function createModel( string $name ) : ?AbstractModel
    {
        $file = APP_MODEL . "$name.php";

        if ( !is_file( $file ) ) {
            return null;
        }

        // TODO: This needs a lot of work!
        include $file;

        if ( !class_exists( $name ) ) {
            return null;
        }

        // Allows model to have dependency injection
        $this->dependencies->bind( $name, $name );
        $model = $this->dependencies->resolve( $name );

        return ( $model instanceof AbstractModel
            ? $model
            : null
        );
    }

    /**
     * Renders the given template with the data provided
     *
     * @param  string $template Template path
     * @param  array  $data     Template data
     * @return string           Rendered template
     */
    public function render( string $template, array $data = [] ) : string
    {
        return $this->renderer->render( APP_VIEW . $template, $data );
    }

    /**
     * Renders a template and wraps it in a Response object
     *
     * @param string $template Template
     * @param array  $data     Template data
     * @return Response        Response object
     */
    public function output( string $template, array $data = [] ) : Response
    {
        return new Response(
            $this->renderer->render( APP_VIEW . $template, $data ),
            200,
            []
        );
    }

    /**
     * Redirect the user to a new location
     *
     * @param string $url Redirect location
     * @param int $code   (Optional) HTTP code
     * @return never
     */
    public function redirect( string $url, int $code = 303 ) : void
    {
        $redirect = new RedirectResponse( $url, $code, [] );
        $redirect->send();
        die;
    }
}
