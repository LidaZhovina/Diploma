<?php

use yii\bootstrap5\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use app\models\Role;

$this->title = 'Пользователи';
$this->registerCssFile('@web/css/admin.css');
?>

<div class="user-index">

    <div class="user-page-header">
        <div>
            <div class="page-title">Пользователи</div>
            <div class="page-sub">Управление учётными записями</div>
        </div>
        <?= Html::a(
            '<i class="ti ti-user-plus" aria-hidden="true"></i> Добавить ресепшн',
            ['/site/register-reception'],
            ['class' => 'btn-create', 'encode' => false]
        ) ?>
    </div>

    <?php Pjax::begin(['id' => 'users-pjax']) ?>

    <?= Html::beginForm(['index'], 'get', [
        'data-pjax' => true,
        'class'     => 'user-search-form',
    ]) ?>
    <div class="search-bar">
        <i class="ti ti-search" aria-hidden="true"></i>
        <?= Html::input(
            'text',
            'UserSearch[fio]',
            Yii::$app->request->get('UserSearch')['fio'] ?? '',
            [
                'placeholder' => 'Поиск по фамилии, имени или email...',
                'class'       => 'search-input',
            ]
        ) ?>
        <?= Html::submitButton(
            'Найти',
            ['class' => 'btn-search', 'encode' => false]
        ) ?>
    </div>
    <?= Html::endForm() ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView'     => 'item',
        'layout'       => '{items}{pager}',
        'options'      => ['class' => 'users-grid'],
        'itemOptions'  => ['tag' => false],
        'emptyText'    => '<p class="empty-text">Пользователи не найдены</p>',
    ]) ?>

    <?php Pjax::end() ?>

</div>