<?php
/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Pin;

use Calcinai\PHPi\Pin;

class SysFS
{

    public const PATH_BASE = '/sys/class/gpio';

    public static $unexport_on_cleanup = [];

    /**
     * exportPin
     * @static
     * @param Pin $pin
     * @return bool
     */
    public static function exportPin(Pin $pin): bool
    {

        if (self::isExported($pin)) {
            return false;
        }

        return (bool)file_put_contents(sprintf('%s/export', self::PATH_BASE), $pin->getPinNumber());
    }

    /**
     * unexportPin
     * @static
     * @param Pin $pin
     * @return bool
     */
    public static function unexportPin(Pin $pin): bool
    {

        if (!self::isExported($pin)) {
            return false;
        }

        return (bool)file_put_contents(sprintf('%s/unexport', self::PATH_BASE), $pin->getPinNumber());
    }

    /**
     * getPinValue
     * @static
     * @param Pin $pin
     * @return bool|string
     */
    public static function getPinValue(Pin $pin)
    {
        if (!self::isExported($pin)) {
            return false;
        }
        return file_get_contents(sprintf('%s/gpio%s/value', self::PATH_BASE, $pin->getPinNumber()));
    }

    /**
     * setEdge
     * @static
     * @param Pin $pin
     * @param string $edge
     * @return bool
     */
    public static function setEdge(Pin $pin, string $edge): bool
    {
        if (!self::isExported($pin)) {
            return false;
        }
        return (bool)file_put_contents(sprintf('%s/gpio%s/edge', self::PATH_BASE, $pin->getPinNumber()), $edge);
    }

    /**
     * isExported
     * @static
     * @param Pin $pin
     * @return bool
     */
    public static function isExported(Pin $pin): bool
    {
        return file_exists(sprintf('%s/gpio%s', self::PATH_BASE, $pin->getPinNumber()));
    }

    /**
     * cleanup
     * @static
     */
    public static function cleanup(): void
    {
        while ($pin = array_pop(static::$unexport_on_cleanup)) {
            self::unexportPin($pin);
        }
    }
}