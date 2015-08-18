<?php

namespace dee\admin\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use mdm\admin\components\MenuHelper;
use dee\admin\models\User;

/**
 * AssignmentController implements the CRUD actions for Assignment model.
 *
 * @property \dee\admin\Module $module
 * 
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class AssignmentController extends Controller
{

    protected function verbs()
    {
        return[
            'index' => ['GET'],
            'view' => ['GET'],
            'assign' => ['POST'],
            'revoke' => ['POST'],
        ];
    }

    /**
     * Lists all Assignment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = User::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere(['like', $this->module->usernameField, Yii::$app->request->get('q', '')]);

        return $dataProvider;
    }

    /**
     * Displays a single Assignment model.
     * @param  integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $model;
    }

    /**
     * Assign or revoke assignment to user
     * @param  integer $id
     * @param  string  $action
     * @return mixed
     */
    public function actionAssign($id)
    {
        $items = Yii::$app->request->post('items', []);
        $manager = Yii::$app->authManager;
        $error = [];
        $count = 0;
        foreach ((array) $items as $name) {
            try {
                $item = $manager->getRole($name);
                $item = $item ? : $manager->getPermission($name);
                $manager->assign($item, $id);
                $count++;
            } catch (\Exception $exc) {
                $error[] = $exc->getMessage();
            }
        }

        //MenuHelper::invalidate();
        return[
            'type' => 'S',
            'count' => $count,
            'errors' => $error,
        ];
    }

    /**
     * Assign or revoke assignment to user
     * @param  integer $id
     * @param  string  $action
     * @return mixed
     */
    public function actionRevoke($id)
    {
        $items = Yii::$app->request->post('items', []);
        $manager = Yii::$app->authManager;
        $error = [];
        $count = 0;
        foreach ($items as $name) {
            try {
                $item = $manager->getRole($name);
                $item = $item ? : $manager->getPermission($name);
                $manager->revoke($item, $id);
                $count++;
            } catch (\Exception $exc) {
                $error[] = $exc->getMessage();
            }
        }

        //MenuHelper::invalidate();
        return[
            'type' => 'S',
            'count' => $count,
            'errors' => $error,
        ];
    }

    /**
     * Finds the Assignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}