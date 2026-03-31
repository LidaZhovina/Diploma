<?php

use yii\helpers\Html;
?>
<div class="card my-3 w-100">
    <div class="card-header">
        <?php if ($model->imageUrl): ?>
            <img src="<?= $model->imageUrl ?>" class="card-img" alt="<?= Html::encode($model->title) ?>" style="max-height: 300px; max-weight: 300px; object-fit: cover;">
        <?php else: ?>
            <img src="/web/img/no-image.jpg" class="card-img-top" alt="Нет изображения">
        <?php endif; ?>
    </div>
    <div class="card-body">
        <h5 class="card-title"><?= $model->title ?></h5>

    </div>
    <div class="card-footer">
        <!-- <?= Html::a('Записаться', ['index', 'id' => $model->id], ['class' => 'btn register']) ?> -->
        <?= Html::a('Подробнее', ['view', 'id' => $model->id], ['class' => 'btn register']) ?>
    </div>
</div>