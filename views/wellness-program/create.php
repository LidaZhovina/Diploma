<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\WellnessProgram $model */

$this->title = 'Создание программы';

?>
<div class="wellness-program-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
