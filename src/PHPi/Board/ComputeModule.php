<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Board;

use Calcinai\PHPi\Board;
use Calcinai\PHPi\Board\Feature;

class ComputeModule extends Board
{
    public const NAME = 'Compute Module';

    use Feature\SoC\BCM2835;
}