<?php

use app\models\WellnessProgram;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Оздоровительные программы';
$this->registerCssFile('@web/css/catalog.css');
?>
<div class="wellness-program-index">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">

            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

            <p class="d-flex justify-content-between">
                <?= Html::a('Создать программу', ['create'], ['class' => 'btn-wellness']) ?>
                <?= Html::a('Панель Администратора', ['admin/index'], ['class' => 'btn-wellness']) ?>
            </p>


            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'item'],
                'itemView' => 'item',
            ]) ?>


        </div>
    </div>
</div>