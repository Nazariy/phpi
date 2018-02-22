<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Board\V1;

use Calcinai\PHPi\Board;
use Calcinai\PHPi\Board\Feature;

class A extends Board
{
    public const NAME = '1 Model A';
    use Feature\SoC\BCM2835;
    use Feature\HDMI;
    use Feature\Header\P1;
}