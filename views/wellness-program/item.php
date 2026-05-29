<?php

use yii\bootstrap5\Html;
?>

<div class="wellness-card">

    <div class="wellness-img-wrap">
        <?php if ($model->imageUrl): ?>
            <img src="<?= $model->imageUrl ?>"
                alt="<?= Html::encode($model->title) ?>">
        <?php else: ?>
            <img src="/img/no-image.jpg" alt="Нет изображения">
        <?php endif; ?>
        <div class="wellness-duration-badge">
            <?= Html::encode($model->duration) ?>
        </div>
    </div>

    <div class="wellness-body">
        <div>
            <div class="wellness-title"><?= Html::encode($model->title) ?></div>
            <div class="wellness-desc"><?= Html::encode($model->description) ?></div>
        </div>
        <div class="wellness-footer">
            <div class="wellness-meta">
                Длительность: <span><?= Html::encode($model->duration) ?></span>
            </div>
            <?= Html::a('Подробнее', ['view', 'id' => $model->id], ['class' => 'btn-wellness']) ?>
        </div>
    </div>

</div>