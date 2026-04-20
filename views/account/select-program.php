<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var int $guestsCount */
/** @var app\models\WellnessProgram[] $programs */
?>

<h2 class="text-center">Выбор оздоровительной программы для каждого гостя</h2>

<?php $form = ActiveForm::begin(['id' => 'program-selection-form']); ?>

<div class="card text-center">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="guestTabs" role="tablist">
            <?php for ($i = 0; $i < $guestsCount; $i++): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $i === 0 ? 'active' : '' ?>"
                        id="tab-guest-<?= $i ?>"
                        data-bs-toggle="tab"
                        data-bs-target="#guest-<?= $i ?>"
                        type="button"
                        role="tab">
                        Гость <?= $i + 1 ?>
                    </button>
                </li>
            <?php endfor; ?>
        </ul>
    </div>
    <div class="card-body tab-content">
        <?php for ($i = 0; $i < $guestsCount; $i++): ?>
            <div class="tab-pane fade <?= $i === 0 ? 'show active' : '' ?>"
                id="guest-<?= $i ?>"
                role="tabpanel">
                <h5>Выберите программу для Гостя <?= $i + 1 ?></h5>
                <div class="row">
                    <?php foreach ($programs as $program): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 d-flex flex-column">
                                <div class="card-body d-flex flex-column flex-grow-1">
                                    <h5 class="card-title fw-bold"><?= Html::encode($program->title) ?></h5>
                                    <p class="card-text"><?= Html::encode($program->description) ?></p>
                                    <div class="btn-group mt-auto d-flex justify-content-center" role="group">
                                        <input type="radio" class="btn-check program-radio"
                                            name="program[<?= $i ?>]"
                                            value="<?= $program->id ?>"
                                            id="program_<?= $i ?>_<?= $program->id ?>"
                                            autocomplete="off"
                                            <?= (Yii::$app->session->get('guest_programs')[$i] ?? null) == $program->id ? 'checked' : '' ?>>
                                        <label class="btn btn-outline-primary w-100" for="program_<?= $i ?>_<?= $program->id ?>">
                                            Выбрать
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endfor; ?>
    </div>
</div>

<div class="mt-3 text-center">
    <?= Html::submitButton('Далее', ['class' => 'btn btn-primary btn-lg']) ?>
</div>

<?php ActiveForm::end(); ?>