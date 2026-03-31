<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Регистрация';
?>
<div class="site-register">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <div class="row justify-content-center">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'register-form']); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'surname') ?>

            <?= $form->field($model, 'name') ?>

            <?= $form->field($model, 'patronymic') ?>


            <div class="form-group d-flex justify-content-between">
                <?= Html::submitButton('Зарегестрироваться', ['class' => 'btn register', 'name' => 'register-button']) ?>
                <?= Html::a('Авторизация',['login'], ['class' => 'btn register', 'name' => 'register-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>


</div>