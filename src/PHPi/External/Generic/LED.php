<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\External\Generic;

use Calcinai\PHPi\External\Output;
use Calcinai\PHPi\Pin;

class LED extends Output
{
    /**
     * flash
     * @param null $iterations
     * @param int $interval
     * @param float $duty
     * @throws \Calcinai\PHPi\Exception\InvalidPinFunctionException
     */
    public function flash($iterations = null, $interval = 1, $duty = 0.5): void
    {
        parent::pulse($iterations, $interval, $duty);
    }

}