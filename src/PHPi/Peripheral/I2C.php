<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral;

use Calcinai\PHPi\Board;

class I2C extends AbstractPeripheral
{

    /**
     * I2C constructor.
     * @param Board $board
     * @param $spi_number
     * @throws \RuntimeException
     */
    public function __construct(Board $board, $spi_number)
    {
        $this->setBoard($board);
        throw new \RuntimeException('I2C not implemented');
    }

    /**
     * setFrequency
     * @param float $frequency
     * @return mixed
     */
    public function setFrequency(float $frequency)
    {
        return $this;
    }
}