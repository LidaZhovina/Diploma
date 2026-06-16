<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\RegisterForm;
use app\models\Room;
use app\models\Route;
// use app\models\Role;
use yii\helpers\VarDumper;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $rooms = Room::find()
            ->with(['roomType', 'roomImages'])
            ->limit(4)
            ->orderBy(['id' => SORT_DESC])
            ->all();

        $routes = Route::find()
            ->with(['routeImage', 'level'])
            ->limit(3)
            ->orderBy(['id' => SORT_DESC])
            ->all();

        $reviews = \app\models\Review::find()
            ->with('user')
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(3)
            ->all();

        return $this->render('index', ['rooms' => $rooms, 'routes' => $routes, 'reviews' => $reviews,]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {

            if ($model->login()) {
                Yii::$app->session->setFlash('toast', ['type' => 'success', 'message' => 'Вы успешно авторизовались!']);
                return $this->goBack();
            } else {
                Yii::$app->session->setFlash('toast', ['type' => 'error', 'message' => 'Неккоректные почта или пароль!']);
                return $this->redirect(['login']);
            }
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        Yii::$app->session->setFlash('toast', ['type' => 'info', 'message' => 'Вы вышли из аккаунта']);
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post())) {
            $user = $model->register(true);

            if ($user != null) {
                Yii::$app->session->setFlash('toast', ['type' => 'success', 'message' => 'Вы успешно зарегистрировались!']);
                Yii::$app->user->login($user, 3600 * 24 * 30);
                return $this->goHome();
            } else {
                VarDumper::dump($model->errors);
                die;
            }
        }
        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionRegisterReception()
    {
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post())) {
            $user = $model->register(false);
            if ($user != null) {
                Yii::$app->session->setFlash('toast', ['type' => 'success', 'message' => 'Ресепшн успешно зарегистрирован!']);
                return $this->render('register', ['model' => new RegisterForm()]);
            }
        }
        return $this->render('register', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionGetFlash()
    {
        return $this->asJson(
            Yii::$app->session->getFlash('toast', null)
        );
    }
}
