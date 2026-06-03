<?php

use app\models\PayType;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var array $step1 */
/** @var app\models\Room $room */
/** @var array $guestPrograms */

$this->title = 'Данные гостей';
$this->registerCssFile('@web/css/form-booking.css');

$nights = (new \DateTime($step1['arrival_date']))
    ->diff(new \DateTime($step1['departure_date']))->days;
$total   = $nights * $room->price_per_day;
$prepay  = round($total * 0.3);

// Получаем названия программ для отображения
$programNames = [];
if (!empty($guestPrograms)) {
    foreach ($guestPrograms as $i => $progId) {
        if ($progId && $progId != 0) {
            $prog = \app\models\WellnessProgram::findOne($progId);
            $programNames[$i] = $prog ? $prog->title : '';
        } else {
            $programNames[$i] = 'Без программы';
        }
    }
}
?>

<div class="booking-page">
    <div class="booking-wrap">

        <!-- Шапка -->
        <div class="bk-header">
            <div class="bk-title">Данные гостей</div>
            <div class="bk-sub">Заполните информацию о каждом госте</div>
        </div>

        <!-- Прогресс -->
        <div class="bk-progress">
            <div class="bp done"></div>
            <div class="bp done"></div>
            <div class="bp active"></div>
            <div class="bp"></div>
        </div>
        <div class="bk-progress-labels">
            <span>Детали</span>
            <span>Программы</span>
            <span class="active-lbl">Гости</span>
            <span>Оплата</span>
        </div>

        <?php $form = ActiveForm::begin([
            'id'          => 'guests-data-form',
            'fieldConfig' => [
                'template'     => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'f-label'],
                'errorOptions' => ['class' => 'invalid-feedback d-block'],
            ],
        ]); ?>

        <!-- Блоки гостей -->
        <?php for ($i = 0; $i < $step1['guests_count']; $i++): ?>
            <div class="guest-block">
                <div class="guest-block-header">
                    <div class="guest-num"><?= $i + 1 ?></div>
                    <div>
                        <div class="guest-block-name">Гость <?= $i + 1 ?></div>
                        <?php if (!empty($programNames[$i])): ?>
                            <div class="guest-block-prog"><?= Html::encode($programNames[$i]) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="g-row-3">
                    <?= Html::activeTextInput($model, "guests[$i][surname]", [
                        'class'       => 'form-control',
                        'placeholder' => 'Фамилия',
                        'id'          => "guest-{$i}-surname",
                    ]) ?>
                    <?= Html::activeTextInput($model, "guests[$i][name]", [
                        'class'       => 'form-control',
                        'placeholder' => 'Имя',
                        'id'          => "guest-{$i}-name",
                    ]) ?>
                    <?= Html::activeTextInput($model, "guests[$i][patronymic]", [
                        'class'       => 'form-control',
                        'placeholder' => 'Отчество',
                        'id'          => "guest-{$i}-patronymic",
                    ]) ?>
                </div>

                <?= $form->field($model, "guests[$i][birth_date]")
                    ->label('Дата рождения')
                    ->textInput(['type' => 'date']) ?>
            </div>
        <?php endfor; ?>

        <!-- Итого -->
        <div class="bk-section">
            <div class="bk-section-title">
                <div class="bk-icon"><i class="ti ti-clipboard-list"></i></div> Итоги бронирования
            </div>
            <div class="booking-summary-row">
                <span class="lbl">Номер</span>
                <span class="val"><?= Html::encode($room->roomType->name) ?>, <?= $room->number_guests ?>-местный</span>
            </div>
            <div class="booking-summary-row">
                <span class="lbl">Даты</span>
                <span class="val">
                    <?= Yii::$app->formatter->asDate($step1['arrival_date'], 'php:d.m.Y') ?>
                    —
                    <?= Yii::$app->formatter->asDate($step1['departure_date'], 'php:d.m.Y') ?>
                </span>
            </div>
            <div class="booking-summary-row">
                <span class="lbl">Количество ночей</span>
                <span class="val"><?= $nights ?></span>
            </div>
            <div class="booking-summary-row">
                <span class="lbl">Гостей</span>
                <span class="val"><?= $step1['guests_count'] ?> человека</span>
            </div>
            <div class="booking-summary-divider"></div>
            <div class="booking-summary-row">
                <span class="lbl">Полная стоимость</span>
                <span class="val"><?= number_format($total, 0, '.', ' ') ?> ₽</span>
            </div>
        </div>

        <!-- Способ оплаты -->
        <div class="bk-section">
            <div class="bk-section-title">
                <div class="bk-icon"><i class="ti ti-credit-card"></i></div> Способ оплаты
            </div>
            <div class="pay-options" id="payOptions">

                <?php
                // Иконки под названия — добавьте свои если названия отличаются
                $payIcons = [
                    'qr'   => '📱',
                    'card' => '💳',
                ];
                $payDescriptions = [
                    'qr'   => 'Сканируйте и оплатите через СБП',
                    'card' => 'Введите данные карты онлайн',
                ];

                $first = true;
                foreach (PayType::getItems() as $id => $name):
                    // Ключ для иконки — ищем подстроку в названии
                    $icon = '💳';
                    $desc = '';
                    foreach ($payIcons as $keyword => $ico) {
                        if (mb_stripos($name, $keyword) !== false) {
                            $icon = $ico;
                            $desc = $payDescriptions[$keyword] ?? '';
                            break;
                        }
                    }
                ?>
                    <label class="pay-opt <?= $first ? 'pay-selected' : '' ?>">
                        <input type="radio"
                            name="<?= Html::getInputName($model, 'pay_type') ?>"
                            value="<?= $id ?>"
                            <?= $first ? 'checked' : '' ?>>
                        <div class="pay-opt-icon"><?= $icon ?></div>
                        <div class="pay-opt-content">
                            <div class="pay-opt-name"><?= Html::encode($name) ?></div>
                            <?php if ($desc): ?>
                                <div class="pay-opt-desc"><?= $desc ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="pay-dot"></div>
                    </label>
                <?php $first = false;
                endforeach; ?>

            </div>
        </div>

        <div class="bk-total">
            <div>
                <div class="bk-total-lbl">К оплате сейчас (30%)</div>
                <div class="bk-total-note">Остаток оплатите при заезде</div>
            </div>
            <div class="bk-total-price">
                <?= number_format($prepay, 0, '.', ' ') ?> ₽
                <small><?= number_format($total, 0, '.', ' ') ?> ₽ всего</small>
            </div>
        </div>

        <?= Html::submitButton('Оплатить →', ['class' => 'btn-booking-next']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php $this->registerJs("
// Переключение способов оплаты
document.querySelectorAll('#payOptions .pay-opt').forEach(function(opt) {
    opt.addEventListener('click', function() {
        document.querySelectorAll('#payOptions .pay-opt').forEach(o => o.classList.remove('pay-selected'));
        this.classList.add('pay-selected');
        this.querySelector('input[type=radio]').checked = true;
    });
});
"); ?>