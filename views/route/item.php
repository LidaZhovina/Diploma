<?php

/** @var app\models\Route $model */

use yii\helpers\Html;
?>
<div class="card my-3 w-100">
    <div class="card-header">
        <?php if ($model->imageUrl): ?>
            <img src="<?= $model->imageUrl ?>" class="card-img-top" alt="<?= Html::encode($model->name) ?>" style="max-height: 300px; object-fit: cover;">
        <?php else: ?>
            <img src="/web/img/no-image.jpg" class="card-img-top" alt="Нет изображения" style="max-height: 300px; object-fit: cover;">
        <?php endif; ?>
    </div>
    <div class="card-body">
        <h5 class="card-title"><?= $model->name ?></h5>
        <div>
            <span class="fw-bold">Дата и время начала: </span>
            <?= Yii::$app->formatter->asDate($model->date_start, 'php:d.m.Y') . ' ' . Yii::$app->formatter->asTime($model->time_start, 'php:H:i') ?>
        </div>
        <div>
            <span class="fw-bold">Сложность: </span> <?= $model->level->title ?>
        </div>
        <div>
            <span class="fw-bold">Количество участников: </span> <?= $model->number_participant ?>
        </div>
        <div>
            <span class="fw-bold">Свободных мест: </span> <?= $model->number_participant - $model->getRouteResidents()->count() ?>
        </div>
    </div>
    <div class="card-footer">
        <?= Html::a('Подробнее', ['view', 'id' => $model->id], ['class' => 'btn register']) ?>
        <?= $model->routeStatus->alias === 'new'
            ? Html::a('Закончить маршрут', ['change-status', 'id' => $model->id, 'alias' => 'past'], ['class' => 'btn btn-outline-primary', 'data-method' => 'post'])
            : '' ?>
    </div>
</div>