<?php
/**
 * @package    phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\External;

use Calcinai\PHPi\Pin;
use React\EventLoop\Timer\TimerInterface;

class Output
{

    /**
     * @var Pin
     */
    protected $pin;

    /**
     * @var bool
     */
    protected $active_high;

    /**
     * Output constructor.
     * @param Pin $pin
     * @param bool $active_high
     * @throws \Calcinai\PHPi\Exception\InvalidPinFunctionException
     */
    public function __construct(Pin $pin, $active_high = true)
    {
        $this->pin = $pin;
        $this->active_high = $active_high;

        $pin->setFunction(Pin\PinInterface::OUTPUT);
    }


    /**
     * on
     * @throws \Calcinai\PHPi\Exception\InvalidPinFunctionException
     */
    public function on(): void
    {
        $this->active_high ? $this->pin->high() : $this->pin->low();
    }

    /**
     * off
     * @throws \Calcinai\PHPi\Exception\InvalidPinFunctionException
     */
    public function off(): void
    {
        $this->active_high ? $this->pin->low() : $this->pin->high();
    }

    /**
     * toggle
     * @throws \Calcinai\PHPi\Exception\InvalidPinFunctionException
     */
    public function toggle(): void
    {
        //This will still work if active low
        $this->pin->getLevel() === Pin::LEVEL_HIGH ? $this->pin->low() : $this->pin->high();
    }

    /**
     * pulse
     * @param null $iterations
     * @param int $interval
     * @param float $duty
     * @throws \Calcinai\PHPi\Exception\InvalidPinFunctionException
     */
    public function pulse($iterations = null, $interval = 1, $duty = 0.5): void
    {

        $this->on();

        $on_time = $interval * $duty;

        $this->pin->getBoard()
            ->getLoop()->addTimer($on_time, [$this, 'off'])
            ->getLoop()->addPeriodicTimer($interval, function (TimerInterface $timer) use (&$iterations, $on_time) {

                if ($iterations !== null && --$iterations === 0) {
                    $timer->cancel();
                    return;
                }

                $this->on();

                $timer->getLoop()->addTimer($on_time, [$this, 'off']);
            });

    }

}