<?php

namespace dee\angular;

/**
 * Description of View
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class View extends \yii\web\View
{

    /**
     * @inheritdoc
     */
    public function registerJs($js, $position = null, $key = null)
    {
        if ($position === null && NgView::$instance) {
            $position = NgView::$instance->getController() ? : self::POS_READY;
        }
        parent::registerJs($js, $position, $key);
    }
}
