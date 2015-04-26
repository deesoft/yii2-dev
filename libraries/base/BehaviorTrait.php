<?php

namespace dee\base;

use yii\base\Component;

/**
 * Description of HandlerTrait
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
trait BehaviorTrait
{
    /**
     * @var Component
     */
    private $_owner;

    /**
     * @param Component $owner
     */
    public function attach($owner)
    {
        $this->_owner = $owner;
        foreach ($this->events() as $event => $handler) {
            $owner->on($event, is_string($handler) ? [$this, $handler] : $handler, null, false);
        }
    }

    /**
     * @param Component $owner
     */
    public function detach()
    {
        if ($this->_owner !== null) {
            foreach ($this->events() as $event => $handler) {
                $this->_owner->off($event, is_string($handler) ? [$this, $handler] : $handler);
            }
            $this->_owner = null;
        }
    }

    /**
     * @return array
     */
    public function events()
    {
        return [];
    }
}