<?php

use yii\bootstrap5\Html;
?>
<div class="card my-3 w-100">
    <div class="row g-0 h-100">
        <div class="col-md-4 d-flex align-items-stretch">
            <?php if ($model->imageUrl): ?>
                <img src="<?= $model->imageUrl ?>" class="card-img" alt="<?= Html::encode($model->title) ?>" style="width: 100%; height: 100%; object-fit: cover;">
            <?php else: ?>
                <img src="/web/img/no-image.jpg" class="card-img" alt="Нет изображения" style="width: 100%; height: 100%; object-fit: cover;">
            <?php endif; ?>
        </div>
        <div class="col-md-8 d-flex flex-column">
            <div class="card-body d-flex flex-column h-100">
                <h4 class="card-title"><?= $model->title ?></h4>
                <div><strong>Длительность:</strong> <?= $model->duration ?></div>
                <div><strong>Описание:</strong> <?= $model->description ?></div>
                <div class="text-center mt-3">
                    <?= Html::a('Подробнее', ['view', 'id' => $model->id], ['class' => 'btn register']) ?>
                </div>
            </div>
        </div>
    </div>
</div>