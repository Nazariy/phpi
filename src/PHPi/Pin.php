<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi;

use Calcinai\PHPi\Exception\InvalidPinFunctionException;
use Calcinai\PHPi\Peripheral\Register;
use Calcinai\PHPi\Pin\PinInterface;
use Calcinai\PHPi\Traits\EventEmitterTrait;

class Pin
{
    use EventEmitterTrait;

    public const LEVEL_LOW = 0;
    public const LEVEL_HIGH = 1;

    public const PULL_NONE = 0b00;
    public const PULL_DOWN = 0b01;
    public const PULL_UP = 0b10;


    public const EVENT_FUNCTION_CHANGE = 'function.change';

    public const EVENT_LEVEL_CHANGE = 'level.change';
    public const EVENT_LEVEL_HIGH = 'level.high';
    public const EVENT_LEVEL_LOW = 'level.low';


    /**
     * @var Board $board
     */
    private $board;

    /**
     * @var Register\GPIO
     */
    private $gpio_register;

    /**
     * @var int BCM pin number
     */
    private $pin_number;

    /**
     * In unknown at start, so has to be actively disabled.
     *
     * @var int
     */
    private $pull;


    /**
     * Internal function cache - this will only be the last known function, not actually updated until it changes.
     *
     * @var int function select
     */
    private $internal_function;


    /**
     * Internal level cache - this will only be the last known level, not actually updated until it changes.
     *
     * @var null|self::LEVEL_LOW|self::LEVEL_HIGH
     */
    private $internal_level;

    /**
     * @var array
     */
    private $mask_cache = [];

    /**
     * Pin constructor.
     * @param Board $board
     * @param $pin_number
     * @throws \Calcinai\PHPi\Exception\InternalFailureException
     */
    public function __construct(Board $board, $pin_number)
    {
        $this->board = $board;
        $this->gpio_register = $board->getGPIORegister();
        $this->pin_number = $pin_number;

        //This needs to be done since it could be in any state, and the user would never know.
        //Without this could lead to unpredictable behaviour.
        //$this->setPull(self::PULL_NONE); //Maybe it should be up tot he user after all.

        //Set internal level
        $this->getLevel();
        //Set internal function
        $this->getFunction();
    }

    /**
     * Set the pin function from IN/OUT/ALT0-5
     *
     * @param $function
     * @return $this
     * @throws InvalidPinFunctionException
     */
    public function setFunction($function): self
    {

        if (\is_string($function)) {
            $function = $this->getAltCodeForPinFunction($function);
        }

        $this->setInternalFunction($function);
        [$bank, $mask, $shift] = $this->getAddressMask(3);

        //This feels like its getting messy!  There must be a way to do this with ^=
        $reg = $this->gpio_register[Register\GPIO::$GPFSEL[$bank]];
        $this->gpio_register[Register\GPIO::$GPFSEL[$bank]] = ($reg & ~$mask) | ($function << $shift);

        return $this;
    }


    /**
     * Function to cache internal function and emit events
     *
     * @param string $function
     * @return int
     */
    private function setInternalFunction($function): int
    {
        if ($this->internal_function !== null) {
            if ($this->internal_function !== $function) {
                //This has to be done so you don't get recursion if you access ->getFunction from within the event.
                $old_function = $this->internal_function;
                $this->internal_function = $function;

                $this->emit(self::EVENT_FUNCTION_CHANGE, [$function, $old_function]);
            }
            //If it's set and the same, just leave it.
        } else {
            //Otherwise, if it's not set, set it.
            $this->internal_function = $function;
        }

        return $this->internal_function;
    }

    /**
     * @return int
     */
    public function getFunction(): int
    {

        [$bank, $mask, $shift] = $this->getAddressMask(3);
        $function = ($this->gpio_register[Register\GPIO::$GPFSEL[$bank]] & $mask) >> $shift;

        $this->setInternalFunction($function);

        return $function;
    }

    /**
     * @return string|null
     */
    public function getFunctionName(): ?string
    {

        $function = $this->getFunction();
        if ($function === PinInterface::INPUT) {
            return 'in';
        }
        if ($function === PinInterface::OUTPUT) {
            return 'out';
        }

        $matrix = $this->board->getPinFunctionMatrix();

        //Return null, not false
        return array_search($function, $matrix[$this->pin_number], false) ?: null;
    }

    /**
     * @param $function
     * @return mixed
     * @throws InvalidPinFunctionException
     */
    public function getAltCodeForPinFunction($function)
    {

        $matrix = $this->board->getPinFunctionMatrix();

        if (isset($matrix[$this->pin_number][$function])) {
            return $matrix[$this->pin_number][$function];
        }

        throw new InvalidPinFunctionException(sprintf('Pin %s does not support [%s]', $this->pin_number, $function));
    }

    /**
     * Get the alternative functions for this pin
     *
     * @return array
     */
    public function getAltFunctions(): array
    {
        $matrix = $this->board->getPinFunctionMatrix();

        return $matrix[$this->pin_number];
    }


    /**
     * Function to check that a pin is in a particular mode before an action is attempted.
     *
     * @param array $valid_functions
     * @return bool
     * @throws InvalidPinFunctionException
     */
    public function assertFunction(array $valid_functions): bool
    {
        if (!\in_array($this->getFunction(), $valid_functions, true)) {
            throw new InvalidPinFunctionException(
                sprintf(
                    'Pin %s is set to invalid function (%s) for ->%s(). Supported functions are [%s]',
                    $this->pin_number,
                    $this->getFunction(),
                    debug_backtrace()[1]['function'],
                    implode(',', $valid_functions)
                )
            );
        }
        return true;
    }


