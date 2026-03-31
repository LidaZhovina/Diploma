<?php

namespace app\controllers;

use app\models\Room;
use app\models\RoomImage;
use app\models\RoomType;
use app\models\StatusRoom;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

/**
 * RoomController implements the CRUD actions for Room model.
 */
class RoomController extends Controller
{
    const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    const MAX_IMAGE_SIZE = 5 * 1024 * 1024; // 5 MB

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        $publicActions = ['display-image'];
        if (in_array($action->id, $publicActions)) {
            return true;
        }
        
        if (!Yii::$app->user->identity?->isAdmin) {
            return $this->redirect('/');
        }


        return true;
    }

    /**
     * Lists all Room models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Room::find(),

            'pagination' => [
                'pageSize' => 6,
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
     * Displays a single Room model.
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
     * Creates a new Room model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Room();
        $types = RoomType::getTypes();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->status_room_id = StatusRoom::getStatusId('unbound');

                if ($model->validate() && $model->save()) {
                    $uploadedFiles = UploadedFile::getInstancesByName('imageFiles');
                    $this->saveImages($model->id, $uploadedFiles);

                    Yii::$app->session->setFlash('success', 'Номер создан!');
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Ошибка создания!');
                    return $this->redirect(['create']);
                    // VarDumper::dump($model->errors);
                    // die;
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'types' => $types,
        ]);
    }

    /**
     * Updates an existing Room model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            $uploadedFiles = UploadedFile::getInstancesByName('imageFiles');
            $this->saveImages($model->id, $uploadedFiles);

            Yii::$app->session->setFlash('success', 'Номер обновлён!');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Сохраниние файлов и записей в бд
     */
    private function saveImages($roomId, $imageFiles)
    {
        $folder = 'img/room/';
        $uploadPath = Yii::getAlias('@webroot/' . $folder);
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $errors = [];
        foreach ($imageFiles as $file) {
            if (!in_array(strtolower($file->extension), self::ALLOWED_EXTENSIONS)) {
                $errors[] = "Файл {$file->name} – неподдерживаемый формат.";
                continue;
            }
            if ($file->size > self::MAX_IMAGE_SIZE) {
                $errors[] = "Файл {$file->name} слишком большой (макс. 5 МБ).";
                continue;
            }

            $fileName = uniqid() . '.' . $file->extension;
            $filePath = $uploadPath . $fileName;
            if ($file->saveAs($filePath)) {
                $image = new RoomImage();
                $image->room_id = $roomId;
                $image->image = $folder . $fileName;   // сохраняем относительный путь
                $image->save();
            } else {
                $errors[] = "Не удалось сохранить файл {$file->name}.";
            }
        }

        if (!empty($errors)) {
            Yii::$app->session->setFlash('warning', 'Проблемы с загрузкой изображений: ' . implode(' ', $errors));
        }
    }

    /**
     * Deletes an existing Room model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        foreach ($model->roomImages as $image) {
            $filePath = Yii::getAlias('@webroot/' . $image->image);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Отображение изображений в слайдере
     */
    public function actionDisplayImage($id)
    {
        $image = RoomImage::findOne($id);
        if (!$image) {
            throw new NotFoundHttpException('Изображение не найдено');
        }

        $filePath = Yii::getAlias('@webroot/' . $image->image);
        if (!file_exists($filePath)) {
            throw new NotFoundHttpException('Файл изображения не найден');
        }

        // Определяем MIME-тип
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', $mimeType);
        return file_get_contents($filePath);
    }

    /**
     * Finds the Room model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Room the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Room::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
