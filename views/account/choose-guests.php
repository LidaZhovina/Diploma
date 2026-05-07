<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var app\models\Route $route */
/** @var app\models\Resident[] $residents */
?>
<div class="d-flex justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-center">Выберите гостей для записи на маршрут "<?= Html::encode($route->name) ?>"</h2>
                <p class="text-center">Маршрут состоится <?= Yii::$app->formatter->asDate($route->date_start, 'php:d.m.Y') ?> в <?= Yii::$app->formatter->asTime($route->time_start, 'php:H:i') ?></p>
                <p class="text-center">Свободных мест: <?= $route->number_participant - $route->getRouteResidents()->count() ?></p>

                <?php $form = ActiveForm::begin(); ?>
                <?php foreach ($residents as $resident): ?>
                    <div class="form-check">
                        <input type="checkbox" name="resident_ids[]" value="<?= $resident->id ?>" class="form-check-input" id="guest_<?= $resident->id ?>">
                        <label class="form-check-label" for="guest_<?= $resident->id ?>">
                            <?= Html::encode($resident->surname . ' ' . $resident->name . ($resident->patronymic ? ' ' . $resident->patronymic : '')) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
                <div class="form-group mt-3 d-flex justify-content-between">
                    <?= Html::submitButton('Записаться', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>