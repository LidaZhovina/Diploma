<?php

use app\models\WellnessProgram;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Оздоровительные программы';
?>
<div class="wellness-program-index">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">

            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

            <p class="d-flex justify-content-between">
                <?= Html::a('Создать программу', ['create'], ['class' => 'btn register']) ?>
                <?= Html::a('Панель Администратора', ['admin/index'], ['class' => 'btn register']) ?>
            </p>


            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'item'],
                'itemView' => 'item2',
            ]) ?>


        </div>
    </div>
</div>