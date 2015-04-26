<?php

namespace dee\base;

/**
 * Description of SelfHandled
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
trait SelfHandled
{

    use BehaviorTrait;
    
    /**
     * @var boolean 
     */
    private $_attached;

    public function ensureBehaviors()
    {
        if (!$this->_attached) {
            $this->_attached = true;
            $this->attach($this);
            parent::ensureBehaviors();
        }
    }
}