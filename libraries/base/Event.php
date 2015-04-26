<?php

namespace dee\base;

/**
 * Description of Event
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>  
 * @since 1.0
 */
class Event extends \yii\base\Event
{
    public $params;

    public function __construct($params = [], $config = [])
    {
        $this->params = $params;
        parent::__construct($config);
    }
}