<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\RegisterForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\web\JqueryAsset;

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

            <?= $form->field($model, 'rules')->checkbox([
                'class' => 'form-check-input',
                'template' => "<div class=\"form-check\">{input} {label}\n{error}</div>",
            ]) ?>


            <div class="form-group d-flex justify-content-between mt-3">
                <?= Html::submitButton('Зарегестрироваться', ['class' => 'btn register l', 'name' => 'register-button']) ?>
                <?= Html::a('Авторизация', ['login'], ['class' => 'btn register', 'name' => 'register-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>


</div>

<?php
$this->registerJsFile("web/js/register.js", ['depends' => JqueryAsset::class]);