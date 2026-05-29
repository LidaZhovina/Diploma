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

?>
<div class="wellness-program-index">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">

            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

            <?php Pjax::begin(); ?>

            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'item'],
                'itemView' => 'item',
            ]) ?>

            <?php Pjax::end(); ?>

        </div>
    </div>
</div>