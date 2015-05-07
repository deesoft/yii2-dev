<?php

namespace dee\base;

use Yii;

/**
 * Description of ComponentTrait
 * 
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
trait GlobalTriggerTrait
{
    /**
     * @var string 
     */
    public $prefixEventName;

    /**
     * @var string
     */
    protected $prefixEventHandler = 'ee';

    /**
     * Trigger global event
     * @param string $name
     * @param array $params
     */
    public function fire($name, $params = [])
    {
        if ($this->prefixEventName === null) {
            $reflector = new \ReflectionClass($this);
            $this->prefixEventName = 'e' . $reflector->getShortName();
        }
        
        $event = new Event($params);
        if (method_exists($this, $this->prefixEventHandler . $name)) {
            call_user_func([$this, $this->prefixEventHandler . $name], $event);
        }
        Yii::$app->trigger($this->prefixEventName . ucfirst($name), $event);
    }
}