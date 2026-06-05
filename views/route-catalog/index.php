<?php
use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = 'Маршруты';
$this->registerCssFile('@web/css/catalog.css');
$this->registerCssFile('@web/css/route-card.css');
?>

<div class="wellness-program-index container">
    <h1><?= Html::encode($this->title) ?></h1>
    <p class="wellness-page-sub">Исследуйте природу Байкала вместе с нами</p>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['tag' => 'div', 'class' => 'wellness-list'],
        'itemOptions' => ['tag' => 'div'],
        'itemView' => '_item',
        'layout' => '{items}',
    ]) ?>
</div>