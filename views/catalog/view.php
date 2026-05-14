<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Room $model */

$this->title = $model->roomType->name . " " . $model->number_guests . " " . "местный";;
\yii\web\YiiAsset::register($this);
?>
<div class="room-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="text-end">
        <?= Html::a('Назад', ['/catalog/index', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <div class="row mt-3">
        <div class="col-12">
            <h4>Фотографии номера</h4>
            <?php if ($model->roomImages): ?>
                <div class="row">
                    <?php foreach ($model->roomImages as $image): ?>
                        <div class="col-md-3 mb-3">
                            <?= Html::img(Yii::getAlias('@web/' . $image->image), [
                                'class' => 'img-thumbnail',
                                'style' => 'width: 100%; height: 200px; object-fit: cover;'
                            ]) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Нет изображений</p>
            <?php endif; ?>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'room_type_id',
                'value' => $model->roomType->name
            ],
            [
                'attribute' => 'price_per_day',
                'value' => $model->price_per_day . "₽",
            ],
            'number_guests',
            'description:ntext',
            // 'number',
            // 'floor',
        ],
    ]) ?>

</div>