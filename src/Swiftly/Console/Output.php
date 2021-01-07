<?php

namespace Swiftly\Console;

/**
 * Provides utility methods for dealing with console output
 *
 * @author clvarley
 */
Class Output
{

    /**
     * The ANSI escape code
     *
     * @var string ANSI_ESCAPE Escape code
     */
    const ANSI_ESCAPE = "\033";

    /**
     * The ANSI code for reset
     *
     * @var string ANSI_RESET ANSI code
     */
    const ANSI_RESET = SELF::ANSI_ESCAPE . "[0m";

    /**
     * The ANSI code for bold
     *
     * @var string ANSI_BOLD ANSI code
     */
    const ANSI_BOLD = SELF::ANSI_ESCAPE . "[1m";

    /**
     * The ANSI code for black
     *
     * @var string ANSI_BLACK ANSI code
     */
    const ANSI_BLACK = SELF::ANSI_ESCAPE . "[30m";

    /**
     * The ANSI code for red
     *
     * @var string ANSI_RED ANSI code
     */
    const ANSI_RED = SELF::ANSI_ESCAPE . "[31m";

    /**
     * The ANSI code for green
     *
     * @var string ANSI_GREEN ANSI code
     */
    const ANSI_GREEN = SELF::ANSI_ESCAPE . "[32m";

    /**
     * The ANSI code for yellow
     *
     * @var string ANSI_YELLOW ANSI code
     */
    const ANSI_YELLOW = SELF::ANSI_ESCAPE . "[33m";

    /**
     * The ANSI code for blue
     *
     * @var string ANSI_BLUE ANSI code
     */
    const ANSI_BLUE = SELF::ANSI_ESCAPE . "[34m";

    /**
     * The ANSI code for magenta
     *
     * @var string ANSI_MAGENTA ANSI code
     */
    const ANSI_MAGENTA = SELF::ANSI_ESCAPE . "[35m";

    /**
     * The ANSI code for cyan
     *
     * @var string ANSI_CYAN ANSI code
     */
    const ANSI_CYAN = SELF::ANSI_ESCAPE . "[36m";

    /**
     * The ANSI code for white
     *
     * @var string ANSI_WHITE ANSI code
     */
    const ANSI_WHITE = SELF::ANSI_ESCAPE . "[37m";

    /**
     * Outputs to the console
     *
     * @param string $output  Output
     * @return Output         Chainable
     */
    public function write( string $output ) : Output
    {
        echo $output;

        return $this;
    }

    /**
     * Outputs to the console with a newline
     *
     * @param string $output  Output
     * @return Output         Chainable
     */
    public function writeLine( string $output = '' ) : Output
    {
        echo $output . \PHP_EOL;

        return $this;
    }

    /**
     * Outputs a warning to the console followed by a newline
     *
     * @param string $output  Output
     * @return Output         Chainable
     */
    public function warn( string $output = '' ) : Output
    {
        $this->toYellow();

        $this->writeLine( $output );

        return $this->reset();
    }

    /**
     * Outputs an error to the console followed by a newline
     *
     * @param string $output  Output
     * @return Output         Chainable
     */
    public function error( string $output = '' ) : Output
    {
        $this->toRed();

        $this->writeLine( $output );

        return $this->reset();
    }

    /**
     * Outputs a success message to the console followed by a newline
     *
     * @param string $output  Output
     * @return Output         Chainable
     */
    public function success( string $output = '' ) : Output
    {
        $this->toGreen();

        $this->writeLine( $output );

        return $this->reset();
    }

    /**
     * Resets the terminal using the ANSI default code
     *
     * @return Output Chainable
     */
    public function reset() : Output
    {
        echo SELF::ANSI_RESET;

        return $this;
    }

    /**
     * Sets the terminal text to bold
     *
     * @return Output Chainable
     */
    public function toBold() : Output
    {
        echo SELF::ANSI_BOLD;

        return $this;
    }

    /**
     * Sets the terminal foreground colour to black
     *
     * @return Output Chainable
     */
    public function toBlack() : Output
    {
        echo SELF::ANSI_BLACK;

        return $this;
    }

    /**
     * Sets the terminal foreground colour to red
     *
     * @return Output Chainable
     */
    public function toRed() : Output
    {
        echo SELF::ANSI_RED;

        return $this;
    }

    /**
     * Sets the terminal foreground colour to green
     *
     * @return Output Chainable
     */
    public function toGreen () : Output
    {
        echo SELF::ANSI_GREEN;

        return $this;
    }

    /**
     * Sets the terminal foreground colour to yellow
     *
     * @return Output Chainable
     */
    public function toYellow () : Output
    {
        echo SELF::ANSI_YELLOW;

        return $this;
    }

    /**
     * Sets the terminal foreground colour to blue
     *
     * @return Output Chainable
     */
    public function toBlue() : Output
    {
        echo SELF::ANSI_BLUE;

        return $this;
    }

    /**
     * Sets the terminal foreground colour to magenta
     *
     * @return Output Chainable
     */
    public function toMagenta() : Output
    {
        echo SELF::ANSI_MAGENTA;

        return $this;
    }

    /**
     * Sets the terminal foreground colour to cyan
     *
     * @return Output Chainable
     */
    public function toCyan() : Output
    {
        echo SELF::ANSI_CYAN;

        return $this;
    }

    /**
     * Sets the terminal foreground colour to white
     *
     * @return Output Chainable
     */
    public function toWhite() : Output
    {
        echo SELF::ANSI_WHITE;

        return $this;
    }

}
