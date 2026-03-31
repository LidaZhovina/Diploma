<?php

use app\models\Booking;
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

            <?php Pjax::begin(); ?>

            <p class="gap-3 m-auto pb-3 text-center">
                <!-- <?= Html::a('Создать бронирование', ['create'], ['class' => 'btn register']) ?> -->
                <?= Html::a('Программы', ['wellness-program/index'], ['class' => 'btn register']) ?>
                <?= Html::a('В номера', ['/room/index'], ['class' => 'btn register']) ?>
                <?= Html::a('Пользователи', ['/user/index'], ['class' => 'btn register']) ?>
                <?= Html::a('Маршруты', ['/route/index'], ['class' => 'btn register']) ?>
            </p>

            
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'item'],
                'itemView' => function ($model, $key, $index, $widget) {
                    return Html::a(Html::encode($model->id), ['view', 'id' => $model->id]);
                },
            ]) ?>

            <?php Pjax::end(); ?>

        </div>
    </div>
</div>