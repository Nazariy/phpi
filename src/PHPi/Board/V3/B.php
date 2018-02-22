<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Board\V3;

use Calcinai\PHPi\Board;
use Calcinai\PHPi\Board\Feature;

class B extends Board
{
    public const NAME = '3 Model B';
    use Feature\SoC\BCM2837;
    use Feature\HDMI;
    use Feature\Ethernet;
    use Feature\Header\J8;
}