<?php

namespace Swiftly\Template;

use function is_readable;
use function ob_start;
use function ob_get_clean;

/**
 * Renders a template using PHP
 *
 * @author clvarley
 */
Class Php Implements TemplateInterface
{

    /**
     * @var array $data Template data
     */
    private $data = [];

    /**
     * @inheritdoc
     */
    public function render( string $template, array $data = [] ) : string
    {
        $template = "$template.html.php";

        if ( !is_readable( $template ) ) {
            return '';
        }

        $this->data = $data;

        ob_start();
            include $template;
        $result = ob_get_clean() ?: '';

        return $result;
    }

    /**
     * Renders a partial template and returns the result
     *
     * @param string $template  Path to template
     * @return string           Rendered template
     */
    public function renderPartial( string $template ) : string
    {
        $template = "$template.html.php";

        if ( !is_readable( $template ) ) {
            return '';
        }

        ob_start();
            include $template;
        $result = ob_get_clean();

        return $result;
    }

    /**
     * Provide support for direct access to `$this->data`
     *
     * @param string $name    Variable name
     * @return mixed          The value
     */
    public function __get( string $name )
    {
        return ( isset( $this->data[$name] )
            ? $this->data[$name]
            : ''
        );
    }

    /**
     * Allows the setting of data values
     *
     * @param string $name  Variable name
     * @param mixed $value  Variable value
     */
    public function __set( string $name, /* mixed */ $value )
    {
        $this->data[$name] = $value;
    }

    /**
     * Provide support for isset() & empty()
     *
     * @param string $name    Variable name
     * @return boolean        Isset?
     */
    public function __isset( string $name )
    {
        return ( isset( $this->data[$name] ) );
    }
}
