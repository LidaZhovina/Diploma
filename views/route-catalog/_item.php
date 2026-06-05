<?php

use yii\helpers\Html;

/** @var app\models\Route $model */
?>
<div class="wellness-card" style="grid-template-columns: 280px 1fr;">

    <div class="wellness-img-wrap">
        <?php if ($model->imageUrl): ?>
            <img src="<?= Html::encode($model->imageUrl) ?>" alt="<?= Html::encode($model->name) ?>">
        <?php else: ?>
            <img src="/img/no-image.jpg" alt="Нет изображения">
        <?php endif; ?>
        <div class="wellness-duration-badge">
            <?= Html::encode($model->level->title ?? '') ?>
        </div>
    </div>

    <div class="wellness-body">
        <div>
            <div class="wellness-title"><?= Html::encode($model->name) ?></div>
            <div class="wellness-desc"><?= Html::encode(mb_substr($model->description, 0, 200)) ?>…</div>
        </div>
        <div class="wellness-footer">
            <div class="wellness-meta">
                Протяжённость: <span><?= $model->length ?> км</span>
                &nbsp;·&nbsp;
                Длительность: <span><?= Html::encode($model->duration) ?></span>
            </div>
            <?= Html::a('Подробнее', ['route-catalog/view', 'id' => $model->id], ['class' => 'btn-wellness']) ?>
        </div>
    </div>

</div>