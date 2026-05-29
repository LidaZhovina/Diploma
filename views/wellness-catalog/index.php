<?php

use app\models\WellnessProgram;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Оздоровительные программы';
$this->registerCssFile('@web/css/catalog.css');
?>
<div class="wellness-program-index container">


    <h1><?= Html::encode($this->title) ?></h1>
    <p class="wellness-page-sub">Восстановите здоровье и силы на берегу Байкала</p>

    <?php Pjax::begin(); ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'options'      => ['tag' => 'div', 'class' => 'wellness-list'],
        'itemOptions'  => ['tag' => 'div'],
        'itemView' => 'item',
        'layout'       => "{items}\n{pager}",
    ]) ?>

    <?php Pjax::end(); ?>


</div>