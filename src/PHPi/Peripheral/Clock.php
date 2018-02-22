<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral;

use Calcinai\PHPi\Board;

class Clock extends AbstractPeripheral
{

    private $clock_register;

    private $div;
    private $ctl;


    public const MIN_FREQUENCY = 0.0001;

    public const GP0 = 0;
    public const GP1 = 1;
    public const GP2 = 2;
    public const PCM = 3;
    public const PWM = 4;

    /**
     * @var array
     */
    public static $CTL = [
        self::GP0 => Register\Clock::GP0_CTL,
        self::GP1 => Register\Clock::GP1_CTL,
        self::GP2 => Register\Clock::GP2_CTL,
        self::PCM => Register\Clock::PCM_CTL,
        self::PWM => Register\Clock::PWM_CTL
    ];

    /**
     * @var array
     */
    public static $DIV = [
        self::GP0 => Register\Clock::GP0_DIV,
        self::GP1 => Register\Clock::GP1_DIV,
        self::GP2 => Register\Clock::GP2_DIV,
        self::PCM => Register\Clock::PCM_DIV,
        self::PWM => Register\Clock::PWM_DIV
    ];

    /**
     * Set of frequency presented as float numbers
     * @var array
     */
    public static $CLOCK_FREQUENCIES = [
        Register\Clock::SRC_OSC => 192e5, //19.2MHz
        Register\Clock::SRC_PLLA => 0,
        Register\Clock::SRC_PLLC => 100e7, //1GHz
        Register\Clock::SRC_PLLD => 500e6, //500MHz
        Register\Clock::SRC_HDMI => 216e6, //216MHz
    ];

    /**
     * Clock constructor.
     * @param Board $board
     * @param int $clock_number
     * @throws \Calcinai\PHPi\Exception\InternalFailureException
     * @throws \ReflectionException
     */
    public function __construct(Board $board, int $clock_number)
    {
        $this->setBoard($board);
        $this->clock_register = $this->board->getClockRegister();

        $this->div = static::$DIV[$clock_number];
        $this->ctl = static::$CTL[$clock_number];
    }

    /**
     * start
     * @param float $frequency
     * @param int $src
     * @return Clock
     * @throws \RangeException
     * @throws \OutOfRangeException
     */
    public function start(float $frequency, $src = Register\Clock::SRC_OSC): self
    {

        if (!isset(static::$CLOCK_FREQUENCIES[$src])) {
            throw new \RangeException(sprintf('Invalid clock source'));
        }

        $base_frequency = static::$CLOCK_FREQUENCIES[$src];

        if ($frequency < self::MIN_FREQUENCY || $frequency > $base_frequency) {
            throw new \OutOfRangeException(
                sprintf(
                    'Frequency must be between %f and %f',
                    self::MIN_FREQUENCY,
                    $base_frequency
                )
            );
        }


        $divi = $base_frequency / $frequency;
        $divr = $base_frequency % $frequency;
        $divf = ($divr * (1 << 12) / $base_frequency);

        $divi = min($divi, 4095);

        $this->clock_register[$this->div] = Register\AbstractRegister::BCM_PASSWORD | ($divi << 12) | $divf;
        usleep(10);

        $this->clock_register[$this->ctl] = Register\AbstractRegister::BCM_PASSWORD | $src;
        usleep(10);

        $this->clock_register[$this->ctl] |= Register\Clock::ENAB;

        return $this;
    }

    /**
     * stop
     * @return Clock
     */
    public function stop(): self
    {
        $this->clock_register[$this->ctl] = Register\AbstractRegister::BCM_PASSWORD | Register\Clock::KILL;
        usleep(110);

        //Wait for not busy
        while (($this->clock_register[$this->ctl] & Register\Clock::BUSY) !== 0) {
            usleep(10);
        }
        return $this;
    }

    /**
     * setFrequency
     * @param float $frequency
     * @return $this|AbstractPeripheral
     */
    public function setFrequency(float $frequency):self
    {
        return $this;
    }
}