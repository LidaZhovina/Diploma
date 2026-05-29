<?php

use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/** @var app\models\CatalogSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */


$this->title = 'Номера';
$this->registerCssFile('@web/css/catalog.css');
?>

<div class="room-index container">

    <h1><?= Html::encode($this->title) ?></h1>
    <p class="catalog-subtitle">Выберите подходящий номер для вашего отдыха</p>

    <?php Pjax::begin(); ?>
    <?= $this->render('_search', ['model' => $searchModel]) ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'options'      => ['tag' => 'div', 'class' => 'row row-cols-1 row-cols-md-3 g-4'],
        'itemOptions'  => ['tag' => 'div', 'class' => 'col d-flex'],
        'pager'        => ['class' => LinkPager::class],
        'itemView'     => 'item2',
        'layout'       => "{items}\n{pager}",
    ]) ?>

    <?php Pjax::end(); ?>

</div>