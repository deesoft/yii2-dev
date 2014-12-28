<?php
namespace mdm\composer;
/**
 * Configure
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Installer extends \yii\composer\Installer
{

    public static function apply($event)
    {
        $composer = $event->getComposer();
        $installer = new static($event->getIO(), $composer);
        $installer->addPackage($composer->getPackage());
    }
}