<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Booking $model */

$this->title = "Детали бронирования:";
\yii\web\YiiAsset::register($this);
?>
<div class="booking-view">
    <h1 class="my-3 text-center"><?= Html::encode($this->title) ?></h1>

    <p class="text-end">
        <?= Html::a('Назад', ['index', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'status_booking_id',
                'value' => $model->statusBooking->title
            ],
            [
                'label' => 'Причина отмены',
                'visible' => (bool)$model?->reason,
                'format' => 'html',
                'value' => $model?->reason ? nl2br($model->reason->comment): '',
            ],
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
            [
                'attribute' => 'price',
                'value' => $model->price . ' руб'
            ],
            [
                'attribute' => 'payment',
                'value' => $model->payment_amount . ' руб'
            ],
            [
                'attribute' => 'pay_type',
                'value' => $model->payType->title
            ],
            [
                'attribute' => 'payment_status',
                'value' => $model->paymentStatus->alias
            ],
            [
                'attribute' => 'route_id',
                'label' => 'Маршрут',
                'value' => function ($model) {
                    if ($model->route_id && $model->route) {
                        return $model->route->name;
                    }
                    return null;
                },
                'visible' => !empty($model->route_id),
            ],
            'amount_residents',
            'comment:ntext',
            [
                'label' => 'Гости и их программы',
                'format' => 'raw',
                'value' => function ($model) {
                    $output = '<div class="list-group">';
                    foreach ($model->bookingUsers as $bookingUser) {
                        $resident = $bookingUser->resident;
                        $programTitle = $resident->wellnessProgram ? $resident->wellnessProgram->title : 'Не выбрана';
                        $output .= '<div class="d-flex w-100 justify-content-between">';
                        $output .= '<h6 class="mb-1">' . Html::encode($resident->surname . ' ' . $resident->name . ' ' . $resident->patronymic) . '</h6>';
                        $output .= '</div>';
                        $output .= '<p class="mb-1"><strong>Программа:</strong> ' . Html::encode($programTitle) . '</p>';
                    }
                    $output .= '</div>';
                    return $output;
                }
            ]
        ],
    ]) ?>

</div>