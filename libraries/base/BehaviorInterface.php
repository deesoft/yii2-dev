<?php

namespace dee\base;

use yii\base\Component;

/**
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
interface BehaviorInterface
{

    /**
     * @return array
     */
    public function events();

    /**
     * Attaches the behavior object to the component.
     * The default implementation will set the [[owner]] property
     * and attach event handlers as declared in [[events]].
     * Make sure you call the parent implementation if you override this method.
     * @param Component $owner the component that this behavior is to be attached to.
     */
    public function attach($owner);

    /**
     * Detaches the behavior object from the component.
     * The default implementation will unset the [[owner]] property
     * and detach event handlers declared in [[events]].
     * Make sure you call the parent implementation if you override this method.
     */
    public function detach();
}