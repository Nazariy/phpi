<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral;


use Calcinai\PHPi\Board;

abstract class AbstractPeripheral
{
    /**
     * @var Board
     */
    protected $board;

    /**
     * Set Board
     * @access public
     * @param Board $board
     * @return self
     */
    protected function setBoard(Board $board): self
    {
        $this->board = $board;
        return $this;
    }

    /**
     * @return Board
     */
    public function getBoard(): Board
    {
        return $this->board;
    }

    /**
     * setFrequency
     * @param float $frequency
     * @return mixed
     */
    abstract public function setFrequency(float $frequency);
}