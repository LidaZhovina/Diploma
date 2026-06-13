<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\ReceptionSearch $searchModel */
/** @var string $activeTab */
/** @var int[] $tabCounts  ['new'=>3, 'pending'=>1, 'active'=>4, 'past'=>10, 'cancelled'=>2] */
/** @var app\models\Booking[] $todayCheckouts */
/** @var app\models\Resident[] $activeGuests */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

$this->title = 'Кабинет менеджера';
$this->registerCssFile('@web/css/reception.css');

/** @var app\models\User $me */
$me = Yii::$app->user->identity;
$initials = mb_strtoupper(
    mb_substr($me->surname, 0, 1) . mb_substr($me->name, 0, 1)
);

$tabs = [
    'new'       => ['label' => 'Предстоящие',  'icon' => 'ti-calendar-event'],
    'active'    => ['label' => 'Активные',      'icon' => 'ti-home-check'],
    'past'      => ['label' => 'Прошедшие',     'icon' => 'ti-history'],
    'cancelled' => ['label' => 'Отменённые',    'icon' => 'ti-ban'],
];
?>

<div class="reception-page">
    <div class="reception-container">

        <!-- ── Заголовок ─────────────────────────────────────── -->
        <div class="reception-header">
            <div class="reception-title">
                <div class="reception-title-icon">
                    <i class="ti ti-id-badge"></i>
                </div>
                Кабинет менеджера
            </div>
            <div class="reception-user-info">
                <div class="reception-avatar"><?= Html::encode($initials) ?></div>
                <div>
                    <div class="reception-user-name">
                        <?= Html::encode($me->surname . ' ' . $me->name) ?>
                    </div>
                    <div class="reception-user-role">Ресепшн</div>
                </div>
            </div>
        </div>

        <!-- ── Статистика ─────────────────────────────────────── -->
        <div class="reception-stats">
            <div class="rstat-card">
                <div class="rstat-icon rstat-icon--blue">
                    <i class="ti ti-calendar-event"></i>
                </div>
                <div>
                    <div class="rstat-label">Заедут сегодня</div>
                    <div class="rstat-value"><?= $tabCounts['new'] ?? 0 ?></div>
                    <div class="rstat-sub">ожидают заселения</div>
                </div>
            </div>
            <div class="rstat-card">
                <div class="rstat-icon rstat-icon--green">
                    <i class="ti ti-users"></i>
                </div>
                <div>
                    <div class="rstat-label">Живут сейчас</div>
                    <div class="rstat-value"><?= $tabCounts['active'] ?? 0 ?></div>
                    <div class="rstat-sub">активных гостей</div>
                </div>
            </div>
            <div class="rstat-card">
                <div class="rstat-icon rstat-icon--yellow">
                    <i class="ti ti-home-move"></i>
                </div>
                <div>
                    <div class="rstat-label">Выедут сегодня</div>
                    <div class="rstat-value"><?= count($todayCheckouts) ?></div>
                    <div class="rstat-sub">нужно выселить</div>
                </div>
            </div>
        </div>

        <!-- ── Вкладки ────────────────────────────────────────── -->
        <div class="reception-tabs">
            <?php foreach ($tabs as $alias => $tab): ?>
                <?= Html::a(
                    '<i class="ti ' . $tab['icon'] . '"></i> '
                        . Html::encode($tab['label'])
                        . ' <span class="rtab-count">' . ($tabCounts[$alias] ?? 0) . '</span>',
                    ['reseption/index', 'tab' => $alias],
                    ['class' => 'rtab' . ($activeTab === $alias ? ' active' : '')]
                ) ?>
            <?php endforeach; ?>
        </div>

        <!-- ── Основная раскладка ─────────────────────────────── -->
        <div class="reception-layout">

            <!-- Левая колонка: поиск + список -->
            <div>
                <!-- Поиск -->
                <?php $form = \yii\widgets\ActiveForm::begin([
                    'method' => 'get',
                    'action' => ['reseption/index', 'tab' => $activeTab],
                    'options' => ['class' => 'reception-search'],
                ]); ?>
                <?= Html::input(
                    'text',
                    'ReseptionSearch[query]',
                    $searchModel->query ?? '',
                    [
                        'class'       => 'reception-search-input',
                        'placeholder' => 'Поиск по фамилии гостя или номеру бронирования...',
                    ]
                ) ?>
                <?= Html::submitButton(
                    '<i class="ti ti-search"></i> Найти',
                    ['class' => 'btn-reception-search']
                ) ?>
                <?php \yii\widgets\ActiveForm::end(); ?>

                <!-- Список карточек -->
                <div class="bk-list">
                    <?= ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemView'     => 'item',
                        'layout'       => '{items}{pager}',
                        'emptyText'    => '<div class="bk-empty">Бронирований не найдено</div>',
                        'options'      => ['tag' => false],
                    ]) ?>
                </div>
            </div>

            <!-- Правая колонка: сайдбар -->
            <div class="reception-sidebar">

                <!-- Выезды сегодня -->
                <div class="rside-card">
                    <div class="rside-title">
                        <i class="ti ti-home-move"></i> Выезды сегодня
                    </div>
                    <?php if (empty($todayCheckouts)): ?>
                        <div style="font-size:12px; color:#aaa;">Выездов сегодня нет</div>
                    <?php else: ?>
                        <?php foreach ($todayCheckouts as $booking): ?>
                            <div class="checkout-item">
                                <div class="checkout-item-name">
                                    <?= Html::encode(
                                        $booking->mainResident->surname . ' '
                                            . mb_substr($booking->mainResident->name, 0, 1) . '.'
                                            . mb_substr($booking->mainResident->patronymic, 0, 1) . '.'
                                    ) ?>
                                </div>
                                <div class="checkout-item-meta">
                                    <i class="ti ti-clock"></i>
                                    До 12:00 · ком. <?= Html::encode($booking->room->number) ?>
                                </div>
                                <?= Html::a(
                                    '<i class="ti ti-home-move"></i> Выселить',
                                    ['reseption/checkout', 'id' => $booking->id],
                                    ['class' => 'btn-bk btn-bk--checkout']
                                ) ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Активные гости -->
                <div class="rside-card">
                    <div class="rside-title">
                        <i class="ti ti-users"></i> Активные гости
                    </div>
                    <?php if (empty($activeBookings)): ?>
                        <div style="font-size:12px; color:#aaa;">Нет активных гостей</div>
                    <?php else: ?>
                        <?php foreach ($activeBookings as $booking): ?>
                            <?php
                            $resident = $booking->mainResident;
                            if (!$resident) continue;
                            $av = mb_strtoupper(
                                mb_substr($resident->surname, 0, 1)
                                    . mb_substr($resident->name, 0, 1)
                            );
                            ?>
                            <div class="guest-item">
                                <div class="guest-av"><?= Html::encode($av) ?></div>
                                <div>
                                    <div class="guest-name">
                                        <?= Html::encode(
                                            $resident->surname . ' '
                                                . mb_substr($resident->name, 0, 1) . '.'
                                        ) ?>
                                    </div>
                                    <div class="guest-room">
                                        Ком. <?= Html::encode($booking->room->number) ?>
                                        · до <?= Yii::$app->formatter->asDate($booking->departure_date, 'dd.MM') ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </div>
        </div>

    </div>
</div>