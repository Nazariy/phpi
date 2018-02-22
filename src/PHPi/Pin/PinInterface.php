<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Pin;

interface PinInterface
{

    /**
     * Binary function selectors.  Each pin has 3 bits.  4 registers total.
     */
    public const INPUT = 0b000;
    public const OUTPUT = 0b001;
    public const ALT0 = 0b100;
    public const ALT1 = 0b101;
    public const ALT2 = 0b110;
    public const ALT3 = 0b111;
    public const ALT4 = 0b011;
    public const ALT5 = 0b010;


    /**
     * Named alternative functions - these are used in the board function matrices
     */

    /** BSC master 0 data line */
    public const SDA0 = 'SDA0';

    /** BSC master 0 clock line */
    public const SCL0 = 'SCL0';

    /** BSC master 1 data line */
    public const SDA1 = 'SDA1';

    /** BSC master 1 clock line */
    public const SCL1 = 'SCL1';

    /** General purpose Clock 0 */
    public const GPCLK0 = 'GPCLK0';

    /** General purpose Clock 1 */
    public const GPCLK1 = 'GPCLK1';

    /** General purpose Clock 2 */
    public const GPCLK2 = 'GPCLK2';

    /** SPI0 Chip select 1 */
    public const SPI0_CE1_N = 'SPI0_CE1_N';

    /** SPI0 Chip select 0 */
    public const SPI0_CE0_N = 'SPI0_CE0_N';

    /** SPI0 MISO */
    public const SPI0_MISO = 'SPI0_MISO';

    /** SPI0 MOSI */
    public const SPI0_MOSI = 'SPI0_MOSI';

    /** SPI0 Serial clock */
    public const SPI0_SCLK = 'SPI0_SCLK';

    /** Pulse Width Modulator 0 */
    public const PWM0 = 'PWM0';

    /** Pulse Width Modulator 1 */
    public const PWM1 = 'PWM1';

    /** UART 0 Transmit Data */
    public const TXD0 = 'TXD0';

    /** UART 0 Receive Data */
    public const RXD0 = 'RXD0';

    /** UART 0 Clear To Send */
    public const CTS0 = 'CTS0';

    /** UART 0 Request To Send */
    public const RTS0 = 'RTS0';

    /** PCM clock */
    public const PCM_CLK = 'PCM_CLK';

    /** PCM Frame Sync */
    public const PCM_FS = 'PCM_FS';

    /** PCM Data in */
    public const PCM_DIN = 'PCM_DIN';

    /** PCM data out */
    public const PCM_DOUT = 'PCM_DOUT';

    /** SPI1 Chip select 0 */
    public const SPI1_CE0_N = 'SPI1_CE0_N';

    /** SPI1 Chip select 1 */
    public const SPI1_CE1_N = 'SPI1_CE1_N';

    /** SPI1 Chip select 2 */
    public const SPI1_CE2_N = 'SPI1_CE2_N';

    /** SPI1 MISO */
    public const SPI1_MISO = 'SPI1_MISO';

    /** SPI1 MOSI */
    public const SPI1_MOSI = 'SPI1_MOSI';

    /** SPI1 Serial clock */
    public const SPI1_SCLK = 'SPI1_SCLK';

    /** UART 1 Transmit Data */
    public const TXD1 = 'TXD1';

    /** UART 1 Receive Data */
    public const RXD1 = 'RXD1';

    /** UART 1; Clear To Send */
    public const CTS1 = 'CTS1';

    /** UART 1 Request To Send */
    public const RTS1 = 'RTS1';

    /** SPI2 Chip select 0 */
    public const SPI2_CE0_N = 'SPI2_CE0_N';

    /** SPI2 Chip select 1 */
    public const SPI2_CE1_N = 'SPI2_CE1_N';

    /** SPI2 Chip select 2 */
    public const SPI2_CE2_N = 'SPI2_CE2_N';

    /** SPI2 MISO */
    public const SPI2_MISO = 'SPI2_MISO';

    /** SPI2 MOSI */
    public const SPI2_MOSI = 'SPI2_MOSI';

    /** SPI2 Serial clock */
    public const SPI2_SCLK = 'SPI2_SCLK';

    /** ARM JTAG reset */
    public const ARM_TRST = 'ARM_TRST';

    /** ARM JTAG return clock */
    public const ARM_RTCK = 'ARM_RTCK';

    /** ARM JTAG Data out */
    public const ARM_TDO = 'ARM_TDO';

    /** ARM JTAG Clock */
    public const ARM_TCK = 'ARM_TCK';

    /** ARM JTAG Data in */
    public const ARM_TDI = 'ARM_TDI';

    /** ARM JTAG Mode select */
    public const ARM_TMS = 'ARM_TMS';
}