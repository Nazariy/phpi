<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\External\Generic;

use Calcinai\PHPi\External\Input;
use Calcinai\PHPi\Pin;

class Button extends Input
{

    /**
     * @var bool
     */
    private $active_high;

    /**
     * @var float
     */
    private $press_period;

    /**
     * @var int
     */
    private $hold_period;


    public const DEFAULT_HOLD_PERIOD = 1;
    public const DEFAULT_PRESS_PERIOD = 0.05;

    /**
     * Period (in seconds) to ignore subsequent press events
     */
    public const DEFAULT_DEBOUNCE_PERIOD = 0.25;

    public const EVENT_PRESS = 'press';
    public const EVENT_HOLD = 'hold';
    public const EVENT_RELEASE = 'release';
    /**
     * @var float
     */
    private $debounce_period;


    /**
     * Button constructor.
     * @param Pin $pin
     * @param bool $active_high
     * @param float $press_period
     * @param int $hold_period
     * @param float $debounce_period
     * @throws \Calcinai\PHPi\Exception\InvalidPinFunctionException
     */
    public function __construct(
        Pin $pin,
        $active_high = true,
        $press_period = self::DEFAULT_PRESS_PERIOD,
        $hold_period = self::DEFAULT_HOLD_PERIOD,
        $debounce_period = self::DEFAULT_DEBOUNCE_PERIOD
    ) {
        parent::__construct($pin);

        $this->press_period = $press_period;
        $this->hold_period = $hold_period;
        $this->active_high = $active_high;
        $this->debounce_period = $debounce_period;
    }


    /**
     * Function to setup the listerner on pin change.  There is a 'once' listener because it needs to be removed and
     * re-added for debounce.  This could also be changed to have some 'debouncing' flag etc.
     */
    private function registerPressEvent(): void
    {
        //Do it like this so it can be hidden from userspace
        $press_event = $this->active_high ? Pin::EVENT_LEVEL_HIGH : Pin::EVENT_LEVEL_LOW;
        $this->pin->once($press_event, function () {
            $this->onPinPressEvent();

            //Re-add the press event after the debounce period
            $this->pin->getBoard()->getLoop()->addTimer($this->debounce_period, function () {
                $this->registerPressEvent();
            });
        });

    }

    /**
     *
     * Internal function for dealing with a press (high or low) event on the pin
     */
    private function onPinPressEvent(): void
    {

        //Mainly just connecting up events here

        $press_timer = $this->pin->getBoard()->getLoop()->addTimer($this->press_period, function () {
            $this->emit(self::EVENT_PRESS);
        });

        $hold_timer = $this->pin->getBoard()->getLoop()->addTimer($this->hold_period, function () {
            $this->emit(self::EVENT_HOLD);
        });


        $release_event = $this->active_high ? Pin::EVENT_LEVEL_LOW : Pin::EVENT_LEVEL_HIGH;

        $this->pin->once($release_event, function () use (&$press_timer, &$hold_timer) {
            $press_timer->cancel();
            $hold_timer->cancel();

            $this->emit(self::EVENT_RELEASE);
        });
    }

    /**
     * eventListenerAdded
     * @param $event_name
     */
    public function eventListenerAdded($event_name): void
    {
        //Only interested in the first event added, no advantage to only firing the onces that are being listened to
        if ($this->countListeners() !== 1) {
            return;
        }

        $this->registerPressEvent();
    }
}