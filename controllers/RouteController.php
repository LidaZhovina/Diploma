<?php

namespace app\controllers;

use app\models\Level;
use app\models\Raiting;
use app\models\Review;
use app\models\Route;
use app\models\RouteImage;
use app\models\RouteResident;
use app\models\RouteStatus;
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
        $model = Route::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Маршрут не найден');
        }

        // Получаем текущую оценку пользователя (если авторизован)
        $userRating = null;
        if (!Yii::$app->user->isGuest) {
            $userRating = Raiting::find()
                ->where(['route_id' => $model->id, 'user_id' => Yii::$app->user->id])
                ->select('stars')
                ->scalar();
        }

        // Отзывы из таблицы reviews 
        $reviews = Review::find()
            ->with('user')
            ->where(['route_id' => $model->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        // Может ли текущий пользователь оставить отзыв?
        $canReview = false;
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isClient) {
            $wasParticipant = RouteResident::find()
                ->joinWith('resident')
                ->where([
                    'route_resident.route_id' => $model->id,
                    'resident.user_id'        => Yii::$app->user->id,
                ])
                ->exists();

            $canReview = $wasParticipant
                && !Review::hasResidentRouteReview(Yii::$app->user->id, $model->id);
        }

        return $this->render('view', [
            'model' => $model,
            'userRating' => $userRating,
            'reviews'    => $reviews,
            'canReview'  => $canReview,
        ]);
    }


    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        // Эти действия доступны авторизованным пользователям (не только админу)
        if (in_array($action->id, ['view', 'add-review'])) {
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

    /* Смена статуса маршрута*/

    public function actionChangeStatus($id, $alias)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {
            $model->route_status = RouteStatus::getStatusId($alias);

            if ($model->save()) {
                Yii::$app->session->setFlash('warning', 'Статус обновлён!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->redirect('/admin');
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
     * Оставить отзыв на маршрут.
     * Доступно только участникам маршрута.
     *
     * @param int $id  ID маршрута
     */
    public function actionAddReview(int $id)
    {
        $route  = $this->findModel($id);
        $userId = Yii::$app->user->id;

        // Только авторизованные клиенты
        if (!Yii::$app->user->identity?->isClient) {
            return $this->redirect(['/site/login']);
        }

        // Резиденты, которые могут ещё оставить отзыв
        $residentsCanReview = \app\models\Review::getResidentsCanReview($userId, $id);

        if (empty($residentsCanReview)) {
            Yii::$app->session->setFlash('info', 'Все ваши гости уже оставили отзыв на этот маршрут.');
            return $this->redirect(['view', 'id' => $id]);
        }

        $model = new \app\models\Review();
        $model->user_id  = $userId;
        $model->route_id = $id;
        $model->stars    = 5;

        if ($model->load(Yii::$app->request->post())) {
            // Проверяем, что выбранный resident_id принадлежит текущему пользователю
            // и действительно участвовал в этом маршруте
            $validIds = array_map(fn($r) => $r->id, $residentsCanReview);
            if (!in_array((int)$model->resident_id, $validIds)) {
                Yii::$app->session->setFlash('error', 'Некорректный гость.');
                return $this->redirect(['view', 'id' => $id]);
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Спасибо за отзыв!');
                // Если ещё остались гости без отзыва — остаёмся на странице
                $remaining = \app\models\Review::getResidentsCanReview($userId, $id);
                if (!empty($remaining)) {
                    return $this->redirect(['add-review', 'id' => $id]);
                }
                return $this->redirect(['view', 'id' => $id]);
            }
        }

        return $this->render('add-review', [
            'model'              => $model,
            'route'              => $route,
            'residentsCanReview' => array_values($residentsCanReview),
        ]);
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
