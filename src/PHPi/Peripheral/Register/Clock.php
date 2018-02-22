<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral\Register;

class Clock extends AbstractRegister
{

    public const GP0_CTL = 0x1c; //0b0011100
    public const GP0_DIV = 0x1d; //0b0011101

    public const GP1_CTL = 0x1e; //0b0011110
    public const GP1_DIV = 0x1f; //0b0011111

    public const GP2_CTL = 0x20; //0b0100000
    public const GP2_DIV = 0x21; //0b0100001

    public const PCM_CTL = 0x26; //0b0100110
    public const PCM_DIV = 0x27; //0b0100111

    public const PWM_CTL = 0x28; //0b0101000
    public const PWM_DIV = 0x29; //0b0101001


    public const FLIP = 0x100;
    public const BUSY = 0x080;
    public const KILL = 0x020;
    public const ENAB = 0x010;

    public const SRC_MASK = 0xf;

    public const SRC_GND = 0x0;
    public const SRC_OSC = 0x1;
    public const SRC_TEST0 = 0x2;
    public const SRC_TEST1 = 0x3;
    public const SRC_PLLA = 0x4;
    public const SRC_PLLC = 0x5;
    public const SRC_PLLD = 0x6;
    public const SRC_HDMI = 0x7;

    /**
     * getOffset
     * @static
     * @return int
     */
    public static function getOffset(): int
    {
        return 0x101000;
    }

}