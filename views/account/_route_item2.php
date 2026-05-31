<?php
use yii\bootstrap5\Html;
/** @var app\models\Route $route */
/** @var bool $isBooked */
/** @var array $residents */ // для isBooked = true
?>
<div class="card my-3 w-100">
    <div class="card-header">
        <h5 class="card-title text-center"><?= $route->name ?></h5>
    </div>
    <div class="card-body">

        <div>
            <span class="fw-bold">Дата и время начала: </span>
            <?= Yii::$app->formatter->asDate($route->date_start, 'php:d.m.Y') . ' ' . Yii::$app->formatter->asTime($route->time_start, 'php:H:i') ?>
        </div>

        <?php if ($isBooked): ?>
            <div class="mt-2">
                <span class="fw-bold">Участник(и):</span>
                <ul>
                    <?php foreach ($residents as $resident): ?>
                        <li>
                            <?= Html::encode($resident['name']) ?>
                            <?= Html::a('Отменить', ['account/cancel-route', 'route_id' => $route->id, 'resident_id' => $resident['id']], [
                                'class' => 'btn btn-sm btn-outline-danger ms-2 mb-2',
                                'data-method' => 'post',
                                'data-confirm' => 'Вы точно хотите отменить запись?'
                            ]) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <?php if ($isBooked): ?>
        <?php else: ?>
            <?= Html::a('Записаться', ['account/book-route', 'id' => $route->id], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
        <?= Html::a('Подробнее', ['route/view', 'id' => $route->id], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>