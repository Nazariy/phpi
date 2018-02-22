<?php
/**
 * @package    phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\External;


use Calcinai\PHPi\Pin;
use Calcinai\PHPi\Traits\EventEmitterTrait;

class Input
{
    use EventEmitterTrait;

    /**
     * @var Pin
     */
    protected $pin;

    /**
     * Input constructor.
     * @param Pin $pin
     * @throws \Calcinai\PHPi\Exception\InvalidPinFunctionException
     */
    public function __construct(Pin $pin)
    {
        $this->pin = $pin;

        $pin->setFunction(Pin\PinInterface::INPUT);
    }


    /**
     * eventListenerRemoved
     */
    public function eventListenerRemoved(): void
    {
        //Only interested in the last event removed
        if ($this->countListeners() !== 0) {
            return;
        }
        $this->pin->removeAllListeners();
    }


}