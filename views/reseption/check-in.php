<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Booking $booking */
/** @var app\models\Resident[] $residents */
/** @var app\models\GuestProfile[] $profiles */

$this->title = 'Заселение гостей';
$this->registerCssFile('@web/css/reception.css');
?>

<div class="reception-page">
    <div class="reception-container" style="max-width: 760px;">

        <!-- Заголовок -->
        <div class="reception-header">
            <div class="reception-title">
                <div class="reception-title-icon">
                    <i class="ti ti-home-check"></i>
                </div>
                Заселение гостей
            </div>
            <?= Html::a(
                '<i class="ti ti-arrow-left"></i> Назад к списку',
                ['index'],
                ['class' => 'btn-bk btn-bk--ghost']
            ) ?>
        </div>

        <!-- Плашка с инфо о бронировании -->
        <div class="checkin-booking-bar">
            <div class="checkin-bar-item">
                <span class="checkin-bar-label">Тип номера</span>
                <span class="checkin-bar-value">
                    <?= Html::encode($booking->room->roomType->name) ?>
                    <?= $booking->room->number_guests ?>-местный
                </span>
            </div>
            <div class="checkin-bar-item">
                <span class="checkin-bar-label">Комната</span>
                <span class="checkin-bar-value">№ <?= Html::encode($booking->room->number) ?></span>
            </div>
            <div class="checkin-bar-item">
                <span class="checkin-bar-label">Заезд</span>
                <span class="checkin-bar-value">
                    <?= Yii::$app->formatter->asDate($booking->arrival_date, 'php:d.m.Y') ?>
                </span>
            </div>
            <div class="checkin-bar-item">
                <span class="checkin-bar-label">Выезд</span>
                <span class="checkin-bar-value">
                    <?= Yii::$app->formatter->asDate($booking->departure_date, 'php:d.m.Y') ?>
                </span>
            </div>
            <div class="checkin-bar-item">
                <span class="checkin-bar-label">Гостей</span>
                <span class="checkin-bar-value"><?= $booking->amount_residents ?></span>
            </div>
        </div>

        <!-- Форма -->
        <?php $form = ActiveForm::begin([
            'options' => ['novalidate' => true],
        ]); ?>

        <div class="checkin-guests">
            <?php foreach ($residents as $i => $resident): ?>
                <?php $profile = $profiles[$i]; ?>

                <div class="checkin-guest-card">

                    <!-- Шапка карточки гостя -->
                    <div class="checkin-guest-header">
                        <div class="checkin-guest-num"><?= $i + 1 ?></div>
                        <div class="checkin-guest-info">
                            <div class="checkin-guest-name">
                                <?= Html::encode(
                                    $resident->surname . ' '
                                        . $resident->name
                                        . ($resident->patronymic ? ' ' . $resident->patronymic : '')
                                ) ?>
                                <?php if ($resident->is_main_guest): ?>
                                    <span class="checkin-main-badge">
                                        <i class="ti ti-star-filled"></i> Главный гость
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="checkin-guest-meta">
                                <span>
                                    <i class="ti ti-calendar"></i>
                                    <?= Yii::$app->formatter->asDate($resident->birth_date, 'php:d.m.Y') ?>
                                </span>
                                <?php if ($resident->wellnessProgram): ?>
                                    <span>
                                        <i class="ti ti-heart-rate-monitor"></i>
                                        <?= Html::encode($resident->wellnessProgram->title) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Поля формы -->
                    <div class="checkin-guest-body">
                        <div class="checkin-fields-row">
                            <div class="checkin-field">
                                <?= $form->field($profile, "[$i]passport_series")
                                    ->label('Серия паспорта')
                                    ->widget(\yii\widgets\MaskedInput::class, [
                                        'mask'    => '9999',
                                        'options' => ['class' => 'checkin-input', 'placeholder' => '1234'],
                                    ]) ?>
                            </div>
                            <div class="checkin-field">
                                <?= $form->field($profile, "[$i]passport_number")
                                    ->label('Номер паспорта')
                                    ->widget(\yii\widgets\MaskedInput::class, [
                                        'mask'    => '999999',
                                        'options' => ['class' => 'checkin-input', 'placeholder' => '567890'],
                                    ]) ?>
                            </div>
                            <div class="checkin-field">
                                <?= $form->field($profile, "[$i]phone")
                                    ->label('Телефон')
                                    ->widget(\yii\widgets\MaskedInput::class, [
                                        'mask'    => '8(999)999-99-99',
                                        'options' => [
                                            'class'       => 'checkin-input',
                                            'placeholder' => '8(999)000-00-00',
                                        ],
                                    ]) ?>
                            </div>
                        </div>
                    </div>

                </div>

            <?php endforeach; ?>
        </div>

        <!-- Кнопки -->
        <div class="checkin-footer">
            <?= Html::submitButton(
                '<i class="ti ti-home-check"></i> Заселить гостей',
                ['class' => 'btn-checkin-submit']
            ) ?>
            <?= Html::a(
                '<i class="ti ti-arrow-left"></i> Отмена',
                ['index'],
                ['class' => 'btn-bk btn-bk--ghost']
            ) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>