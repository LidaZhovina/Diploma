<?php

namespace app\controllers;

use app\models\WellnessImage;
use app\models\WellnessProgram;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * WellnessProgramController implements the CRUD actions for WellnessProgram model.
 */
class WellnessProgramController extends Controller
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
     * Lists all WellnessProgram models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => WellnessProgram::find(),

            'pagination' => [
                'pageSize' => 5,
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

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        if (!Yii::$app->user->identity?->isAdmin) {
            return $this->redirect('/');
        }


        return true;
    }

    /**
     * Displays a single WellnessProgram model.
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

    /**
     * Creates a new WellnessProgram model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new WellnessProgram();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                if ($model->save()) {
                    $imageFile = UploadedFile::getInstanceByName('imageFile');
                    if ($imageFile) {
                        $this->saveWellnessImage($model->id, $imageFile);
                    }
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Сохраняет изображение для программы
     */
    private function saveWellnessImage($programId, UploadedFile $imageFile)
    {
        $folder = 'img/wellness/';
        $uploadPath = Yii::getAlias('@webroot/' . $folder);
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $fileName = uniqid() . '.' . $imageFile->extension;
        $filePath = $uploadPath . $fileName;

        if ($imageFile->saveAs($filePath)) {
            $wellnessImage = new WellnessImage();
            $wellnessImage->wellness_id = $programId;
            $wellnessImage->image = $folder . $fileName; // сохраняем относительный путь
            $wellnessImage->save();
        }
    }

    /**
     * Updates an existing WellnessProgram model.
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

    /**
     * Deletes an existing WellnessProgram model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->wellnessImage) {
            $filePath = Yii::getAlias('@webroot/' . $model->wellnessImage->image);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the WellnessProgram model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return WellnessProgram the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WellnessProgram::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
