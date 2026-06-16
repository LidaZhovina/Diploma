<?php
use yii\bootstrap5\Html;

$this->title = 'Пользователь';
$this->registerCssFile('@web/css/admin.css');

$roleAlias = $model->role->alias ?? '—';
$initials = mb_substr($model->surname, 0, 1) . mb_substr($model->name, 0, 1);
?>

<div class="ap-main">
    <div class="user-view">

        <div class="page-hdr">
            <div>
                <div class="page-title">Пользователь</div>
                <div class="page-sub">Просмотр учётной записи</div>
            </div>
        </div>

        <div class="uv-card">
            <div class="uv-top">
                <div class="uv-ava"><?= Html::encode($initials) ?></div>
                <div>
                    <div class="uv-name">
                        <?= Html::encode($model->surname . ' ' . $model->name . ' ' . $model->patronymic) ?>
                    </div>
                    <div class="uv-email"><?= Html::encode($model->email) ?></div>
                    <span class="uv-role"><?= Html::encode($roleAlias) ?></span>
                </div>
            </div>

            <div class="uv-rows">
                <div class="uv-row">
                    <span class="uv-rlbl">Фамилия</span>
                    <span class="uv-rval"><?= Html::encode($model->surname) ?></span>
                </div>
                <div class="uv-row">
                    <span class="uv-rlbl">Имя</span>
                    <span class="uv-rval"><?= Html::encode($model->name) ?></span>
                </div>
                <div class="uv-row">
                    <span class="uv-rlbl">Отчество</span>
                    <span class="uv-rval"><?= Html::encode($model->patronymic) ?></span>
                </div>
                <div class="uv-row">
                    <span class="uv-rlbl">Роль</span>
                    <span class="uv-rval"><?= Html::encode($roleAlias) ?></span>
                </div>
                <div class="uv-row">
                    <span class="uv-rlbl">Email</span>
                    <span class="uv-rval uv-rval-email"><?= Html::encode($model->email) ?></span>
                </div>
            </div>

            <div class="uv-actions">
                <?= Html::a(
                    '<i class="ti ti-arrow-left" aria-hidden="true"></i> Назад',
                    ['index'],
                    ['class' => 'uv-btn', 'encode' => false]
                ) ?>
                <?= Html::a(
                    '<i class="ti ti-trash" aria-hidden="true"></i> Удалить',
                    ['delete', 'id' => $model->id],
                    [
                        'class'  => 'uv-btn uv-btn-del',
                        'encode' => false,
                        'data'   => [
                            'confirm' => 'Удалить пользователя? Это действие необратимо.',
                            'method'  => 'post',
                        ],
                    ]
                ) ?>
            </div>
        </div>

    </div>
</div>