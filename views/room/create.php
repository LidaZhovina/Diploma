<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Room $model */

$this->title = 'Создание номера';
?>
<div class="room-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'types' => $types,
    ]) ?>

</div>
