<?php

use yii\bootstrap5\Html;
?>
<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card my-3 w-100">
            <div class="row g-0 h-100">
                <?php foreach ($programs as $program): ?>
                    <div class="col-md-4 d-flex align-items-stretch">
                        <?php if ($program->imageUrl): ?>
                            <img src="<?= $program->imageUrl ?>" class="card-img" alt="<?= Html::encode($program->title) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <img src="/web/img/no-image.jpg" class="card-img" alt="Нет изображения" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8 d-flex flex-column">
                        <div class="card-body d-flex flex-column h-100">
                            <h4 class="card-title"><?= $program->title ?></h4>
                            <div><strong>Длительность:</strong> <?= $program->duration ?></div>
                            <div><strong>Описание:</strong> <?= $program->description ?></div>
                            <div class="text-center mt-5">
                                <?= Html::a('Выбрать', ['account/set-program', 'id' => $program->id], ['class' => 'btn register']) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>