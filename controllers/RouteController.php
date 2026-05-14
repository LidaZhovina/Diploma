<?php

namespace app\controllers;

use app\models\Level;
use app\models\Route;
use app\models\RouteImage;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

/**
 * RouteController implements the CRUD actions for Route model.
 */
class RouteController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Route models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Route::find(),

            'pagination' => [
                'pageSize' => 50
            ],
            /*
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Route model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if ($action->id === 'view') {
            // Если хотите только авторизованных:
            if (Yii::$app->user->isGuest) {
                return $this->redirect(['/site/login']);
            }
            return true;
        }

        if (!Yii::$app->user->identity?->isAdmin) {
            return $this->redirect('/');
        }


        return true;
    }

    /**
     * Creates a new Route model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Route();
        $levels = Level::getItems();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                if ($model->save()) {
                    $imageFile = UploadedFile::getInstanceByName('imageFile');
                    if ($imageFile) {
                        $this->saveRouteImage($model->id, $imageFile);
                    }
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    VarDumper::dump($model->errors);
                    die;
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'levels' => $levels,
        ]);
    }

    /**
     * Updates an existing Route model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    private function saveRouteImage($routeId, UploadedFile $imageFile)
    {
        // Путь к папке относительно @webroot
        $folder = 'img/routes/';
        $uploadPath = Yii::getAlias('@webroot/' . $folder);

        // Создаём папку, если её нет
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Генерируем уникальное имя файла
        $fileName = uniqid() . '.' . $imageFile->extension;
        $filePath = $uploadPath . $fileName;

        if ($imageFile->saveAs($filePath)) {
            $routeImage = new RouteImage();
            $routeImage->route_id = $routeId;
            $routeImage->image = $folder . $fileName; // сохраняем путь 'img/routes/...'
            $routeImage->save();
        }
    }

    /**
     * Deletes an existing Route model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // $model = $this->findModel($id);

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Route model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Route the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Route::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
