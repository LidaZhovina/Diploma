<?php

use app\models\PayType;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
?>
<h2 class="text-center">Данные гостей</h2>
<?php $form = ActiveForm::begin(); ?>

<?php for ($i = 0; $i < $step1['guests_count']; $i++): ?>
    <div class="guest-block mb-3 p-3 border rounded">
        <h4>Гость <?= $i + 1 ?></h4>
        <?= Html::activeTextInput($model, "guests[$i][surname]", ['class' => 'form-control mb-2', 'placeholder' => 'Фамилия']) ?>
        <?= Html::activeTextInput($model, "guests[$i][name]", ['class' => 'form-control mb-2', 'placeholder' => 'Имя']) ?>
        <?= Html::activeTextInput($model, "guests[$i][patronymic]", ['class' => 'form-control mb-2', 'placeholder' => 'Отчество']) ?>
        <!-- <?= Html::activeTextInput($model, "guests[$i][birth_date]", ['class' => 'form-control', 'type' => 'date']) ?> -->
         <?= $form->field($model, "guests[$i][birth_date]")->textInput(['type' => 'date']) ?>
    </div>
<?php endfor; ?>
<?= $form->field($model, 'pay_type')->dropDownList(PayType::getItems(), ['prompt' => 'Выберите способ оплаты']) ?>

<p>Стоимость проживания: <?= (new \DateTime($step1['arrival_date']))->diff(new \DateTime($step1['departure_date']))->days * $room->price_per_day ?> ₽</p>

<?= Html::submitButton('Оплатить', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end();
?>