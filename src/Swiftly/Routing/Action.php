<?php

namespace Swiftly\Routing;

use Swiftly\{
    Base\Controller,
    Dependency\Container
};
use Swiftly\Http\Server\Response;

/**
 * Represents an action that can be called
 *
 * @author C Varley <clvarley>
 */
Class Action
{

    /**
     * The classname of the controller
     *
     * @var string $class Class name
     */
    private $class;

    /**
     * The controller method to call
     *
     * @var string $method Method name
     */
    private $method;

    /**
    * Scoped context variables
    *
    * @var array $context The context
    */
    private $context;

    /**
     * The controller used to handle the request
     *
     * @var Controller $controller Controller class
     */
    private $controller = null;

    /**
     * Create a new action using the controller and method provided
     *
     * @param string $class   Controller name
     * @param string $method  Controller method
     * @param array $context  (Optional) The context
     */
    public function __construct( string $class, string $method, array $context = [] )
    {
        $this->class = $class;
        $this->method = $method;
        $this->context = $context;
    }

    /**
     * Get the controller for this action
     *
     * @return Controller|null Controller
     */
    public function getController() : ?Controller
    {
        return $this->controller;
    }

    /**
     * Get the method for this action
     *
     * @return string Method name
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * Prepares the controller and method for execution
     *
     * @param array $args Constructor arguments
     * @return bool       Prepared successfully?
     */
    public function prepare( ...$args ) : bool
    {
        // Already prepared!
        if ( !\is_null( $this->controller ) ) {
            return true;
        }

        // No classname
        if ( empty( $this->class ) ) {
            return false;
        }

        $this->controller = new $this->class( ...$args );
        $this->method = $this->method ?: 'index';

        return \method_exists( $this->controller, $this->method );
    }

    /**
     * Execute the method and return the controller
     *
     * Calling code should have already called the {@see Action::prepare} method
     *
     * @param Container $services Dependency manager
     * @param array $params       Parameters
     * @return Response|null      Controller response
     */
    public function execute( Container $services, array $params = [] ) : ?Response
    {
        $args = [];

        // Merge the context with any passed params
        $params = \array_merge( $this->context, $params );

        $method_info = new \ReflectionMethod( $this->controller, $this->method );

        $method_params = $method_info->getParameters();

        // Handle the parameters
        if ( !empty( $method_params ) ) {
            $args = $this->handleParams( $method_params, $params, $services );
        }

        // Execute the method
        $response = $this->controller->{$this->method}( ...$args );

        return ( $response instanceof Response ? $response : null );
    }

    /**
     * Handles the parameters for the method
     *
     * @param  array $method_params Method Parameters
     * @param  array $context       Context variables
     * @param  Container $services  Dependency manager
     * @return array                Method arguments
     */
    private function handleParams( array $method_params, array $context, Container $services ) : array
    {
        $args = [];

        foreach ( $method_params as $param ) {
            $value = null;

            $name = $param->getName();
            $type = $param->getType();

            // Try to guess what the method wants
            if ( isset( $context[$name] ) && $this->isType( $type, $context[$name] ) ) {
                $value = $context[$name];
            } elseif ( !$type->isBuiltin() ) {
                $value = $services->resolve( $type->getName() );
            }

            // If we fail and get nothing
            if ( \is_null( $value ) && $param->isOptional() ) {
                $value = $param->getDefaultValue();
            }

            $args[$param->getPosition()] = $value;
        }

        return $args;
    }

    /**
     * Checks to see if a variable is of the given type
     *
     * @param \ReflectionType $type Variable type
     * @param mixed $variable       The variable
     * @return bool                 Is of type
     */
    private function isType( \ReflectionType $type, $variable ) : bool
    {
        $result = false;

        $name = $type->getName();

        // No type specified
        if ( empty( $name ) ) {
            return true;
        }

        // Use the appropriate check
        switch ( $name ) {
            case 'string':
                $result = \is_scalar( $variable );
            break;

            case 'int':
            case 'integer':
            case 'float':
            case 'double':
                $result = \is_numeric( $variable );
            break;

            case 'bool':
            case 'boolean':
                $result = \is_bool( $variable );
            break;

            default:
                $result = \is_a( $variable, $name );
            break;
        }

        return $result;
    }
}
