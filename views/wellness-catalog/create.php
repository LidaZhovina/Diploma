<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\WellnessProgram $model */

$this->title = 'Create Wellness Program';
$this->params['breadcrumbs'][] = ['label' => 'Wellness Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wellness-program-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
