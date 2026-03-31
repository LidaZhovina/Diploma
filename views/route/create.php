<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Route $model */

$this->title = 'Создание маршрута';

?>
<div class="route-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'levels' => $levels,
    ]) ?>

</div>
