<?php

/**
 * @package    phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\External\Generic\Motor;

use Calcinai\PHPi\External\Generic\MotorInterface;
use Calcinai\PHPi\Pin;

class HBridge implements MotorInterface
{

    /**
     * @var Pin
     */
    private $pin_a;
    /**
     * @var Pin
     */
    private $pin_b;


    /**
     * HBridge constructor.
     * @param Pin $pin_a
     * @param Pin $pin_b
     * @throws \Calcinai\PHPi\Exception\InvalidPinFunctionException
     */
    public function __construct(Pin $pin_a, Pin $pin_b)
    {

        $this->pin_a = $pin_a->setFunction(Pin\PinInterface::OUTPUT);
        $this->pin_b = $pin_b->setFunction(Pin\PinInterface::OUTPUT);

        $this->stop();
    }

    /**
     * stop
     * @throws \Calcinai\PHPi\Exception\InvalidPinFunctionException
     */
    public function stop(): void
    {
        $this->pin_a->low();
        $this->pin_b->low();
    }

    /**
     * forward
     * @throws \Calcinai\PHPi\Exception\InvalidPinFunctionException
     */
    public function forward(): void
    {
        $this->pin_a->high();
        $this->pin_b->low();
    }

    /**
     * reverse
     * @throws \Calcinai\PHPi\Exception\InvalidPinFunctionException
     */
    public function reverse(): void
    {
        $this->pin_a->low();
        $this->pin_b->high();
    }

}