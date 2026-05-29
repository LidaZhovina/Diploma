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
$this->registerCssFile('@web/css/catalog.css');
?>
<div class="room-index container">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="d-flex justify-content-between">
        <?= Html::a('Создать номер', ['create'], ['class' => 'btn-wellness']) ?>
        <?= Html::a('Панель Администратора', ['admin/index'], ['class' => 'btn-wellness']) ?>
    </p>


    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'options'      => ['tag' => 'div', 'class' => 'row row-cols-1 row-cols-md-3 g-4'],
        'itemOptions'  => ['tag' => 'div', 'class' => 'col d-flex'],
        'pager' => [
            'class' => LinkPager::class
        ],
        'itemView' => 'item',
        'layout' => "{items}\n{pager}",
    ]) ?>


</div>