<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral\Register;

/**
 * Class GPIO
 * @package Calcinai\PHPi\Peripheral\Register
 */
class GPIO extends AbstractRegister
{

    public const GPFSEL0 = 0x0000; // GPIO Function Select 0 (R/W)
    public const GPFSEL1 = 0x0004; // GPIO Function Select 1 (R/W)
    public const GPFSEL2 = 0x0008; // GPIO Function Select 2 (R/W)
    public const GPFSEL3 = 0x000C; // GPIO Function Select 3 (R/W)
    public const GPFSEL4 = 0x0010; // GPIO Function Select 4 (R/W)
    public const GPFSEL5 = 0x0014; // GPIO Function Select 5 (R/W)
    public const GPSET0 = 0x001C; // GPIO Pin Output Set 0 (W)
    public const GPSET1 = 0x0020; // GPIO Pin Output Set 1 (W)
    public const GPCLR0 = 0x0028; // GPIO Pin Output Clear 0 (W)
    public const GPCLR1 = 0x002C; // GPIO Pin Output Clear 1 (W)
    public const GPLEV0 = 0x0034; // GPIO Pin Level 0 (R)
    public const GPLEV1 = 0x0038; // GPIO Pin Level 1 (R)
    public const GPEDS0 = 0x0040; // GPIO Pin Event Detect Status 0 (R/W)
    public const GPEDS1 = 0x0044; // GPIO Pin Event Detect Status 1 (R/W)
    public const GPREN0 = 0x004C; // GPIO Pin Rising Edge Detect Enable 0 (R/W)
    public const GPREN1 = 0x0050; // GPIO Pin Rising Edge Detect Enable 1 (R/W)
    public const GPFEN0 = 0x0058; // GPIO Pin Falling Edge Detect Enable 0 (R/W)
    public const GPFEN1 = 0x005C; // GPIO Pin Falling Edge Detect Enable 1 (R/W)
    public const GPHEN0 = 0x0064; // GPIO Pin High Detect Enable 0 (R/W)
    public const GPHEN1 = 0x0068; // GPIO Pin High Detect Enable 1 (R/W)
    public const GPLEN0 = 0x0070; // GPIO Pin Low Detect Enable 0 (R/W)
    public const GPLEN1 = 0x0074; // GPIO Pin Low Detect Enable 1 (R/W)
    public const GPAREN0 = 0x007C; // GPIO Pin Async. Rising Edge Detect 0 (R/W)
    public const GPAREN1 = 0x0080; // GPIO Pin Async. Rising Edge Detect 1 (R/W)
    public const GPAFEN0 = 0x0088; // GPIO Pin Async. Falling Edge Detect 0 (R/W)
    public const GPAFEN1 = 0x008C; // GPIO Pin Async. Falling Edge Detect 1 (R/W)
    public const GPPUD = 0x0094; // GPIO Pin Pull-up/down Enable (R/W)
    public const GPPUDCLK0 = 0x0098; // GPIO Pin Pull-up/down Enable Clock 0 (R/W)
    public const GPPUDCLK1 = 0x009C; // GPIO Pin Pull-up/down Enable Clock 1 (R/W)


    public static $GPFSEL = [
        self::GPFSEL0,
        self::GPFSEL1,
        self::GPFSEL2,
        self::GPFSEL3,
        self::GPFSEL4,
        self::GPFSEL5
    ];
    public static $GPSET = [
        self::GPSET0,
        self::GPSET1
    ];
    public static $GPCLR = [
        self::GPCLR0,
        self::GPCLR1
    ];
    public static $GPLEV = [
        self::GPLEV0,
        self::GPLEV1
    ];

    public static $GPEDS = [
        self::GPEDS0,
        self::GPEDS1
    ];
    public static $GPREN = [
        self::GPREN0,
        self::GPREN1
    ];
    public static $GPFEN = [
        self::GPFEN0,
        self::GPFEN1
    ];

    public static $GPPUDCLK = [
        self::GPPUDCLK0,
        self::GPPUDCLK1
    ];

    public static function getOffset(): int
    {
        return 0x200000;
    }

    /**
     * getDirectMemoryFile
     * @static
     * @return string
     */
    public static function getDirectMemoryFile(): string
    {
        return '/dev/gpiomem';
    }

}