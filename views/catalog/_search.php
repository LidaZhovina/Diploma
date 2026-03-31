<?php

use app\models\RoomType;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CatalogSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="room-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="row g-3 align-items-end">
        <div class="col-sm">
            <?= $form->field($model, 'number_guests', [
                'template' => '
                    <div class="form-group">
                        {label}
                            <div class="input-group">
                                {input}
                                <button class="btn btn-primary btn-number-minus" id="minus"  type="button">–</button>
                                <button class="btn btn-primary btn-number-plus" id="plus"  type="button">+</button>
                            </div>
                        {error}{hint}
                    </div>',
            ])->textInput([
                // 'type' => 'number',
                'class' => 'form-control field-number-guests',
                'min' => 1,           // минимальное значение
                'max' => 5,           // максимальное значение
                'step' => 1,
                'value' => 1,          // значение по умолчанию
                'readonly' => true,
            ]) ?>
        </div>
        <div class="col-sm">
            <?= $form->field($model, 'room_type_id')->dropDownList(RoomType::getTypes(), ['prompt' => '---']) ?>
        </div>
        <div class="col-sm">
            <?= $form->field($model, 'arrival_date')->textInput(['type' => 'date']) ?>
        </div>
        <div class="col-sm">
            <?= $form->field($model, 'departure_date')->textInput(['type' => 'date']) ?>
        </div>

        <div class="form-group col-auto mb-3">
            <?= Html::submitButton('Найти', ['class' => 'btn register']) ?>
            <?= Html::a('Сбросить', 'index', ['class' => 'btn btn-outline-secondary']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>