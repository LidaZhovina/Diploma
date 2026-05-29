<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\RegisterForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\web\JqueryAsset;

$this->title = 'Регистрация';
$this->registerCssFile('@web/css/auth.css')
?>
<div class="auth-page">
    <div class="auth-wrap">

        <div class="auth-left">
            <div class="auth-left-title">Добро пожаловать!</div>
            <div class="auth-left-sub">Создайте аккаунт и получите доступ к бронированию номеров и оздоровительных программ.</div>
            <div class="auth-badge">Берег Байкала</div>
            <div class="auth-badge">Маршруты и прогулки</div>
            <div class="auth-badge">Оздоровление</div>
        </div>

        <div class="auth-right">
            <h1 class="auth-title"><?= Html::encode($this->title)?></h1>
            <div class="auth-subtitle">Заполните данные для создания аккаунта</div>

            <?php $form = ActiveForm::begin(['id' => 'register-form']); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => 'example@mail.ru']) ?>
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => '••••••••']) ?>
            <?= $form->field($model, 'surname')->textInput(['placeholder' => 'Иванов']) ?>
            <?= $form->field($model, 'name')->textInput(['placeholder' => 'Иван']) ?>
            <?= $form->field($model, 'patronymic')->textInput(['placeholder' => 'Иванович']) ?>
            <?= $form->field($model, 'rules')->checkbox([
                'class'    => 'form-check-input',
                'template' => "<div class=\"form-check\">{input} {label}\n{error}</div>",
            ]) ?>

            <?= Html::submitButton('Зарегистрироваться →', ['class' => 'btn btn-auth']) ?>

            <?php ActiveForm::end(); ?>

            <div class="auth-switch">
                Уже есть аккаунт? <?= Html::a('Войти', ['login']) ?>
            </div>
        </div>

    </div>
</div>

<?php
$this->registerJsFile("web/js/register.js", ['depends' => JqueryAsset::class]);
