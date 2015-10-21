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
 * Description of NgView
 *
 * @property string $controller
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class NgView extends Widget
{
    public $options = [];

    /**
     *
     * @var array
     */
    public $routes = [];

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

    /**
     * @var boolean If `true` will render attribute `ng-app="appName"` in widget.
     */
    public $useNgApp = true;

    /**
     * @var array
     */
    public $requires = [];
    public $resources = [];

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
    private $_prefixes = [];
    private $_routeProviders = [];
    private $_injections = [];
    private $_controller;

    /**
     * @inheritdoc
     */
    public function init()
    {
        static::$instance = $this;
        $this->_varName = Inflector::variablize($this->name);

        $this->requires[] = 'ngRoute';
        $this->requires = array_unique($this->requires);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $view = $this->getView();
        // Asset Dependency
        $am = Yii::$app->getAssetManager();
        $key = md5(serialize([__CLASS__, Yii::$app->controller->route, $this->name]));
        $bundle = [
            'basePath' => '',
            'depends' => [AngularAsset::className()],
            'js' => [],
        ];
        $js = $this->generate();
        foreach ($this->requires as $module) {
            if (isset(AngularAsset::$assetMap[$module])) {
                $bundle['depends'][] = AngularAsset::$assetMap[$module];
            }
        }
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

        static::$instance = null;
        $options = $this->options;
        if ($this->tag !== 'ng-view' && !isset($options['ng-view'])) {
            $options['ng-view'] = true;
        }
        if ($this->useNgApp) {
            $options[ng - app] = $this->name;
        }
        return Html::tag($this->tag, '', $options);
    }

    public function getController()
    {
        return $this->_controller;
    }

    public function requires($modules)
    {
        $this->requires = array_unique(array_merge($this->requires, (array) $modules));
    }

    protected function generate()
    {
        $this->add([
            'injection' => $this->injection,
            'js' => $this->js,
            'templates' => $this->templates,
            'routes' => $this->routes,
            'resources' => $this->resources,
        ]);
        $js = [];
        $js[] = "{$this->_varName} = (function(options){";
        $js[] = $this->renderModule();
        $js[] = implode("\n", $this->_services);
        $js[] = $this->renderTemplates();
        $js[] = $this->renderRouteProviders();
        $js[] = $this->renderControllers();

        $options = empty($this->clientOptions) ? '{}' : Json::htmlEncode($this->clientOptions);
        $js[] = "\nreturn module;\n})({$options});";

        return implode("\n\n", $js);
    }

    protected function applyPrefix($path)
    {
        if (strncmp($path, '@', 1) === 0) {
            return substr($path, 1);
        }
        return strncmp($path, '/', 1) === 0 ? $path : (end($this->_prefixes) ? : '/') . $path;
    }

    public function add($configs)
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
        if (!empty($configs['resources'])) {
            $this->requires(['ngResource']);
            $this->_services[] = $this->renderResources($configs['resources']);
        }
        if (!empty($configs['templates'])) {
            foreach ($configs['templates'] as $name => $config) {
                $name = $this->applyPrefix($name);
                $this->applyTemplate($name, $config);
                $this->_templates[$name] = $config;
            }
        }

        if (!empty($configs['routes'])) {
            $otherwise = null;
            foreach ($configs['routes'] as $path => $config) {
                if (is_int($path)) {
                    $view->render($config, ['widget' => $this]);
                    continue;
                }
                if ($path === 'otherwise') {
                    $path = count($this->_prefixes) ? $this->applyPrefix(':redirect*') : 'otherwise';
                    $otherwise = true;
                } else {
                    $path = $this->applyPrefix($path);
                }
                $p = json_encode($path);

                if (is_string($config) && strncmp($config, 'js:', 3) !== 0) {
                    $this->_prefixes[] = rtrim($path, '/') . '/';
                    $view->render($config, ['widget' => $this]);
                    array_pop($this->_prefixes);
                } else {
                    if (is_string($config)) {
                        $config = substr($config, 3);
                    } elseif ($config instanceof JsExpression) {
                        $config = $config->expression;
                    } else {
                        $this->applyTemplate($path, $config);
                        $config = Json::htmlEncode($config);
                    }
                    if ($otherwise === true) {
                        $otherwise = ($path === 'otherwise') ? ".otherwise({$config})" : ".when({$p},{$config})";
                    } else {
                        $this->_routeProviders[] = ".when({$p},{$config})";
                    }
                }
            }
            if ($otherwise) {
                $this->_routeProviders[] = $otherwise;
            }
        }

        if (isset($configs['injection'])) {
            array_pop($this->_injections);
        }
    }

    protected function applyTemplate($path, &$config)
    {
        $view = $this->getView();
        if (isset($config['js'])) {
            $injection = end($this->_injections);
            if ($injection === false) {
                $injection = $this->injection;
            }
            if (empty($config['controller'])) {
                $config['controller'] = Inflector::camelize($path) . 'Controller';
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
        $requires = Json::htmlEncode($this->requires);
        return <<<JS
var module = angular.module('{$this->name}',$requires);
var {$this->_varName} = module;
var widget = module.widget = {};
JS;
    }

    protected function renderTemplates()
    {
        $templates = empty($this->_templates) ? '{}' : Json::htmlEncode($this->_templates);
        return "widget.templates = $templates;";
    }

    /**
     * Render script config for $routeProvider
     * @param array $routeProviders
     */
    protected function renderRouteProviders()
    {
        $routeProviders = implode("\n", $this->_routeProviders);
        return <<<JS
module.config(['\$routeProvider',function(\$routeProvider){
\$routeProvider
{$routeProviders};
}]);
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
     * @param array $controllers
     */
    protected function renderControllers()
    {
        $js = [];
        $view = $this->getView();
        foreach ($this->_controllers as $name => $controller) {
            $injection = $controller['injection'];
            $injectionStr = Json::htmlEncode($injection);
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
     * Render script resource
     * ```javascript
     * module.factory(ResName,['$resource',function($resource){
     *     return ...;
     * }]);
     * ```
     */
    protected function renderResources($resources)
    {
        $js = [];
        foreach ($resources as $name => $config) {
            $url = Json::htmlEncode($config['url']);
            if (empty($config['paramDefaults'])) {
                $paramDefaults = '{}';
            } else {
                $paramDefaults = Json::htmlEncode($config['paramDefaults']);
            }
            if (empty($config['actions'])) {
                $actions = '{}';
            } else {
                $actions = Json::htmlEncode($config['actions']);
            }
            $js[] = <<<JS
module.factory('$name',['\$resource',function(\$resource){
    return \$resource({$url},{$paramDefaults},{$actions});
}]);
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
