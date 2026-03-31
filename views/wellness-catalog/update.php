<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\WellnessProgram $model */

$this->title = 'Update Wellness Program: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Wellness Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wellness-program-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
