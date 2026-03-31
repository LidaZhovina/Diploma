<?php

use yii\bootstrap5\Html;
?>
<div class="card my-3">
    <div class="card-body">
        <div>
            <spam class="fw-bold text-secondary">Пользователь: </spam> <?= $model->surname . " " . $model->name ?>
        </div>
        <div>
            <span class="fw-bold text-secondary">Роль: </span> <?= $model->role->alias ?>
        </div>
        <div class="mt-2">
            <?= Html::a('Подробнее', ['view', 'id' => $model->id], ['class' => 'btn register']) ?>
        </div>
    </div>
</div>