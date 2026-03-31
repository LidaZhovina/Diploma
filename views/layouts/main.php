<?php

/** @var yii\web\View $this */
/** @var string $content */

// use Yii;
use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100 body">
    <?php $this->beginBody() ?>

    <header id="header">
        <?php
        NavBar::begin([
            'brandLabel' => '<img src=/img/logo.jpg alt=logo class="logo"> Сайт',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => ['class' => 'navbar-expand-md navbarColor navbar-dark fixed-top']
        ]);

        // Левая группа (основные ссылки)
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav me-auto'],
            'items' => [
                ['label' => 'Номера', 'url' => ['/catalog']],
                ['label' => 'Оздоровительные программы', 'url' => ['/wellness-catalog']],
            ]
        ]);

        // Кнопка для администратора (открывает меню)
        if (Yii::$app->user->identity?->isAdmin) {
            echo Html::a('Панель Администратора', '#', [
                'class' => 'nav-link ms-auto btnAdmin',
                'data-bs-toggle' => 'offcanvas',
                'data-bs-target' => '#adminOffcanvas',
                'aria-controls' => 'adminOffcanvas',
            ]);
        }

        // Для гостей: ссылки регистрации и авторизации
        if (Yii::$app->user->isGuest) {
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items' => [
                    ['label' => 'Регистрация', 'url' => ['/site/register']],
                    ['label' => 'Авторизация', 'url' => ['/site/login']],
                    Yii::$app->user->identity?->isClient
                        ? ['label' => 'Личный кабинет', 'url' => ['/account']]
                        : '',
                ]
            ]);
        } else {
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav '],
                'items' => [
                    Yii::$app->user->identity?->isClient
                        ? ['label' => 'Личный кабинет', 'url' => ['/account']]
                        : '',
                    Yii::$app->user->identity?->isClient
                        ? Html::beginForm(['/site/logout'])
                        . Html::submitButton(
                            'Выход (' . Yii::$app->user->identity->email . ')',
                            ['class' => 'nav-link btn btn-link logout']
                        )
                        . Html::endForm()
                        : ''

                ]
            ]);
        }

        NavBar::end();
        ?>
    </header>

    <?php if (Yii::$app->user->identity?->isAdmin): ?>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="adminOffcanvas" aria-labelledby="adminOffcanvasLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="adminOffcanvasLabel">Администрирование</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Закрыть"></button>
            </div>
            <div class="offcanvas-body">
                <div class="list-group">
                    <a href="<?= Url::to(['/admin']) ?>" class="list-group-item list-group-item-action">Панель админа</a>
                    <a href="<?= Url::to(['/room/index']) ?>" class="list-group-item list-group-item-action">Номера</a>
                    <a href="<?= Url::to(['/wellness-program/index']) ?>" class="list-group-item list-group-item-action">Оздоровительные программы</a>
                    <a href="<?= Url::to(['/route/index']) ?>" class="list-group-item list-group-item-action">Маршруты</a>
                    <a href="<?= Url::to(['/user/index']) ?>" class="list-group-item list-group-item-action">Пользователи</a>
                    <div class="dropdown-divider"></div>
                    <?= Html::beginForm(['/site/logout']) ?>
                    <?= Html::submitButton('Выход', ['class' => 'list-group-item list-group-item-action text-danger']) ?>
                    <?= Html::endForm() ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <main id="main" class="flex-shrink-0" role="main">
        <div class="container">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer id="footer" class="mt-auto py-3 ">
        <div class="container">
            <div class="row text-muted">
                <div class="col-md-6 text-center text-md-start">&copy; Сайт <?= date('Y') ?></div>
                <div class="col-md-6 text-center text-md-start">Информация, размещенная на сайте, носит справочный характер 
                    (не может использоваться для постановки диагноза, не заменяет приём врача)
                     и не является публичной офертой, определяемой положениями Статьи 437 ГК РФ.</div>
            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>