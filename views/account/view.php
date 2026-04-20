<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Booking $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="booking-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'room_id',
                'value' => $model->room->roomType->name
            ],
            [
                'attribute' => 'arrival_date',
                'value' => Yii::$app->formatter->asDate($model->arrival_date, 'php:d.m.Y')
            ],
            [
                'attribute' => 'departure_date',
                'value' => Yii::$app->formatter->asDate($model->departure_date, 'php:d.m.Y')
            ],
            'contact_phone',
            'price',
            [
                'attribute' => 'status_booking_id',
                'value' => $model->statusBooking->title
            ],
            'wellness_program_id',
            'route_id',
            'amount_residents',
            'comment:ntext',
        ],
    ]) ?>

</div>
