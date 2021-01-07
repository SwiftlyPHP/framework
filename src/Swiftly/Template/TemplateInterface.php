<?php

namespace Swiftly\Template;

/**
 * Interface for renderable templates
 *
 * @author clvarley
 */
Interface TemplateInterface
{

    /**
     * Renders the given template optionally using the data provided
     *
     * @param string $template  Path to template
     * @param array $data       Template data
     * @return string           Rendered content
     */
    public function render( string $template, array $data = [] ) : string;

}
