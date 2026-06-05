<?php

/** @var yii\web\View $this */
/** @var string $content */

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100 body">
    <?php $this->beginBody() ?>

    <header id="header">
        <?php
        NavBar::begin([
            'brandLabel' => '<img src=/img/logo.jpg alt=logo class="logo"> Танхой',
            'brandUrl'   => Yii::$app->homeUrl,
            'options'    => ['class' => 'navbar-expand-md navbar-site navbar-light fixed-top'],
        ]);

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav me-auto'],
            'items'   => [
                ['label' => 'Номера',                  'url' => ['/catalog']],
                ['label' => 'Оздоровительные программы', 'url' => ['/wellness-catalog']],
                ['label' => 'Маршруты', 'url' => ['/route-catalog']],
            ],
        ]);

        if (Yii::$app->user->identity?->isAdmin) {
            echo Html::a('Панель Администратора', '#', [
                'class'           => 'nav-link ms-auto btnAdmin',
                'data-bs-toggle'  => 'offcanvas',
                'data-bs-target'  => '#adminOffcanvas',
                'aria-controls'   => 'adminOffcanvas',
            ]);
        }

        if (Yii::$app->user->isGuest) {
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items'   => [
                    ['label' => 'Регистрация', 'url' => ['/site/register']],
                    ['label' => 'Авторизация', 'url' => ['/site/login']],
                ],
            ]);
        } else {
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items'   => [
                    Yii::$app->user->identity?->isReception
                        ? ['label' => 'Кабинет Менеджера', 'url' => ['/reseption']] : '',
                    Yii::$app->user->identity?->isClient
                        ? ['label' => 'Личный кабинет',   'url' => ['/account']]   : '',
                    Yii::$app->user->identity?->isClient | Yii::$app->user->identity?->isReception
                        ? Html::beginForm(['/site/logout'])
                        . Html::submitButton(
                            'Выход (' . Yii::$app->user->identity->email . ')',
                            ['class' => 'nav-link btn btn-link logout']
                        )
                        . Html::endForm()
                        : '',
                ],
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
                    <a href="<?= Url::to(['/review']) ?>" class="list-group-item list-group-item-action">Отзывы</a>
                    <div class="dropdown-divider"></div>
                    <?= Html::beginForm(['/site/logout']) ?>
                    <?= Html::submitButton('Выход', ['class' => 'list-group-item list-group-item-action text-danger']) ?>
                    <?= Html::endForm() ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <main id="main" class="flex-shrink-0" role="main" style="padding-top: 58px;">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <div class="container">
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            </div>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </main>

    <footer style="background: #deeaff; padding: 28px 48px;">
        <div class="container d-flex justify-content-between align-items-center gap-4 mb-3">
            <a href="/" class="d-flex align-items-center gap-2 text-decoration-none">
                <div style="width:30px; height:30px; border-radius:8px; background:#3B4593; display:flex; align-items:center; justify-content:center; font-size:15px; font-weight:500; color:#fff; flex-shrink:0;">
                    Т
                </div>
                <span style="font-size:15px; font-weight:500; color:#3B4593;">Санаторий Танхой</span>
            </a>

            <div class="d-flex gap-4">
                <a href="/catalog" style="font-size:13px; color:#4a559e; text-decoration:none;">Номера</a>
                <a href="/wellness-catalog" style="font-size:13px; color:#4a559e; text-decoration:none;">Программы</a>
                <a href="/route-catalog" style="font-size:13px; color:#4a559e; text-decoration:none;">Маршруты</a>
            </div>

            <div class="d-flex gap-2">
                <a href="https://vk.com/?ysclid=mq199hmk8y369894912" title="ВКонтакте" aria-label="ВКонтакте"
                    style="width:34px; height:34px; border-radius:8px; background:rgba(59,69,147,0.12); display:flex; align-items:center; justify-content:center; color:#3B4593; text-decoration:none; font-size:17px;">
                    <i class="ti ti-brand-vk" aria-hidden="true"></i>
                </a>
                <a href="https://web.max.ru/" title="MAX" aria-label="MAX"
                    style="width:34px; height:34px; border-radius:8px; background:rgba(59,69,147,0.12); display:flex; align-items:center; justify-content:center; color:#3B4593; text-decoration:none; font-size:17px;">
                    <i class="ti ti-brand-telegram" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <div class="container" style="height:0.5px; background:rgba(59,69,147,0.15); margin-bottom:14px;"></div>

        <div class="container d-flex justify-content-between gap-4">
            <p style="font-size:12px; color:#5a67b0; margin:0; white-space:nowrap;">
                &copy; Танхой <?= date('Y') ?>
            </p>
            <p style="font-size:11px; color:#7d88c2; margin:0; line-height:1.55;">
                Информация на сайте носит справочный характер, не заменяет приём врача и не является публичной офертой по ст. 437 ГК РФ.
            </p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>