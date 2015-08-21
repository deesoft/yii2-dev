<?php

namespace dee\admin\models;

use Yii;
use yii\rbac\Item;
use yii\helpers\ArrayHelper;

/**
 * Description of User
 *
 * @property integer $id
 * @property string $username
 * @property Item[] $assignments
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 3.0
 */
class User extends \yii\db\ActiveRecord
{
    private static $_module;
    private static $_items;
    private $_assignment;

    /**
     * @return \dee\admin\Module
     */
    private static function module()
    {
        if (self::$_module === null) {
            self::$_module = Yii::$app->controller->module;
        }
        return self::$_module;
    }

    public function getAssignments()
    {
        $manager = Yii::$app->getAuthManager();
        if (self::$_items === null) {
            self::$_items = array_merge($manager->getRoles(), $manager->getPermissions());
            self::$_items = array_filter(self::$_items, function($item) {
                return $item->name[0] !== '/';
            });
        }
        if ($this->_assignment === null) {
            $this->_assignment = [];
            foreach ($manager->getAssignments($this->id) as $item) {
                $this->_assignment[] = self::$_items[$item->roleName];
            }
            ArrayHelper::multisort($this->_assignment, ['type','name']);
        }
        return $this->_assignment;
    }

    public function getAvaliables()
    {
        $assignments = $this->getAssignments();
        $items = self::$_items;
        foreach ($assignments as $item) {
            unset($items[$item->name]);
        }
        return array_values($items);
    }

    public function getId()
    {
        return $this[self::module()->idField];
    }

    public function getUsername()
    {
        return $this[self::module()->usernameField];
    }

    public static function tableName()
    {
        $class = self::module()->userClassName;
        return $class::tableName();
    }

    public function fields()
    {
        return[
            'id',
            'username',
        ];
    }

    public function extraFields()
    {
        return[
            'assignments',
            'avaliables',
        ];
    }
}