<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Авторизация';
$this->registerCssFile('@web/css/auth.css')
?>
<div class="auth-page">
    <div class="auth-wrap">

        <div class="auth-left">
            <div class="auth-left-title">Санаторий «Танхой»</div>
            <div class="auth-left-sub">Мы рады видеть вас снова!</div>
            <div class="auth-badge">Лечебные программы</div>
            <div class="auth-badge">Чистый воздух Байкала</div>
            <div class="auth-badge">Маршруты и прогулки</div>
        </div>

        <div class="auth-right">
            <div class="auth-title">Войти</div>
            <div class="auth-subtitle">Введите данные вашего аккаунта</div>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => 'example@mail.ru']) ?>
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => '••••••••']) ?>
            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"form-check\">{input} {label}</div>\n{error}",
            ]) ?>

            <?= Html::submitButton('Войти →', ['class' => 'btn btn-auth']) ?>

            <?php ActiveForm::end(); ?>

            <div class="auth-switch">
                Нет аккаунта? <?= Html::a('Регистрация', ['register']) ?>
            </div>
        </div>

    </div>
</div>