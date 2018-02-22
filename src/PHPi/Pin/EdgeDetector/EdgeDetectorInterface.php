<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Pin\EdgeDetector;

use Calcinai\PHPi\Pin;

interface EdgeDetectorInterface
{
    public const EDGE_NONE = 'none';
    public const EDGE_RISING = 'rising';
    public const EDGE_FALLING = 'falling';
    public const EDGE_BOTH = 'both';

    public function addPin(Pin $pin): void;

    public function removePin(Pin $pin): void;
}