<?php
/**
 * @package    api
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\PHPi\Peripheral\Register;

use Calcinai\PHPi\Board\BoardInterface;

/**
 * Register to store and return data, so testing can be done on non-pi systems
 *
 * Class Mock
 * @package Calcinai\PHPi\Peripheral\Register
 */
class Mock extends AbstractRegister
{
    private $data;
    /**
     * @var BoardInterface
     */
    private $board;
    /** @noinspection MagicMethodsValidityInspection */
    /** @noinspection PhpMissingParentConstructorInspection */
    /**
     * Overload the parent constructor so nothing is actually mapped
     *
     * AbstractRegister constructor.
     * @param BoardInterface $board
     */
    public function __construct(BoardInterface $board)
    {
        $this->data = [];
        $this->board = $board;
    }

    /**
     * @param mixed $offset
     * @return bool
     */

    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset] ?? 0;
    }

    public function offsetSet($offset, $value):void
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset):void
    {

    }

    public static function getOffset():int
    {
        return 0;
    }
}