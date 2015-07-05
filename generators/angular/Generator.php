<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace dee\gii\generators\angular;

use Yii;
use yii\web\Controller;
use yii\gii\CodeFile;
use yii\db\BaseActiveRecord;

/**
 * Generates CRUD
 *
 * @property string $controllerClass The controller class to be generated. This property is
 * read-only.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 */
class Generator extends \dee\gii\generators\crud\Generator
{
    public $alsoAsRest = true;

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Angular CRUD Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates a controller and views that implement CRUD (Create, Read, Update, Delete)
            operations for the specified data model.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['alsoAsRest'], 'boolean'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'controllerID' => 'Controller ID',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'alsoAsRest' => 'When <code>true</code> then controller is also generated as REST',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $controllerFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->getControllerClass(), '\\')) . '.php');

        $files = [
            new CodeFile($controllerFile, $this->render('controller.php')),
        ];

        $viewPath = $this->getViewPath();
        $templatePath = $this->getTemplatePath() . '/views';
        foreach (scandir($templatePath) as $file) {
            if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $files[] = new CodeFile("$viewPath/$file", $this->render("views/$file"));
            }
        }
        $templatePath = $this->getTemplatePath() . '/views/js';
        foreach (scandir($templatePath) as $file) {
            if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $jsFile = substr($file, 0, -4);
                $files[] = new CodeFile("$viewPath/js/$jsFile", $this->render("views/js/$file"));
            }
        }

        return $files;
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['alsoAsRest']);
    }
}