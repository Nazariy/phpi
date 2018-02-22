<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral;

use Calcinai\PHPi\Board;

/**
 * Credit to bcm2835.c for the constants and impl hints
 *
 * Currently only supporting SPI0, the other two are conditionally in the auxiliary reg.
 *
 * Class SPI
 * @package Calcinai\PHPi\Peripheral
 */
class SPI extends AbstractPeripheral
{
    /**
     *
     */
    public const SPI0 = 0;

    public const CS0 = 0; //Chip Select 0
    public const CS1 = 1; //Chip Select 1
    public const CS2 = 2; //Chip Select 2 (ie pins CS1 and CS2 are asserted)
    public const CS_NONE = 3; // CS, control it yourself

    /**
     * 250MHz - Haven't had time to check this yet.
     */
    public const SYSTEM_CLOCK_SPEED = 250e6;

    /**
     * @var Register\SPI
     */
    private $spi_register;
    /**
     * @var
     */
    private $spi_number;

    /**
     * SPI constructor.
     * @param Board $board
     * @param $spi_number
     * @throws \ReflectionException
     * @throws \Calcinai\PHPi\Exception\InternalFailureException
     */
    public function __construct(Board $board, $spi_number)
    {
        $this->board = $board;
        $this->spi_number = $spi_number;
        $this->spi_register = $board->getSPIRegister();
    }

    /**
     * @param float $frequency
     * @deprecated
     * @return $this
     */
    public function setClockSpeed(float $frequency): self
    {
        return $this->setFrequency($frequency);
    }

    /**
     * setFrequency
     * @param float $frequency
     * @return SPI
     */
    public function setFrequency(float $frequency): self
    {
        $divisor = self::SYSTEM_CLOCK_SPEED / $frequency;

        $this->spi_register[Register\SPI::CLK] = round($divisor);

        return $this;
    }


    /**
     * Since this is a register state, there's no need to call it on each transfer
     * (although you could if you were addressing multiple chis)
     *
     * @param $cex
     * @return $this
     */
    public function chipSelect($cex): self
    {
        $this->spi_register[Register\SPI::CS] = Register\SPI::CS_CS & $cex;

        return $this;
    }


    /**
     *
     * TODO - handle interrupts and mem barriers
     *
     * Maxes out at about 3kB/s, sorry!
     * Can get to about 20kB/s with ext-mmap - woohoo!
     *
     * @param $tx_buffer
     * @param int $cex
     * @return mixed
     */
    public function transfer($tx_buffer, $cex = null)
    {

        if ($cex !== null) {
            $this->chipSelect($cex);
        }

        $this->startTransfer();

        //Unpack the bytes and send/receive one by one
        //Slow because of the shallow FIFO
        //Also need to pack and unpack so there's a sensible interface to send data
        $rx_buffer = '';
        foreach (str_split($tx_buffer) as $char) {
            $rx_buffer .= $this->transferByte($char);
        }

        //This one might not be necessary
        while (!($this->spi_register[Register\SPI::CS] & Register\SPI::CS_DONE)) {
            usleep(1);
        }

        $this->endTransfer();

        return $rx_buffer;
    }


    /**
     * transferByte
     * @param $byte
     * @return string
     */
    private function transferByte($byte): string
    {
        // Wait for cts
        while (!($this->spi_register[Register\SPI::CS] & Register\SPI::CS_TXD)) {
            usleep(1);
        }
        $this->spi_register[Register\SPI::FIFO] = \ord($byte); //Just in case (PHP)

        //Wait for FIFO to be populated
        while (!($this->spi_register[Register\SPI::CS] & Register\SPI::CS_RXD)) {
            usleep(1);
        }

        return \chr($this->spi_register[Register\SPI::FIFO]);
    }

    /**
     * startTransfer
     */
    private function startTransfer(): void
    {
        //Clear TX and RX FIFO, set TA
        $this->spi_register[Register\SPI::CS] |= Register\SPI::CS_CLEAR | Register\SPI::CS_TA;
    }

    /**
     * endTransfer
     */
    private function endTransfer(): void
    {
        //Clear TA
        $this->spi_register[Register\SPI::CS] &= ~Register\SPI::CS_TA;
    }

    /**
     * Get SpiNumber
     * @access public
     * @return mixed
     */
    public function getSpiNumber()
    {
        return $this->spi_number;
    }

}