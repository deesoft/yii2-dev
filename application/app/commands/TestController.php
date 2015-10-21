<?php

namespace app\commands;

use yii\console\Controller;

/**
 * Description of TestController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class TestController extends Controller
{
    public function actionIndex($arg1, $arg2)
    {
        var_dump(func_get_args());
    }
}
