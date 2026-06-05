<?php

namespace app\controllers;

use app\models\Route;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class RouteCatalogController extends Controller
{
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Route::find()->with(['routeImage', 'level']),
            'pagination' => false,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = Route::findOne($id);
        if (!$model) {
            throw new \yii\web\NotFoundHttpException('Маршрут не найден.');
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }
}
