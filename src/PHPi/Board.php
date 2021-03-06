<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi;

use Calcinai\PHPi\Board\BoardInterface;
use Calcinai\PHPi\Peripheral\Clock;
use Calcinai\PHPi\Peripheral\I2C;
use Calcinai\PHPi\Peripheral\PWM;
use Calcinai\PHPi\Peripheral\Register;
use Calcinai\PHPi\Peripheral\SPI;
use Calcinai\PHPi\Pin\EdgeDetector;
use React\EventLoop\LoopInterface;

abstract class Board implements BoardInterface
{
    public const NAME = null;
    /**
     * @var \React\EventLoop\LibEvLoop|LoopInterface
     */
    private $loop;

    /**
     * @var Register\GPIO
     *
     * Register for gpio functions
     */
    protected $gpio_register;

    /**
     * @var Register\PWM
     */
    protected $pwm_register;

    /**
     * @var Register\Clock
     */
    protected $clock_register;

    /**
     * @var Register\SPI
     */
    protected $spi_register;

    /**
     * @var Register\Auxiliary
     */
    protected $aux_register;

    /**
     * @var EdgeDetector\EdgeDetectorInterface
     */
    protected $edge_detector;

    /**
     * @var Pin[]
     */
    private $pins = [];

    /**
     * @var PWM[]
     */
    private $pwms = [];

    /**
     * @var Clock[]
     */
    private $clocks = [];

    /**
     * @var SPI[]
     */
    private $spis;

    /**
     * @var I2C[]
     */
    private $i2cs;


    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
    }

    public function __destruct()
    {
        Pin\SysFS::cleanup();
    }

    public function getLoop()
    {
        return $this->loop;
    }

    /**
     * @param $pin_number
     * @return Pin
     */
    public function getPin($pin_number): Pin
    {

        if (!isset($this->pins[$pin_number])) {
            $this->pins[$pin_number] = new Pin($this, $pin_number);
        }

        return $this->pins[$pin_number];
    }

    /**
     * @param $pwm_number
     * @return PWM
     * @throws \ReflectionException
     * @throws \OutOfRangeException
     * @throws \Calcinai\PHPi\Exception\InternalFailureException
     */
    public function getPWM($pwm_number): PWM
    {
        if (!isset($this->pwms[$pwm_number])) {
            $this->pwms[$pwm_number] = new PWM($this, $pwm_number);
        }

        return $this->pwms[$pwm_number];
    }

    /**
     * @param $clock_number
     * @return Clock
     * @throws \ReflectionException
     * @throws \Calcinai\PHPi\Exception\InternalFailureException
     */
    public function getClock($clock_number): Clock
    {
        if (!isset($this->clocks[$clock_number])) {
            $this->clocks[$clock_number] = new Clock($this, $clock_number);
        }

        return $this->clocks[$clock_number];
    }

    /**
     * @param $spi_number
     * @return SPI
     * @throws \ReflectionException
     * @throws \Calcinai\PHPi\Exception\InternalFailureException
     */
    public function getSPI($spi_number): SPI
    {
        if (!isset($this->spis[$spi_number])) {
            $this->spis[$spi_number] = new SPI($this, $spi_number);
        }

        return $this->spis[$spi_number];
    }

    /**
     * @param $i2c_number
     * @return SPI
     * @throws \RuntimeException
     */
    public function getI2C($i2c_number): SPI
    {
        if (!isset($this->i2cs[$i2c_number])) {
            $this->i2cs[$i2c_number] = new I2C($this, $i2c_number);
        }

        return $this->i2cs[$i2c_number];
    }

    /**
     * @return Register\GPIO
     * @throws Exception\InternalFailureException
     * @throws \ReflectionException
     */
    public function getGPIORegister(): Peripheral\Register\GPIO
    {
        if (null !== $this->gpio_register) {
            $this->gpio_register = new Register\GPIO($this);
        }

        return $this->gpio_register;
    }

    /**
     * @return Register\PWM
     * @throws Exception\InternalFailureException
     * @throws \ReflectionException
     */
    public function getPWMRegister(): Peripheral\Register\PWM
    {
        if (null !== $this->pwm_register) {
            $this->pwm_register = new Register\PWM($this);
        }

        return $this->pwm_register;
    }

    /**
     * @return Register\Clock
     * @throws Exception\InternalFailureException
     * @throws \ReflectionException
     */
    public function getClockRegister(): Peripheral\Register\Clock
    {
        if (null !== $this->clock_register) {
            $this->clock_register = new Register\Clock($this);
        }

        return $this->clock_register;
    }

    /**
     * @return Register\Auxiliary
     * @throws Exception\InternalFailureException
     * @throws \ReflectionException
     */
    public function getAuxRegister(): Peripheral\Register\Auxiliary
    {
        if (null !== $this->aux_register) {
            $this->aux_register = new Register\Auxiliary($this);
        }

        return $this->aux_register;
    }

    /**
     * @return Register\SPI
     * @throws Exception\InternalFailureException
     * @throws \ReflectionException
     */
    public function getSPIRegister(): Peripheral\Register\SPI
    {
        if (null !== $this->spi_register) {
            $this->spi_register = new Register\SPI($this);
        }

        return $this->spi_register;
    }

    /**
     * getEdgeDetector
     * @return EdgeDetector\EdgeDetectorInterface|EdgeDetector\Rubberneck|EdgeDetector\StatusPoll
     */
    public function getEdgeDetector()
    {
        if (null !== $this->edge_detector) {
            $this->edge_detector = EdgeDetector\Factory::create($this);
        }
        return $this->edge_detector;
    }

    /**
     * Should be overloaded by fracture trait
     */
    public function getPhysicalPins(): array
    {
        return [];
    }


    /**
     * Some of this is the same as the factory, but it's a bit more granular.
     *
     * @return \stdClass
     */
    public static function getMeta(): \stdClass
    {

        $meta = new \stdClass();

        //Get a whole lot of stuff - parsing them is the same.
        $info = file_get_contents('/proc/cpuinfo') . `lscpu`;

        $meta->serial = 'unknown';
        $meta->speed = 0;
        $meta->cpu = 'unknown';
        $meta->num_cores = 0;

        foreach (explode("\n", $info) as $line) {
            //null,null avoid undefined offset.
            [$tag, $value] = explode(':', $line, 2) + [null, null];

            switch (strtolower(trim($tag))) {
                case 'revision':
                    $meta->revision = trim($value);
                    break;
                case 'serial':
                    $meta->serial = trim($value);
                    break;
                case 'cpu(s)':
                    $meta->num_cores = trim($value);
                    break;
                case 'model name':
                    $meta->cpu = trim($value);
                    break;
                case 'cpu max mhz':
                    $meta->speed = trim($value);
                    break;
            }
        }

        $meta->board_name = static::getBoardName();

        return $meta;
    }

    public static function getBoardName(): string
    {
        return static::NAME ?? static::class;
    }
}