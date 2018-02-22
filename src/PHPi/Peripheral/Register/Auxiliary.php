<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral\Register;

/**
 * For some stupid reason, CIFS fails if this class is called 'Aux' so has to be this so I can dev.
 *
 * Class Auxiliary
 * @package Calcinai\PHPi\Peripheral\Register
 */
class Auxiliary extends AbstractRegister
{
    public const AUX_IRQ = 0x000; //Auxiliary Interrupt status
    public const AUX_ENABLES = 0x004; //Auxiliary enables

    public const AUX_MU_IO_REG = 0x040; //Mini UART I/O Data
    public const AUX_MU_IER_REG = 0x044; //Mini UART Interrupt Enable
    public const AUX_MU_IIR_REG = 0x048; //Mini UART Interrupt Identify
    public const AUX_MU_LCR_REG = 0x04C; //Mini UART Line Control
    public const AUX_MU_MCR_REG = 0x050; //Mini UART Modem Control
    public const AUX_MU_LSR_REG = 0x054; //Mini UART Line Status
    public const AUX_MU_MSR_REG = 0x058; //Mini UART Modem Status
    public const AUX_MU_SCRATCH = 0x05C; //Mini UART Scratch
    public const AUX_MU_CNTL_REG = 0x060; //Mini UART Extra Control
    public const AUX_MU_STAT_REG = 0x064; //Mini UART Extra Status
    public const AUX_MU_BAUD_REG = 0x068; //Mini UART Baudrate

    public const AUX_SPI0_CNTL0_REG = 0x080; //SPI 1 Control register 0
    public const AUX_SPI0_CNTL1_REG = 0x084; //SPI 1 Control register 1
    public const AUX_SPI0_STAT_REG = 0x088; //SPI 1 Status
    public const AUX_SPI0_IO_REG = 0x090; //SPI 1 Data
    public const AUX_SPI0_PEEK_REG = 0x094; //SPI 1 Peek

    public const AUX_SPI1_CNTL0_REG = 0x0C0; //SPI 2 Control register 0
    public const AUX_SPI1_CNTL1_REG = 0x0C4; //SPI 2 Control register 1
    public const AUX_SPI1_STAT_REG = 0x0C8; //SPI 2 Status
    public const AUX_SPI1_IO_REG = 0x0D0; //SPI 2 Data
    public const AUX_SPI1_PEEK_REG = 0x0D4; //SPI 2 Peek


    //Bits in the registers
    public const AUXIRQ_UART = 0b0001;
    public const AUXIRQ_SPI0 = 0b0010;
    public const AUXIRQ_SPI1 = 0b0100;

    public const AUXENB_UART = 0b0001;
    public const AUXENB_SPI0 = 0b0010;
    public const AUXENB_SPI1 = 0b0100;


    public static function getOffset(): int
    {
        return 0x215000;
    }

}