<?php

namespace Swiftly\Template;

use function is_readable;
use function extract;
use function ob_start;
use function ob_get_clean;

use const EXTR_PREFIX_SAME;

/**
 * Renders a single PHP template
 *
 * @author clvarley
 */
Class Php Implements TemplateInterface
{

    /**
     * @inheritdoc
     */
    public function render( string $template, array $data = [] ) : string
    {
        $template = "$template.html.php";

        if ( !is_readable( $template ) ) {
            return '';
        }

        // Stop templates accessing the $this variable
        $render = static function ( array $data ) use ($template) : void {
            extract( $data, EXTR_PREFIX_SAME, '_' );
            require $template;
        };

        ob_start();
        $render( $data );
        return ob_get_clean() ?: '';
    }
}
