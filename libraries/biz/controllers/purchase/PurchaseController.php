<?php

namespace biz\core\controllers\purchase;

use Yii;
use biz\core\components\purchase\Purchase as ApiPurchase;

/**
 * Description of PurchaseController
 *
 * @property ApiPurchase $api
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>  
 * @since 3.0
 */
class PurchaseController extends \biz\core\base\rest\Controller
{
    /**
     * @inheritdoc
     */
    public $api = 'biz\core\components\purchase\Purchase';

    public function receive($id)
    {
        try {
            $transaction = Yii::$app->db->beginTransaction();
            $model = $this->api->receive($id, Yii::$app->getRequest()->getBodyParams());
            if (!$model->hasErrors()) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
        } catch (\Exception $exc) {
            $transaction->rollBack();
            throw $exc;
        }
        return $model;
    }
}