<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Board\V1;

use Calcinai\PHPi\Board;
use Calcinai\PHPi\Board\Feature;

class B extends Board
{

    use Feature\SoC\BCM2835;
    use Feature\HDMI;
    use Feature\Ethernet;
    use Feature\Header\P1; //need to sort out pinmap for rev0 gpio 0&1 not 2&3

    public static function getBoardName()
    {
        return '1 Model B';
    }
}