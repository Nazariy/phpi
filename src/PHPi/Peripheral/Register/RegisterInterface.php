<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral\Register;

/**
 * Interface RegisterInterface
 * @package Calcinai\PHPi\Peripheral\Register
 */
interface RegisterInterface
{
    public static function getOffset(): int;
}