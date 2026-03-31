<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="card w-100 mb-3">
    <div class="card-header">
        <div id="carousel-<?= $model->id ?>" class="carousel slide" data-bs-ride="false">
            <div class="carousel-inner">
                <?php $images = $model->roomImages; ?>
                <?php if (!empty($images)): ?>
                    <?php foreach ($images as $index => $image): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="<?= Url::to(['room/display-image', 'id' => $image->id]) ?>" class="d-block w-100" alt="Фото номера" style="height: 200px; object-fit: cover;">
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="carousel-item active">
                        <img src="/web/img/no-image.jpg" class="d-block w-100" alt="Нет фото" style="height: 200px; object-fit: cover;">
                    </div>
                <?php endif; ?>
            </div>
            <?php if (count($images) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#carousel-<?= $model->id ?>" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Предыдущий</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carousel-<?= $model->id ?>" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Следующий</span>
                </button>
        </div>
    <?php endif; ?>
    </div>
    <div class="card-body">
        <!-- <h5 class="card-title">Номер <?= $model->number ?> (<?= $model->roomType->name ?>)</h5> -->
        <h5 class="card-title"><?= $model->roomType->name . " " . $model->number_guests . " " . "местный" ?></h5>
        <div><strong>Цена за ночь:</strong> <?= $model->price_per_day ?> руб.</div>
        <div><strong>Количество мест:</strong> <?= $model->number_guests ?></div>
    </div>
    <div class="card-footer">
        <?= Html::a('Подробнее', ['room/view', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </div>
</div>