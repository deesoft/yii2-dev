<?php

namespace dee\angular;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Inflector;
use yii\web\View as WebView;
use yii\base\Widget;
use yii\helpers\FileHelper;
use yii\web\AssetBundle;
use yii\web\JsExpression;

/**
 * Description of UiView
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class UiView extends Widget
{
    /**
     *
     * @var array
     */
    public $options = [];

    /**
     *
     * @var array
     */
    public $states = [];

    /**
     *
     * @var array
     */
    public $templates = [];

    /**
     *
     * @var string
     */
    public $name = 'dApp';

    public $ngApp = true;

    /**
     * @var array
     */
    public $requires = [];

    /**
     * @var string
     */
    public $tag = 'div';

    /**
     *
     * @var string
     */
    public $js;

    /**
     *
     * @var string
     */
    private $_controller;

    /**
     * @var array
     */
    public $clientOptions;

    /**
     * @var array
     */
    public $injection = ['$scope', '$injector'];

    /**
     * @var boolean
     */
    public $remote;
    private $_varName;

    /**
     * @var static
     */
    public static $instance;
    private $_templates = [];
    private $_controllers = [];
    private $_services = [];
    private $_injections = [];
    private $_stateProviders = [];
    private $_parentStates = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        static::$instance = $this;
        $this->_varName = Inflector::variablize($this->name);

        $this->requires[] = 'ui.router';
        $this->requires = array_unique($this->requires);

        $view = $this->getView();
        // Asset Dependency
        $am = Yii::$app->getAssetManager();
        $key = md5(serialize([__CLASS__, Yii::$app->controller->route, $this->name]));
        $bundle = [
            'basePath' => '',
            'depends' => [AngularAsset::className()],
            'js' => [],
        ];
        foreach ($this->requires as $module) {
            if (isset(AngularAsset::$assetMap[$module])) {
                $bundle['depends'][] = AngularAsset::$assetMap[$module];
            }
        }
        $js = $this->generate();
        if ($this->remote) {
            $path = sprintf('%x', crc32($key));
            $jsName = Inflector::camel2id($this->name) . '.js';
            $bundle['js'][] = $jsName;
            $bundle['basePath'] = $am->basePath . '/' . $path;
            $bundle['baseUrl'] = $am->baseUrl . '/' . $path;
            FileHelper::createDirectory(Yii::getAlias($bundle['basePath']));
            file_put_contents(Yii::getAlias($bundle['basePath'] . '/' . $jsName), $js);
        } else {
            $view->registerJs($js, WebView::POS_END);
        }
        $am->bundles[$key] = new AssetBundle($bundle);
        $view->registerAssetBundle($key);

        $options = $this->options;
        if ($this->tag !== 'ui-view' && !isset($options['ui-view'])) {
            $options['ui-view'] = true;
        }
        if($this->ngApp && !isset($options['ng-app'])){
            $options['ng-app'] = $this->name;
        }
        echo Html::beginTag($this->tag, $options);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        static::$instance = null;
        echo Html::endTag($this->tag);
    }

    public function getController()
    {
        return $this->_controller;
    }

    protected function generate()
    {
        $this->states([
            'injection' => $this->injection,
            'states' => $this->states,
            'templates' => $this->templates,
            'js'=>  $this->js,
        ]);

        $js = [];
        $js[] = "{$this->_varName} = (function(options){";
        $js[] = $this->renderModule();
        $js[] = implode("\n", $this->_services);
        $js[] = $this->renderTemplates();
        $js[] = $this->renderStateProviders();
        $js[] = $this->renderControllers();

        $options = empty($this->clientOptions) ? '{}' : Json::encode($this->clientOptions);
        $js[] = "\nreturn module;\n})({$options});";

        return implode("\n\n", $js);
    }

    public function states($configs)
    {
        $view = $this->getView();
        if (isset($configs['injection'])) {
            $this->_injections[] = (array) $configs['injection'];
        }
        if (isset($configs['js'])) {
            foreach ((array) $configs['js'] as $file) {
                $this->_services[] = Helper::parseBlockJs($view->render($file));
            }
        }

        $prefix = end($this->_parentStates) ? : '';
        if (!empty($configs['templates'])) {
            foreach ($configs['templates'] as $name => $config) {
                $name = trim($prefix . '.' . $name, '.');
                $this->applyTemplate($name, $config);
                $this->_templates[$name] = $config;
            }
        }
        if (!empty($configs['states'])) {
            foreach ($configs['states'] as $name => $config) {
                if (is_int($name)) {
                    $view->render($config, ['widget' => $this]);
                    continue;
                }
                $name = trim($prefix . '.' . $name, '. ');
                if (is_string($config)) {
                    if (strncmp($config, 'js:', 3) === 0) {
                        $config = substr($config, 3);
                        $this->_stateProviders[] = ".state({$name},{$config})";
                    } else {
                        $this->_parentStates[] = $name;
                        $view->render($config, ['widget' => $this]);
                        array_pop($this->_parentStates);
                    }
                } else {
                    $this->applyTemplate($name, $config);
                    $name = json_encode($name);
                    $config = Json::encode($config);
                    $this->_stateProviders[] = ".state({$name},{$config})";
                }
            }
        }
        if (isset($configs['injection'])) {
            array_pop($this->_injections);
        }
    }

    protected function applyTemplate($name, &$config)
    {
        $view = $this->getView();
        if (isset($config['js'])) {
            $injection = end($this->_injections);
            if ($injection === false) {
                $injection = $this->injection;
            }
            if (empty($config['controller'])) {
                $config['controller'] = Inflector::camelize($name) . 'Controller';
            }
            $this->_controller = $config['controller'];
            $injection = array_unique(array_merge($injection, ArrayHelper::remove($config, 'injection', [])));
            $this->_controllers[$this->_controller]['injection'] = $injection;
            $this->_controllers[$this->_controller]['js'][] = Helper::parseBlockJs($view->render($config['js'], ['widget' => $this]));
            unset($config['js']);
        } elseif (isset($config['controller'])) {
            $this->_controller = $config['controller'];
        }

        if (isset($config['view'])) {
            $config['template'] = $view->render($config['view'], ['widget' => $this]);
            unset($config['view']);
        }

        if (isset($config['resolve'])) {
            if (is_string($config['resolve']) && strncmp($config['resolve'], 'js:', 3) === 0) {
                $config['resolve'] = new JsExpression(substr($config['resolve'], 3));
            } elseif (is_array($config['resolve'])) {
                foreach ($config['resolve'] as $key => $value) {
                    if (is_string($value) && strncmp($value, 'js:', 3) === 0) {
                        $config['resolve'][$key] = new JsExpression(substr($value, 3));
                    }
                }
            }
        }
        $this->_controller = null;
    }

    /**
     * Render script create module. The result are
     * ```javascript
     * module = angular.module('appName',[requires,...]);
     * ```
     */
    protected function renderModule()
    {
        $requires = Json::encode($this->requires);
        return <<<JS
var module = angular.module('{$this->name}',$requires);
var {$this->_varName} = module;
var widget = module.widget = {};
JS;
    }

    protected function renderTemplates()
    {
        return "widget.templates = " . Json::encode($this->_templates) . ';';
    }

    /**
     * Render script config for $stateProvider
     */
    protected function renderStateProviders()
    {
        $states = implode("\n", $this->_stateProviders);
        return <<<JS
module.config(configStateProfider);
configStateProfider.\$inject = ['\$stateProvider'];
function configStateProfider(\$stateProvider){
\$stateProvider
{$states};
};
JS;
    }

    /**
     * Render script create controllers
     * ```javascript
     * module.controller('CtrlName',['$scope',...,
     *     function($scope,...){
     *         ...
     *     }]);
     * ```
     */
    protected function renderControllers()
    {
        $js = [];
        $view = $this->getView();
        foreach ($this->_controllers as $name => $controller) {
            $injection = $controller['injection'];
            $injectionStr = Json::encode($injection);
            $injectionVar = implode(", ", $injection);

            $function = implode("\n", $controller['js']);
            $function .= implode("\n", ArrayHelper::remove($view->js, $name, []));
            $js[] = <<<JS
module.controller('$name',$name);
$name.\$inject = $injectionStr;
function $name($injectionVar){
$function
}
JS;
        }
        return implode("\n", $js);
    }

    /**
     * Register script to controller.
     *
     * @param string $viewFile
     * @param array $params
     * @param integer|string $pos
     */
    public function renderJs($viewFile, $params = [], $pos = null)
    {
        $params['widget'] = $this;
        $js = $this->getView()->render($viewFile, $params);
        $this->registerJs($js, $pos);
    }

    /**
     * Register script to controller.
     *
     * @param string $js
     * @param integer|string $pos
     */
    public function registerJs($js, $pos = null, $key = null)
    {
        if ($pos === null) {
            if ($this->_controller) {
                $this->_controllers[$this->_controller]['js'][] = Helper::parseBlockJs($js);
            } else {
                $this->_services[] = Helper::parseBlockJs($js);
            }
        } else {
            $this->getView()->registerJs(Helper::parseBlockJs($js), $pos, $key);
        }
    }
}
