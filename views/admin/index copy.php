<?php

use app\models\Booking;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\AdminSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Панель Администратора';
?>
<div class="booking-index">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">

            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

            <!-- <p class="gap-3 m-auto pb-3 text-center">
                <?= Html::a('Создать бронирование', ['create'], ['class' => 'btn register']) ?>
                <?= Html::a('Программы', ['wellness-program/index'], ['class' => 'btn register']) ?>
                <?= Html::a('В номера', ['/room/index'], ['class' => 'btn register']) ?>
                <?= Html::a('Пользователи', ['/user/index'], ['class' => 'btn register']) ?>
                <?= Html::a('Маршруты', ['/route/index'], ['class' => 'btn register']) ?>
            </p> -->

            <!-- Вкладки статусов -->
            <ul class="nav nav-underline mb-3 justify-content-center" id="statusTabs">
                <li class="nav-item">
                    <?= Html::a('В обработке', ['index', 'status' => 'pending'], [
                        'class' => 'nav-link ' . ($searchModel->status_alias == 'pending' ? 'active' : ''),
                        'data-pjax' => '#bookings-pjax',
                    ]) ?>
                </li>
                <li class="nav-item">
                    <?= Html::a('Предстоящие', ['index', 'status' => 'new'], [
                        'class' => 'nav-link ' . ($searchModel->status_alias == 'new' ? 'active' : ''),
                        'data-pjax' => '#bookings-pjax',
                    ]) ?>
                </li>
                <li class="nav-item">
                    <?= Html::a('Активные', ['index', 'status' => 'active'], [
                        'class' => 'nav-link ' . ($searchModel->status_alias == 'active' ? 'active' : ''),
                        'data-pjax' => '#bookings-pjax',
                    ]) ?>
                </li>
                <li class="nav-item">
                    <?= Html::a('Прошедшие', ['index', 'status' => 'past'], [
                        'class' => 'nav-link ' . ($searchModel->status_alias == 'past' ? 'active' : ''),
                        'data-pjax' => '#bookings-pjax',
                    ]) ?>
                </li>
                <li class="nav-item">
                    <?= Html::a('Отменённые', ['index', 'status' => 'cancelled'], [
                        'class' => 'nav-link ' . ($searchModel->status_alias == 'cancelled' ? 'active' : ''),
                        'data-pjax' => '#bookings-pjax',
                    ]) ?>
                </li>
            </ul>

            <?php Pjax::begin(['id' => 'bookings-pjax', 'enablePushState' => true]); ?>
            <!-- <?php echo $this->render('_search', ['model' => $searchModel]); ?> -->

            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'pager' => [
                    'class' => LinkPager::class
                ],
                'itemOptions' => ['class' => 'item'],
                'itemView' => 'item',
            ]) ?>

            <?php Pjax::end(); ?>

        </div>
    </div>
</div>