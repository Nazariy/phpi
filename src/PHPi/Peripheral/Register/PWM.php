<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral\Register;

class PWM extends AbstractRegister
{

    /** Registers */

    public const CTL = 0x0;
    public const STA = 0x4;
    public const DMAC = 0x8;
    public const RNG1 = 0x10;
    public const DAT1 = 0x14;
    public const FIF1 = 0x18;
    public const RNG2 = 0x20;
    public const DAT2 = 0x24;

    /** Offsets */

    public const MSEN1 = 0x0080; // M/S Enable
    public const USEF1 = 0x0020; // FIFO
    public const POLA1 = 0x0010; // Polarity
    public const SBIT1 = 0x0008; // Silence
    public const RPTL1 = 0x0004; // Repeat last value if FIFO empty
    public const MODE1 = 0x0002; // Run in serial mode
    public const PWEN1 = 0x0001; // Channel Enable

    public const MSEN2 = 0x8000; // M/S Enable
    public const USEF2 = 0x2000; // FIFO
    public const POLA2 = 0x1000; // Polarity
    public const SBIT2 = 0x0800; // Silence
    public const RPTL2 = 0x0400; // Repeat last value if FIFO empty
    public const MODE2 = 0x0200; // Run in serial mode
    public const PWEN2 = 0x0100; // Channel Enable

    public static $DAT = [self::DAT1, self::DAT2];
    public static $RNG = [self::RNG1, self::RNG2];

    public static $MSEN = [self::MSEN1, self::MSEN2];
    public static $POLA = [self::POLA1, self::POLA2];
    public static $PWEN = [self::PWEN1, self::PWEN2];

    /**
     * getOffset
     * @static
     * @return int
     */
    public static function getOffset(): int
    {
        return 0x20C000;
    }

}