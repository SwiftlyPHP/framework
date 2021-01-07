<?php

namespace Swiftly\Template;

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
        $result = '';

        $this->data = $data;

        if ( \is_file( $template . '.html.php' ) && \is_readable( $template . '.html.php' ) ) {
            \ob_start();
            include $template . '.html.php';
            $result = \ob_get_clean() ?: '';
        }

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
        $result = '';

        if ( \is_file( $template . '.html.php' ) && \is_readable( $template . '.html.php' ) ) {
            \ob_start();
            include $template . '.html.php';
            $result = \ob_get_clean();
        }

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
        return ( isset( $this->data[$name] ) ? $this->data[$name] : '' );
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
