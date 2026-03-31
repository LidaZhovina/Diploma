<?php

use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\CatalogSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Номера';
?>
<div class="room-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

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

    <?php Pjax::end(); ?>

</div>
