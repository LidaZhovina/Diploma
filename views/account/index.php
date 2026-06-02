<?php

use app\models\Booking;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\AccountSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $bookedRoutesGrouped */
/** @var array $availableRoutes */

$this->title = 'Личный кабинет';
$user      = Yii::$app->user->identity;
$initials  = mb_strtoupper(mb_substr($user->surname ?? '', 0, 1) . mb_substr($user->name ?? '', 0, 1));
$fullName  = trim(($user->surname ?? '') . ' ' . ($user->name ?? ''));
$email     = $user->email ?? '';

$this->registerCssFile('@web/css/account.css',  ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);
$this->registerCssFile('@web/css/route-card.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);
?>

<div class="booking-index">

    <!-- ── ШАПКА ── -->
    <div class="acc-hero">
        <div class="acc-avatar"><?= Html::encode($initials) ?></div>
        <div class="acc-hero-title"><?= Html::encode($fullName) ?></div>
        <div class="acc-hero-sub"><?= Html::encode($email) ?></div>
    </div>

    <!-- ── ВКЛАДКИ ── -->
    <div class="acc-tabs-wrap">
        <ul class="nav nav-tabs" id="accountTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="bookings-tab"
                        data-bs-toggle="tab" data-bs-target="#bookings"
                        type="button" role="tab">
                    Мои бронирования
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="routes-tab"
                        data-bs-toggle="tab" data-bs-target="#routes"
                        type="button" role="tab">
                    Маршруты
                </button>
            </li>
        </ul>
    </div>

    <!-- ── КОНТЕНТ ── -->
    <div class="acc-content">
        <div class="tab-content">

            <!-- Вкладка: Бронирования -->
            <div class="tab-pane fade show active" id="bookings" role="tabpanel">
                <?= $this->render('_search', ['model' => $searchModel]) ?>
                <?php Pjax::begin(); ?>
                <?= ListView::widget([
                    'dataProvider' => $dataProvider,
                    'pager'        => ['class' => LinkPager::class],
                    'itemView'     => 'item',
                    'itemOptions'  => ['class' => 'item'],
                    'options'      => ['class' => 'list-view'],
                    'summary'      => false,
                ]); ?>
                <?php Pjax::end(); ?>
            </div>

            <!-- Вкладка: Маршруты -->
            <div class="tab-pane fade" id="routes" role="tabpanel">

                <?php if (!empty($bookedRoutesGrouped)): ?>
                    <div class="rc-section-label">Мои записи</div>
                    <div class="rc-list">
                        <?php foreach ($bookedRoutesGrouped as $item): ?>
                            <?= $this->render('_route_item', [
                                'route'     => $item['route'],
                                'isBooked'  => true,
                                'residents' => $item['residents'],
                            ]) ?>
                        <?php endforeach; ?>
                    </div>
                    <?php if (!empty($availableRoutes)): ?>
                        <div class="rc-divider"></div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (!empty($availableRoutes)): ?>
                    <div class="rc-section-label">Доступные маршруты</div>
                    <div class="rc-list">
                        <?php foreach ($availableRoutes as $route): ?>
                            <?= $this->render('_route_item', [
                                'route'    => $route,
                                'isBooked' => false,
                            ]) ?>
                        <?php endforeach; ?>
                    </div>
                <?php elseif (empty($bookedRoutesGrouped)): ?>
                    <div class="rc-empty">
                        <div class="rc-empty-icon">🏔</div>
                        <p>Нет доступных маршрутов</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>