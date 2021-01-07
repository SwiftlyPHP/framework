<?php

namespace Swiftly\Console;

/**
 * Provides utility methods for dealing with console input
 *
 * @author clvarley
 */
Class Input
{

    /**
     * Prompts the user for input
     *
     * @param string $prompt  The prompt message
     * @return string         User response
     */
    public function prompt( string $prompt ) : string
    {
        $input = \readline( $prompt );

        return \trim( $input );
    }

    /**
     * Ask the user to confirm
     *
     * @param string $prompt  The prompt message
     * @param string $yes     Value of confirmation
     * @param string $no      Value of denial
     * @return bool           User confirmed
     */
    public function confirm( string $prompt, string $yes = 'Y', string $no = 'N' ) : bool
    {
        $confirm = false;

        $yes = \mb_strtoupper( $yes );

        $no = \mb_strtoupper( $no );

        // Loop until user picks one
        while ( true ) {
            $input = \trim( \mb_strtoupper( \readline( $prompt . " [$yes/$no] " ) ) );

            if ( $input === $yes ) {
                $confirm = true;
                break;
            } elseif ( $input === $no ) {
                $confirm = false;
                break;
            }
        }

        return $confirm;
    }

}
