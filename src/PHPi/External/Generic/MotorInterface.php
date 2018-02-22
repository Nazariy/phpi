<?php
/**
 * @package    phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\External\Generic;


interface MotorInterface
{

    public const DIRECTION_FORWARD = 'forward';
    public const DIRECTION_REVERSE = 'reverse';

    public function forward();

    public function reverse();

    public function stop();
}