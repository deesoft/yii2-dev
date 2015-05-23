<?php

namespace dee\composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

/**
 * Plugin is the composer plugin that registers the Yii composer installer.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Plugin implements PluginInterface
{

    /**
     * @inheritdoc
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = new Installer();
        $composer->getEventDispatcher()->addSubscriber($installer);
    }

}