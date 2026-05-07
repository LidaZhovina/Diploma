<?php

use app\models\Booking;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var app\models\AccountSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Личный кабинет';
?>
<div class="booking-index">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">

            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

            <!-- <p>
                <?= Html::a('Create Booking', ['create'], ['class' => 'btn btn-success']) ?>
            </p> -->

            <?php echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'pager' => [
                    'class' => LinkPager::class
                ],
                'itemOptions' => ['class' => 'item'],
                'itemView' => 'item',
            ]) ?>


        </div>
    </div>
</div>