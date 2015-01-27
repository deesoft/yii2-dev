<?php

namespace dee\composer;

use Composer\Script\ScriptEvents;
use Composer\Script\CommandEvent;

/**
 * Subscriber
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Subscriber implements \Composer\EventDispatcher\EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return array(
            ScriptEvents::POST_INSTALL_CMD => 'apply',
            ScriptEvents::POST_UPDATE_CMD => 'apply'
        );
    }
    
    public function apply(CommandEvent $event)
    {
        Installer::apply($event);
    }
}