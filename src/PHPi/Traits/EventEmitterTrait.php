<?php

/**
 * @package    calcinai/phpi
 * @author     Michael Calcinai <michael@calcin.ai>
 *
 * This is based on the Evenement\EventEmitterTrait, but with some more meta functionality since it was getting very messy
 * overloading the trait.
 *
 */

namespace Calcinai\PHPi\Traits;

trait EventEmitterTrait
{

    /**
     * @var array
     */
    protected $listeners;

    /**
     * on
     * @param string $event
     * @param callable $listener
     */
    public function on(string $event, callable $listener): void
    {
        if (!isset($this->listeners[$event])) {
            $this->listeners[$event] = [];
        }

        $this->listeners[$event][] = $listener;
        $this->eventListenerAdded($event);
    }

    /**
     * once
     * @param string $event
     * @param callable $listener
     */
    public function once(string $event, callable $listener): void
    {
        $onceListener = function () use (&$onceListener, $event, $listener) {
            $this->removeListener($event, $onceListener);

            \call_user_func_array($listener, \func_get_args());
        };

        $this->on($event, $onceListener);
    }

    public function removeListener(string $event, callable $listener): void
    {
        if (
            isset($this->listeners[$event]) &&
            false !== ($index = array_search($listener, $this->listeners[$event], true))
        ) {
            unset($this->listeners[$event][$index]);
            $this->eventListenerRemoved($event);
        }
    }

    public function removeAllListeners(string $event = null): void
    {
        if ($event !== null) {
            foreach ($this->listeners($event) as $listener) {
                $this->removeListener($event, $listener);
            }
        } else {
            foreach (array_keys($this->listeners) as $e) {
                $this->removeAllListeners($e);
            }
        }
    }

    /**
     * listeners
     * @param string $event
     * @return array
     */
    public function listeners(string $event): array
    {
        return $this->listeners[$event] ?? [];
    }

    /**
     * countListeners
     * @param string $event
     * @return int|null
     */
    public function countListeners(string $event = null): ?int
    {
        if ($event !== null) {
            return \count($this->listeners[$event]);
        }
        $num_listeners = 0;
        foreach (array_keys($this->listeners) as $e) {
            $num_listeners += \count($this->listeners[$e]);
        }
        return $num_listeners;

    }

    /**
     * emit
     * @param $event
     * @param array $arguments
     */
    public function emit(string $event, array $arguments = []): void
    {
        foreach ($this->listeners($event) as $listener) {
            \call_user_func_array($listener, $arguments);
        }
    }

    /**
     * Made these two functions so they don't collide with the events they're describing.
     * Also was getting pretty ugly having no constants to represent the event.
     *
     * @param $event_name
     */
    public function eventListenerAdded($event_name): void
    {
    }

    /**
     * @param $event_name
     */
    public function eventListenerRemoved($event_name): void
    {
    }
}