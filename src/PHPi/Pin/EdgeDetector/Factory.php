<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Pin\EdgeDetector;

use Calcinai\PHPi\Board;

class Factory
{

    /**
     * create
     * @static
     * @param Board $board
     * @return Rubberneck|StatusPoll
     * @throws \Calcinai\PHPi\Exception\InternalFailureException
     */
    public static function create(Board $board)
    {
        //It isn't working correctly yet.
//        if (false && Rubberneck::isSuitable()) {
//            return new Rubberneck($board);
//        }
        return new StatusPoll($board);

    }
}