    /**
     * Raw pin transition with fast mode, fast mode ignores all checks and event emitters
     *
     * @param bool $fast_mode
     * @return $this
     * @throws InvalidPinFunctionException
     */
    public function high($fast_mode = false): self
    {

        if (!$fast_mode) {
            $this->assertFunction([PinInterface::OUTPUT]);
            $this->setInternalLevel(self::LEVEL_HIGH);
        }

        [$bank, $mask] = $this->getAddressMask();
        $this->gpio_register[Register\GPIO::$GPSET[$bank]] = $mask;

        return $this;
    }

    /**
     * Raw pin transition with fast mode, fast mode ignores all checks and event emitters
     *
     * @param bool $fast_mode
     * @return $this
     * @throws InvalidPinFunctionException
     */
    public function low($fast_mode = false): self
    {

        if (!$fast_mode) {
            $this->assertFunction([PinInterface::OUTPUT]);
            $this->setInternalLevel(self::LEVEL_LOW);
        }

        [$bank, $mask] = $this->getAddressMask();
        $this->gpio_register[Register\GPIO::$GPCLR[$bank]] = $mask;

        return $this;
    }

    /**
     * hasInternalLevel
     * @return bool
     */
    protected function hasInternalLevel(): bool
    {
        return null !== $this->internal_level;
    }

    public function getInternalLevel(): ?Pin
    {
        return $this->internal_level;
    }

    /**
     *
     * @param $level
     * @return mixed
     */
    public function setInternalLevel($level)
    {
        if ($this->hasInternalLevel()) {
            if ($this->internal_level !== $level) {
                //This has to be done so you don't get recursion if you access ->getFunction from within the event.
                $this->internal_level = $level;

                $this->emit(self::EVENT_LEVEL_CHANGE, [$level]);
            }
            //If it's set and the same, just leave it.
        } else {
            //Otherwise, if it's not set, set it.
            $this->internal_level = $level;
        }

        return $this->internal_level;
    }

    /**
     * Inverts the internal level to emit events.
     *
     * Designed for the edge detector to hit when it detects a change.
     */
    public function invertInternalLevel(): void
    {
        $this->setInternalLevel($this->internal_level === self::LEVEL_HIGH ? self::LEVEL_LOW : self::LEVEL_HIGH);
    }


    /**
     * Read the actual pin level from the register
     *
     * @return mixed
     */
    public function getLevel()
    {
        //Can actually be any state
        //$this->assertFunction([PinInterface::INPUT, PinInterface::OUTPUT]);

        [$bank, $mask, $shift] = $this->getAddressMask();
        //Record observed level and return
        $level = ($this->gpio_register[Register\GPIO::$GPLEV[$bank]] & $mask) >> $shift;

        $this->setInternalLevel($level);

        return $level;
    }


    /**
     * @param $direction
     * @return $this
     * @throws InvalidPinFunctionException
     */
    public function setPull($direction): self
    {
        $this->assertFunction([PinInterface::INPUT]);
        $this->pull = $direction;

        [$bank, $mask] = $this->getAddressMask();
        $this->gpio_register[Register\GPIO::GPPUD] = $this->pull;
        usleep(5); //How long are 150 cycles?
        $this->gpio_register[Register\GPIO::$GPPUDCLK[$bank]] = $mask;
        usleep(5);
        $this->gpio_register[Register\GPIO::$GPPUDCLK[$bank]] = 0;

        return $this;
    }

    public function getPull(): int
    {
        return $this->pull;
    }


    /**
     * Function to return the bit mask and shift values for a particular number of bits on the current pin.
     *
     * Seems to be a slight gain caching it as a couple of the operations are expensive.
     *
     * @param int $bits
     * @return array[$bank, $mask, $shift]
     */
    public function getAddressMask($bits = 1): array
    {

        if (!isset($this->mask_cache[$bits])) {
            $divisor = floor(32 / $bits);
            $bank = $this->pin_number / $divisor;
            $shift = ($this->pin_number % $divisor) * $bits;
            $mask = (1 << $bits) - 1 << $shift;

            $this->mask_cache[$bits] = [$bank, $mask, $shift];
        }

        return $this->mask_cache[$bits];
    }


    public function getPinNumber(): int
    {
        return $this->pin_number;
    }

    public function getBoard(): Board
    {
        return $this->board;
    }


    /**
     * Internal function to fire the appropriate events when the pin level changes
     *
     * @param $level
     */
    public function onPinChangeEvent($level): void
    {
        //Mainly just connecting up events here
        if ($level === self::LEVEL_HIGH) {
            $this->emit(self::EVENT_LEVEL_HIGH);
        } elseif ($level === self::LEVEL_LOW) {
            $this->emit(self::EVENT_LEVEL_LOW);
        }
    }

    //Meta events for setting up polling
    public function eventListenerAdded(): void
    {
        //If it's the first event, chain listeners and add to edge detect
        if ($this->countListeners() === 1) {
            $this->on(self::EVENT_LEVEL_CHANGE, [$this, 'onPinChangeEvent']);
            $this->board->getEdgeDetector()->addPin($this);
        }
    }


    public function eventListenerRemoved(): void
    {
        //If it's the last event, there's the internal one for change->high/low so this is 1
        if ($this->countListeners() === 1) {
            $this->board->getEdgeDetector()->removePin($this);
            $this->removeAllListeners();
        }
    }


}