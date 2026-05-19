<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Booking $booking */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-mb-6">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Оплата банковской картой</h3>
                </div>
                <div class="card-body">
                    <p><strong>Бронирование №<?= $booking->id ?></strong></p>
                    <p><strong>Сумма предоплаты (30%):</strong> <?= $booking->payment_amount ?> руб. <?= "(" ?>Остаток оплатите при заезде.<?= ")" ?></p>

                    <div class="card-form">

                        <?php $form = ActiveForm::begin(['id' => 'card-payment-form', 'method' => 'post']); ?>

                        <?= $form->field($model, 'card_number')->textInput([
                            'placeholder' => '1234 5678 9012 3456',
                            'maxlength' => 19,
                            'class' => 'form-control card-number'
                        ])->label('Номер карты') ?>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'expiry')->textInput([
                                    'placeholder' => 'ММ/ГГ',
                                    'maxlength' => 5,
                                    'class' => 'form-control expiry'
                                ])->label('Срок действия') ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'cvv')->textInput([
                                    'placeholder' => '123',
                                    'maxlength' => 3,
                                    'type' => 'password',
                                    'class' => 'form-control cvv'
                                ])->label('CVV') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <?= Html::submitButton('Оплатить', ['class' => 'btn btn-success']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>