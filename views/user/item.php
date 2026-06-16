<?php
use yii\bootstrap5\Html;

// Инициалы из фамилии и имени
$initials = mb_substr($model->surname, 0, 1) . mb_substr($model->name, 0, 1);

// CSS-класс аватара и бейджа по роли
$roleAlias = $model->role->alias ?? '';
$avatarClass = match($roleAlias) {
    'Admin'      => 'av-admin',
    'Reception'  => 'av-reception',
    default      => 'av-client',
};
$badgeClass = match($roleAlias) {
    'Admin'      => 'badge-admin',
    'Reception'  => 'badge-reception',
    default      => 'badge-client',
};
?>

<div class="user-card">
    <div class="user-card-top">
        <div class="avatar <?= $avatarClass ?>">
            <?= Html::encode($initials) ?>
        </div>
        <div>
            <div class="user-name">
                <?= Html::encode($model->surname . ' ' . $model->name . ' ' . $model->patronymic) ?>
            </div>
            <div class="user-email">
                <?= Html::encode($model->email) ?>
            </div>
            <span class="role-badge <?= $badgeClass ?>">
                <?= Html::encode($roleAlias) ?>
            </span>
        </div>
    </div>
    <hr class="user-card-divider">
    <div class="user-card-actions">
        <?= Html::a('<i class="ti ti-eye" aria-hidden="true"></i> Подробнее',
            ['view', 'id' => $model->id],
            ['class' => 'btn-action', 'encode' => false]
        ) ?>
        <?= Html::a('<i class="ti ti-trash" aria-hidden="true"></i> Удалить',
            ['delete', 'id' => $model->id],
            [
                'class' => 'btn-action btn-del',
                'encode' => false,
                'data' => [
                    'confirm' => 'Удалить пользователя? Это действие необратимо.',
                    'method'  => 'post',
                ],
            ]
        ) ?>
    </div>
</div>