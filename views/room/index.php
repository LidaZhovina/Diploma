<?php

use app\models\Room;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Номера';
?>
<div class="room-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="d-flex justify-content-between">
        <?= Html::a('Создать номер', ['create'], ['class' => 'btn register']) ?>
        <?= Html::a('Панель Администратора', ['admin/index'], ['class' => 'btn register']) ?>
    </p>


    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'tag' => 'div',
            'class' => 'row',
        ],
        'itemOptions' => [
            'tag' => 'div',
            'class' => 'col-md-4 mb-4 d-flex',
        ],
        'pager' => [
            'class' => LinkPager::class
        ],
        'itemView' => 'item2',
        'layout' => "{items}\n{pager}",
    ]) ?>


</div>