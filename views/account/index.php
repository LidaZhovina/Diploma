<?php

use app\models\Booking;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var app\models\AccountSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $bookedRoutes */
/** @var array $availableRoutes */

$this->title = 'Личный кабинет';
?>
<div class="booking-index">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">

            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

            <!-- Вкладки -->
            <ul class="nav nav-underline" id="accountTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="bookings-tab" data-bs-toggle="tab" data-bs-target="#bookings" type="button" role="tab">Мои бронирования</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="routes-tab" data-bs-toggle="tab" data-bs-target="#routes" type="button" role="tab">Маршруты</button>
                </li>
            </ul>

            <div class="tab-content mt-3">
                <!-- Вкладка бронирований -->
                <div class="tab-pane fade show active" id="bookings" role="tabpanel">
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                    <?= ListView::widget([
                        'dataProvider' => $dataProvider,
                        'pager' => ['class' => LinkPager::class],
                        'itemOptions' => ['class' => 'item'],
                        'itemView' => 'item',
                    ]) ?>
                </div>

                <!-- Вкладка маршрутов -->
                <div class="tab-pane fade" id="routes" role="tabpanel">
                    <h4>Забронированные маршруты</h4>
                    <?php if (empty($bookedRoutesGrouped)): ?>
                        <p>Вы ещё не записаны на маршруты.</p>
                    <?php else: ?>
                        <?php foreach ($bookedRoutesGrouped as $item): ?>
                            <?= $this->render('_route_item2', [
                                'route' => $item['route'],
                                'isBooked' => true,
                                'residents' => $item['residents'],
                            ]) ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <hr>

                    <h4>Доступные маршруты</h4>
                    <?php if (empty($availableRoutes)): ?>
                        <p>Нет доступных маршрутов.</p>
                    <?php else: ?>
                        <?php foreach ($availableRoutes as $route): ?>
                            <?= $this->render('_route_item', ['model' => $route, 'isBooked' => false]) ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>