<?php
use app\models\Booking;
use yii\bootstrap5\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use app\models\User;

/** @var yii\web\View $this */
/** @var app\models\AdminSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $stats */
/** @var float $monthRevenue */
/** @var array $roomOccupation */
/** @var User[] $recentUsers */

$this->title = 'Панель администратора';
$this->registerCssFile('@web/css/admin.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);
?>

<div class="ap">
    <div class="ap-main">
        <div class="page-hdr">
            <div class="page-title">Панель администратора</div>
            <div class="hdr-btns">
                <?= Html::a('Номера', ['/room/index'], ['class' => 'hdr-btn']) ?>
                <?= Html::a('Программы', ['/wellness-program/index'], ['class' => 'hdr-btn']) ?>
                <?= Html::a('Маршруты', ['/route/index'], ['class' => 'hdr-btn']) ?>
                <?= Html::a('Пользователи', ['/user/index'], ['class' => 'hdr-btn primary']) ?>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background:#EEF2FF;"><i class="ti ti-clock" style="font-size:18px;color:#3B4593;"></i></div>
                <div class="stat-lbl">В обработке</div>
                <div class="stat-val" style="color:#3B4593;"><?= $stats['pending'] ?? 0 ?></div>
                <div class="stat-sub">требуют действий</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#E0F0FF;"><i class="ti ti-calendar-event" style="font-size:18px;color:#1565a0;"></i></div>
                <div class="stat-lbl">Предстоящие</div>
                <div class="stat-val" style="color:#669CFF;"><?= $stats['new'] ?? 0 ?></div>
                <div class="stat-sub">подтверждены</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#E3F5EA;"><i class="ti ti-home-check" style="font-size:18px;color:#1b6834;"></i></div>
                <div class="stat-lbl">Активные</div>
                <div class="stat-val" style="color:#1b6834;"><?= $stats['active'] ?? 0 ?></div>
                <div class="stat-sub">гостей сейчас</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#D8E4FF;"><i class="ti ti-coin" style="font-size:18px;color:#3B4593;"></i></div>
                <div class="stat-lbl">Выручка за месяц</div>
                <div class="stat-val" style="color:#3B4593;"><?= number_format($monthRevenue, 0, '', ' ') ?> ₽</div>
                <div class="stat-sub">рублей</div>
            </div>
        </div>

        <!-- Вкладки статусов -->
        <div class="status-tabs" id="statusTabs">
            <?php $currentStatus = $searchModel->status_alias; ?>
            <?= Html::a('В обработке <span class="stab-cnt">' . ($stats['pending'] ?? 0) . '</span>', ['index', 'status' => 'pending'], [
                'class' => 'stab ' . ($currentStatus == 'pending' ? 'active' : ''),
                'data-pjax' => '#bookings-pjax',
            ]) ?>
            <?= Html::a('Предстоящие <span class="stab-cnt">' . ($stats['new'] ?? 0) . '</span>', ['index', 'status' => 'new'], [
                'class' => 'stab ' . ($currentStatus == 'new' ? 'active' : ''),
                'data-pjax' => '#bookings-pjax',
            ]) ?>
            <?= Html::a('Активные <span class="stab-cnt">' . ($stats['active'] ?? 0) . '</span>', ['index', 'status' => 'active'], [
                'class' => 'stab ' . ($currentStatus == 'active' ? 'active' : ''),
                'data-pjax' => '#bookings-pjax',
            ]) ?>
            <?= Html::a('Прошедшие <span class="stab-cnt">' . ($stats['past'] ?? 0) . '</span>', ['index', 'status' => 'past'], [
                'class' => 'stab ' . ($currentStatus == 'past' ? 'active' : ''),
                'data-pjax' => '#bookings-pjax',
            ]) ?>
            <?= Html::a('Отменённые <span class="stab-cnt">' . ($stats['cancelled'] ?? 0) . '</span>', ['index', 'status' => 'cancelled'], [
                'class' => 'stab ' . ($currentStatus == 'cancelled' ? 'active' : ''),
                'data-pjax' => '#bookings-pjax',
            ]) ?>
        </div>

        <div class="two-col">
            <!-- Левая колонка – список бронирований -->
            <div>
                <?php Pjax::begin(['id' => 'bookings-pjax', 'enablePushState' => true]); ?>
                <?= ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '_booking_card',
                    'itemOptions' => ['class' => 'item'],
                    'options' => ['class' => 'list-view'],
                    'summary' => false,
                    'pager' => ['class' => \yii\bootstrap5\LinkPager::class],
                ]) ?>
                <?php Pjax::end(); ?>
            </div>

            <!-- Правая колонка – сайдбар -->
            <div class="side-panel">
                <!-- Загрузка номеров -->
                <div class="scard">
                    <div class="sec-lbl">Загрузка номеров</div>
                    <?php foreach ($roomOccupation as $roomName => $data): ?>
                        <div class="prog-item">
                            <div class="prog-row">
                                <span class="prog-name"><?= Html::encode($roomName) ?></span>
                                <span class="prog-cnt"><?= $data['occupied'] ?> / <?= $data['total'] ?></span>
                            </div>
                            <div class="prog-bar">
                                <div class="prog-fill" style="width: <?= round($data['occupied'] / $data['total'] * 100) ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Последние пользователи -->
                <div class="scard">
                    <div class="sec-lbl">Последние пользователи</div>
                    <?php foreach ($recentUsers as $user): ?>
                        <div class="ur">
                            <div class="ur-l">
                                <div class="ur-ava"><?= mb_strtoupper(mb_substr($user->surname ?? '', 0, 1) . mb_substr($user->name ?? '', 0, 1)) ?></div>
                                <div>
                                    <div class="ur-name"><?= Html::encode($user->surname . ' ' . $user->name) ?></div>
                                    <div class="ur-mail"><?= Html::encode($user->email) ?></div>
                                </div>
                            </div>
                            <span class="ur-role <?= $user->role_id == 2 ? 'role-c' : ($user->role_id == 3 ? 'role-m' : '') ?>">
                                <?= $user->role_id == 2 ? 'клиент' : ($user->role_id == 3 ? 'менеджер' : 'админ') ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                    <?= Html::a('Все пользователи', ['/user/index'], ['class' => 'hdr-btn primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>