<?php

use yii\helpers\Html;
use yii\helpers\Url;
/** @var app\models\CatalogSearch $model */


$images = $model->roomImages;
?>

<div class="room-card">

    <div class="room-img-wrap">
        <?php if (!empty($images)): ?>
            <div id="carousel-<?= $model->id ?>" class="carousel slide h-100" data-bs-ride="false">
                <div class="carousel-inner h-100">
                    <?php foreach ($images as $index => $image): ?>
                        <div class="carousel-item h-100 <?= $index === 0 ? 'active' : '' ?>">
                            <img src="<?= Url::to(['room/display-image', 'id' => $image->id]) ?>"
                                 class="d-block w-100 h-100"
                                 style="object-fit:cover"
                                 alt="Фото номера">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($images) > 1): ?>
                    <button class="carousel-control-prev" type="button"
                            data-bs-target="#carousel-<?= $model->id ?>" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button"
                            data-bs-target="#carousel-<?= $model->id ?>" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <img src="/img/no-image.jpg" class="d-block w-100 h-100"
                 style="object-fit:cover" alt="Нет фото">
        <?php endif; ?>

        <div class="price-badge">
            <?= $model->price_per_day ?> ₽ <span>/ ночь</span>
        </div>
    </div>

    <div class="card-body">
        <h5 class="card-title">
            <?= Html::encode($model->roomType->name) ?>
            <?= $model->number_guests ?>-местный
        </h5>

        <div class="room-features">
            <div class="room-feat">
                <div class="feat-icon">Wi</div> Wi-Fi
            </div>
            <div class="room-feat">
                <div class="feat-icon">TV</div> Телевизор
            </div>
            <div class="room-feat">
                <div class="feat-icon">Br</div> Питание
            </div>
        </div>
    </div>

    <div class="card-footer">
        <?= Yii::$app->user->identity?->isClient
            ? Html::a('Забронировать', ['account/create', 'room_id' => $model->id], ['class' => 'btn register'])
            : '' ?>
        <?= Html::a('Подробнее', ['view', 'id' => $model->id], ['class' => 'btn details']) ?>
    </div>

</div>