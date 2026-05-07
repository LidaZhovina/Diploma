<?php
use yii\bootstrap5\Html;
/** @var \app\models\RouteResident|\app\models\Route $model */
$isBooked = $model instanceof \app\models\RouteResident;

if ($isBooked) {
    $route = $model->route;
    $resident = $model->resident;
} else {
    $route = $model;
}
?>
<div class="card my-3 w-100">
    <div class="card-header">
        <?php if ($route->imageUrl): ?>
            <img src="<?= $route->imageUrl ?>" class="card-img-top" alt="<?= Html::encode($route->name) ?>" style="max-height: 300px; object-fit: cover;">
        <?php else: ?>
            <img src="/web/img/no-image.jpg" class="card-img-top" alt="Нет изображения" style="max-height: 300px; object-fit: cover;">
        <?php endif; ?>
    </div>
    <div class="card-body">
        <h5 class="card-title text-center"><?= $route->name ?></h5>
        <div>
            <span class="fw-bold">Дата и время начала: </span> 
            <?= Yii::$app->formatter->asDate($route->date_start, 'php:d.m.Y') . ' ' . Yii::$app->formatter->asTime($route->time_start, 'php:H:i') ?>
        </div>
        <div>
            <span class="fw-bold">Сложность: </span> <?= $route->level->title ?>
        </div>
        <div>
            <span class="fw-bold">Количество участников: </span> <?= $route->number_participant ?>
        </div>
        <div>
            <span class="fw-bold">Свободных мест: </span> <?= $route->number_participant - $route->getRouteResidents()->count() ?>
        </div>

        <?php if ($isBooked): ?>
            <div class="mt-2">
                <span class="fw-bold">Вы записаны как:</span> <?= Html::encode($resident->surname . ' ' . $resident->name) ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="card-footer d-flex justify-content-between">
        <?php if ($isBooked): ?>
            <?= Html::a('Отменить запись', ['account/cancel-route', 'route_id' => $route->id, 'resident_id' => $resident->id], [
                'class' => 'btn btn-danger',
                'data-method' => 'post',
                'data-confirm' => 'Отменить запись для этого гостя?'
            ]) ?>
        <?php else: ?>
            <?= Html::a('Записаться', ['account/book-route', 'id' => $route->id], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
        <?= Html::a('Подробнее', ['route/view', 'id' => $route->id], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>