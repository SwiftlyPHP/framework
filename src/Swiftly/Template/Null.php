<?php

namespace Swiftly\Template;

/**
 * A null template
 *
 * @author clvarley
 */
Class Null Implements TemplateInterface
{

    /**
     * Returns an empty string
     *
     * @param string $template  Path to template
     * @param array $data       Template data
     * @return string           Empty string
     */
    public function render( string $template, array $data = [] ) : string
    {
        return '';
    }

}