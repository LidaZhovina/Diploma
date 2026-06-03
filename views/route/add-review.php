<?php

use kartik\rating\StarRating;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Review $model */
/** @var app\models\Route $route */
/** @var app\models\Resident[] $residentsCanReview */

$this->title = 'Отзыв о маршруте';

// ── Фикс стилей: правильные имена файлов и порядок зависимостей ──
$this->registerCssFile('@web/css/form-booking.css');
$this->registerCssFile('@web/css/rewiews.css');
?>

<div class="booking-page">
    <div class="booking-wrap">

        <!-- Кнопка назад -->
        <?= Html::a(
            '← Назад к маршруту',
            ['view', 'id' => $route->id],
            [
                'style' => 'display:inline-flex;align-items:center;gap:6px;color:#888;'
                    . 'font-size:13px;font-weight:600;text-decoration:none;'
                    . 'margin-bottom:20px;font-family:Montserrat,sans-serif;'
                    . 'transition:color .15s;',
                'class' => 'bv-back'
            ]
        ) ?>

        <!-- Шапка -->
        <div class="bk-header">
            <div class="bk-title">Отзыв о маршруте</div>
            <div class="bk-sub"><?= Html::encode($route->name) ?></div>
        </div>

        <!-- Краткая инфо о маршруте -->
        <div class="bk-section" style="margin-bottom:12px">
            <div class="bk-section-title">
                <div class="bk-icon">🏔</div> Маршрут
            </div>
            <div style="display:flex;gap:24px;flex-wrap:wrap;font-size:13px;color:#555">
                <div>
                    <span style="color:#888;font-size:11px;text-transform:uppercase;
                                 letter-spacing:.5px;font-weight:700;display:block;margin-bottom:2px">
                        Дата
                    </span>
                    <?= Yii::$app->formatter->asDate($route->date_start, 'php:d.m.Y') ?>
                </div>
                <div>
                    <span style="color:#888;font-size:11px;text-transform:uppercase;
                                 letter-spacing:.5px;font-weight:700;display:block;margin-bottom:2px">
                        Сложность
                    </span>
                    <?= Html::encode($route->level->title ?? '') ?>
                </div>
                <div>
                    <span style="color:#888;font-size:11px;text-transform:uppercase;
                                 letter-spacing:.5px;font-weight:700;display:block;margin-bottom:2px">
                        Длительность
                    </span>
                    <?= Html::encode($route->duration) ?>
                </div>
            </div>
        </div>

        <!-- Форма -->
        <div class="bk-section">

            <?php $form = ActiveForm::begin([
                'id'          => 'route-review-form',
                'fieldConfig' => [
                    'template'     => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'f-label'],
                    'errorOptions' => ['class' => 'invalid-feedback d-block'],
                ],
            ]); ?>

            <?= $form->field($model, 'user_id')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'route_id')->hiddenInput()->label(false) ?>

            <!-- Выбор гостя (если их несколько) -->
            <?php if (count($residentsCanReview) > 1): ?>
                <div style="margin-bottom:20px">
                    <div class="bk-section-title" style="margin-bottom:10px">
                        <div class="bk-icon">👤</div> Кто оставляет отзыв?
                    </div>
                    <div class="pay-options" id="residentOptions">
                        <?php foreach ($residentsCanReview as $i => $resident): ?>
                            <?php
                            $initials = mb_strtoupper(
                                mb_substr($resident->surname, 0, 1)
                                    . mb_substr($resident->name, 0, 1)
                            );
                            $inputId  = 'resident_' . $resident->id;
                            ?>
                            <label class="pay-opt <?= $i === 0 ? 'pay-selected' : '' ?>" for="<?= $inputId ?>">
                                <input type="radio"
                                    name="<?= Html::getInputName($model, 'resident_id') ?>"
                                    id="<?= $inputId ?>"
                                    value="<?= $resident->id ?>"
                                    <?= $i === 0 ? 'checked' : '' ?>>
                                <div class="pay-opt-icon" style="font-size:16px;font-weight:800;color:#3B4593">
                                    <?= Html::encode($initials) ?>
                                </div>
                                <div class="pay-opt-content">
                                    <div class="pay-opt-name">
                                        <?= Html::encode($resident->surname . ' ' . $resident->name
                                            . ($resident->patronymic ? ' ' . $resident->patronymic : '')) ?>
                                    </div>
                                    <div class="pay-opt-desc">Гость бронирования</div>
                                </div>
                                <div class="pay-dot"></div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <!-- Один гость — скрытое поле -->
                <?= Html::hiddenInput(
                    Html::getInputName($model, 'resident_id'),
                    $residentsCanReview[0]->id
                ) ?>
                <div style="margin-bottom:16px;padding:12px 16px;background:#F6F7F8;
                            border-radius:10px;font-size:13px;color:#555">
                    Отзыв от имени: <strong>
                        <?= Html::encode(
                            $residentsCanReview[0]->surname . ' '
                                . $residentsCanReview[0]->name
                                . ($residentsCanReview[0]->patronymic
                                    ? ' ' . $residentsCanReview[0]->patronymic
                                    : '')
                        ) ?>
                    </strong>
                </div>
            <?php endif; ?>

            <!-- Оценка звёздами -->
            <div class="review-stars-block" style="margin-bottom:20px">
                <div class="bk-section-title" style="margin-bottom:10px">
                    <div class="bk-icon">⭐</div> Ваша оценка
                </div>
                <?= $form->field($model, 'stars')->label(false)->widget(StarRating::class, [
                    'bsVersion'     => '5.x',
                    'pluginOptions' => [
                        'size'        => 'xl',
                        'showClear'   => false,
                        'showCaption' => false,
                        'min'         => 1,
                        'max'         => 5,
                        'step'        => 1,
                    ],
                ]) ?>
            </div>

            <!-- Текст отзыва -->
            <div>
                <div class="bk-section-title" style="margin-bottom:10px">
                    <div class="bk-icon">💬</div> Ваш отзыв
                </div>
                <?= $form->field($model, 'comment')->label(false)
                    ->textarea(['rows' => 5, 'placeholder' => 'Расскажите о маршруте: понравилось ли, что запомнилось, что взять с собой...']) ?>
            </div>

            <div style="margin-top:20px">
                <?= Html::submitButton('Отправить отзыв →', ['class' => 'btn-booking-next']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>

<?php $this->registerJs("
// Переключение выбора гостя (стиль оплаты)
document.querySelectorAll('#residentOptions .pay-opt').forEach(function(opt) {
    opt.addEventListener('click', function() {
        document.querySelectorAll('#residentOptions .pay-opt')
            .forEach(o => o.classList.remove('pay-selected'));
        this.classList.add('pay-selected');
        var radio = this.querySelector('input[type=radio]');
        if (radio) radio.checked = true;
    });
});
"); ?>