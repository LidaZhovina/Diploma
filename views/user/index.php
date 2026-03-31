<?php

use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Список пользователей';
?>
<div class="user-index">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">

            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

            <p class="d-flex justify-content-between">
                <?= Html::a('Зарегестрировать ресепшн', ['/site/register-reception'], ['class' => 'btn register']) ?>
                <?= Html::a('Панель Администратора', ['admin/index'], ['class' => 'btn register']) ?>
            </p>


            <?php echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'item'],
                'itemView' => 'item',
            ]) ?>



        </div>
    </div>
</div>