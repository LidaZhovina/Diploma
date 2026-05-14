<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\JqueryAsset;

/** @var yii\web\View $this */
/** @var app\models\Booking $model */
/** @var yii\widgets\ActiveForm $form */
?>
<h2 class="text-center">Заселение по бронированию на <?= Yii::$app->formatter->asDate($booking->arrival_date, 'php:d.m.Y') . " - " . Yii::$app->formatter->asDate($booking->departure_date, 'php:d.m.Y') ?></h2>
<h4><strong>Номер:</strong> <?= $booking->room->roomType->name . " " . $booking->room->number_guests . "-" . "местный" ?></h4>

<div class="booking-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php foreach ($residents as $i => $resident): ?>
        <?php $profile = $profiles[$i]; ?>
        <div class="card mb-3">
            <div class="card-body">
                <strong><?= Html::encode($resident->surname . ' ' . $resident->name . ($resident->patronymic ? ' ' . $resident->patronymic : '')) ?></strong>
                <?php if ($resident->is_main_guest): ?>
                    <span class="badge rounded-pill bg-info ms-2">Главный</span>
                <?php endif; ?>


                <p><strong>Дата рождения:</strong> <?= Yii::$app->formatter->asDate($resident->birth_date, 'php:d.m.Y') ?></p>
                <?= $form->field($profile, "[$i]passport_series")->textInput(['maxlength' => 10])->widget(\yii\widgets\MaskedInput::class, [
                'mask' => '9999',
            ]) ?>
                <?= $form->field($profile, "[$i]passport_number")->textInput(['maxlength' => 20])->widget(\yii\widgets\MaskedInput::class, [
                'mask' => '999999',
            ]) ?>
                <?= $form->field($profile, "[$i]phone")->textInput(['maxlength' => 20])->widget(\yii\widgets\MaskedInput::class, [
                'mask' => '8(999)999-99-99',
            ]) ?>
            </div>
        </div>
    <?php endforeach; ?>



    <div class="form-group d-flex justify-content-between">
        <?= Html::submitButton('Заселить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Назад', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


