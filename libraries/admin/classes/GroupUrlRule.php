<?php

namespace mdm\admin\classes;

use Yii;
use yii\web\CompositeUrlRule;
use yii\web\UrlRuleInterface;
use yii\base\InvalidConfigException;
use yii\web\UrlRule;

/**
 * Description of GroupUrlRule
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class GroupUrlRule extends CompositeUrlRule
{
    /**
     * @var array the rules contained within this composite rule. Please refer to [[UrlManager::rules]]
     * for the format of this property.
     * @see prefix
     * @see routePrefix
     */
    public $rules = [];

    /**
     * @var string the prefix for the pattern part of every rule declared in [[rules]].
     * The prefix and the pattern will be separated with a slash.
     */
    public $prefix;

    /**
     * @var string the prefix for the route part of every rule declared in [[rules]].
     * The prefix and the route will be separated with a slash.
     * If this property is not set, it will take the value of [[prefix]].
     */
    public $routePrefix;

    /**
     * @var string the URL suffix used when in 'path' format.
     * For example, ".html" can be used so that the URL looks like pointing to a static HTML page.
     * This property is used only if [[enablePrettyUrl]] is true.
     */
    public $suffix;

    /**
     * @var array the default configuration of URL rules. Individual rule configurations
     * specified via [[rules]] will take precedence when the same property of the rule is configured.
     */
    public $ruleConfig = ['class' => 'yii\web\UrlRule'];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->routePrefix === null) {
            $this->routePrefix = $this->prefix;
        }
        $this->prefix = trim($this->prefix, '/');
        $this->routePrefix = trim($this->routePrefix, '/');
        if ($this->suffix !== null) {
            $this->ruleConfig['suffix'] = $this->suffix;
        }
        parent::init();
    }

    /**
     * @inheritdoc
     */
    protected function createRules()
    {
        $compiledRules = [];
        $verbs = 'GET|HEAD|POST|PUT|PATCH|DELETE|OPTIONS';
        foreach ($this->rules as $key => $rule) {
            if (is_string($rule)) {
                $rule = ['route' => ltrim($this->routePrefix . '/' . ltrim($rule, '/'), '/')];
                if (preg_match("/^((?:($verbs),)*($verbs))\\s+(.*)$/", $key, $matches)) {
                    $rule['verb'] = explode(',', $matches[1]);
                    // rules that do not apply for GET requests should not be use to create urls
                    if (!in_array('GET', $rule['verb'])) {
                        $rule['mode'] = UrlRule::PARSING_ONLY;
                    }
                    $key = $matches[4];
                }
                $rule['pattern'] = ltrim($this->routePrefix . '/' . ltrim($key, '/'), '/');
            } elseif (is_array($rule)) {
                if (isset($rule['route'])) {
                    $rule['route'] = ltrim($this->routePrefix . '/' . ltrim($rule['route'], '/'), '/');
                }
                if (isset($rule['pattern'])) {
                    $rule['pattern'] = ltrim($this->routePrefix . '/' . ltrim($rule['pattern'], '/'), '/');
                }
            }

            if (is_array($rule)) {
                $rule = Yii::createObject(array_merge($this->ruleConfig, $rule));
            }
            if (!$rule instanceof UrlRuleInterface) {
                throw new InvalidConfigException('URL rule class must implement UrlRuleInterface.');
            }
            $compiledRules[] = $rule;
        }
        return $compiledRules;
    }

    /**
     * @inheritdoc
     */
    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        $suffix = (string) $this->suffix;
        if ($suffix !== '' && $pathInfo !== '') {
            $n = strlen($suffix);
            if (substr_compare($pathInfo, $suffix, -$n, $n) === 0) {
                $pathInfo = substr($pathInfo, 0, -$n);
                if ($pathInfo === '') {
                    // suffix alone is not allowed
                    return false;
                }
            } else {
                return false;
            }
        }
        if ($this->prefix === '' || strpos($pathInfo . '/', $this->prefix . '/') === 0) {
            return parent::parseRequest($manager, $request);
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function createUrl($manager, $route, $params)
    {
        if ($this->routePrefix === '' || strpos($route, $this->routePrefix . '/') === 0) {
            return parent::createUrl($manager, $route, $params);
        } else {
            return false;
        }
    }
}
