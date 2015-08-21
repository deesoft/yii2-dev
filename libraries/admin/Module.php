<?php

namespace dee\admin;

use Yii;

/**
 * Description of Module
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Module extends \yii\base\Module implements \yii\base\BootstrapInterface
{
    public $userClassName;
    public $idField = 'id';
    public $usernameField = 'username';

    public $layout = 'main';
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->userClassName === null) {
            $this->userClassName = Yii::$app->getUser()->identityClass;
            $this->userClassName = $this->userClassName ? : 'common\models\User';
        }
        if (!isset(Yii::$app->i18n->translations['rbac-admin'])) {
            Yii::$app->i18n->translations['rbac-admin'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en',
                'basePath' => '@mdm/admin/messages'
            ];
        }
    }

    /**
     * @param \yii\web\Application $app
     */
    public function bootstrap($app)
    {
        $urlManager = $app->getUrlManager();
        $urlManager->enablePrettyUrl = true;
        $id = $this->uniqueId;
        $urlManager->addRules([
            $id => $id . '/default/index',
            // assignment
            'GET ' . $id . '/assignment' => $id . '/assignment/index',
            'GET ' . $id . '/assignment/<id>' => $id . '/assignment/view',
            'POST ' . $id . '/assignment/assign/<id>' => $id . '/assignment/assign',
            'POST ' . $id . '/assignment/revoke/<id>' => $id . '/assignment/revoke',
            // item
            'GET ' . $id . '/item' => $id . '/item/index',
            'GET ' . $id . '/item/<id>' => $id . '/item/view',
            'POST ' . $id . '/item/assign/<id>' => $id . '/item/add-child',
            'POST ' . $id . '/item/revoke/<id>' => $id . '/item/remove-child',
            'PUT ' . $id . '/item/<id>' => $id . '/item/update',
            'POST ' . $id . '/item' => $id . '/item/create',
            'DELETE ' . $id . '/item/<id>' => $id . '/item/delete',
            // rule
            'GET ' . $id . '/rule' => $id . '/rule/index',
            'GET ' . $id . '/rule/<id>' => $id . '/rule/view',
            'POST ' . $id . '/rule/<id>' => $id . '/rule/update',
            'POST ' . $id . '/rule' => $id . '/rule/create',
            'DELETE ' . $id . '/rule/<id>' => $id . '/rule/delete',
            // route
            'GET ' . $id . '/route' => $id . '/route/index',
            'POST ' . $id . '/route' => $id . '/route/add',
            'DELETE ' . $id . '/route' => $id . '/route/remove',
            ], false);
    }
}