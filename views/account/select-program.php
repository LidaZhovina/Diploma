<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var int $guestsCount */
/** @var app\models\WellnessProgram[] $programs */

$this->title = 'Выбор программы';
$this->registerCssFile('@web/css/form-booking.css');
?>

<div class="booking-page">
    <div class="booking-wrap">

        <!-- Шапка -->
        <div class="bk-header">
            <div class="bk-title">Программы</div>
            <div class="bk-sub">Выберите оздоровительную программу для каждого гостя</div>
        </div>

        <!-- Прогресс -->
        <div class="bk-progress">
            <div class="bp done"></div>
            <div class="bp active"></div>
            <div class="bp"></div>
            <div class="bp"></div>
        </div>
        <div class="bk-progress-labels">
            <span>Детали</span>
            <span class="active-lbl">Программы</span>
            <span>Гости</span>
            <span>Оплата</span>
        </div>

        <?php $form = ActiveForm::begin(['id' => 'program-selection-form']); ?>

        <div class="bk-section">
            <div class="bk-section-title">
                <div class="bk-icon"><i class="ti ti-user"></i></div> Выберите гостя
            </div>

            <!-- Переключатель гостей -->
            <div class="guest-switcher" id="guestSwitcher">
                <?php for ($i = 0; $i < $guestsCount; $i++): ?>
                    <button type="button"
                        class="gs-btn <?= $i === 0 ? 'active' : '' ?>"
                        data-target="guest-tab-<?= $i ?>">
                        Гость <?= $i + 1 ?>
                    </button>
                <?php endfor; ?>
            </div>

            <!-- Вкладки гостей -->
            <?php for ($i = 0; $i < $guestsCount; $i++): ?>
                <div id="guest-tab-<?= $i ?>"
                    class="guest-prog-tab <?= $i !== 0 ? 'd-none' : '' ?>">

                    <div class="prog-grid">
                        <?php foreach ($programs as $program): ?>
                            <?php
                            $inputId  = "program_{$i}_{$program->id}";
                            $checked  = (Yii::$app->session->get('guest_programs')[$i] ?? null) == $program->id;
                            ?>
                            <input type="radio"
                                class="prog-radio"
                                name="program[<?= $i ?>]"
                                value="<?= $program->id ?>"
                                id="<?= $inputId ?>"
                                <?= $checked ? 'checked' : '' ?>>
                            <label class="prog-card" for="<?= $inputId ?>">
                                <div class="prog-name"><?= Html::encode($program->title) ?></div>
                                <div class="prog-desc"><?= Html::encode(mb_substr($program->description, 0, 80)) ?>…</div>
                                <span class="prog-duration"><?= Html::encode($program->duration) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>

                </div>
            <?php endfor; ?>
        </div>

        <?= Html::submitButton('Далее →', ['class' => 'btn-booking-next']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php $this->registerJs("
document.querySelectorAll('#guestSwitcher .gs-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('#guestSwitcher .gs-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.guest-prog-tab').forEach(t => t.classList.add('d-none'));
        this.classList.add('active');
        document.getElementById(this.dataset.target).classList.remove('d-none');
    });
});
"); ?>