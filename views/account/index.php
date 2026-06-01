<?php

use app\models\Booking;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\AccountSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $bookedRoutesGrouped */
/** @var array $availableRoutes */

$this->title = 'Личный кабинет';
// Получаем данные текущего пользователя для шапки
$user = Yii::$app->user->identity;
$initials = mb_strtoupper(mb_substr($user->surname ?? '', 0, 1) . mb_substr($user->name ?? '', 0, 1));
$email = $user->email ?? '';

// Регистрируем CSS-файл
$this->registerCssFile('@web/css/account.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);
?>
<div class="booking-index">
    <!-- ШАПКА ЛИЧНОГО КАБИНЕТА -->
    <div class="acc-hero">
        <div class="acc-avatar"><?= Html::encode($initials) ?></div>
        <div class="acc-hero-title">Личный кабинет</div>
        <div class="acc-hero-sub"><?= Html::encode($email) ?></div>
    </div>

    <!-- ВКЛАДКИ -->
    <div class="acc-tabs-wrap">
        <ul class="nav nav-tabs" id="accountTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="bookings-tab" data-bs-toggle="tab" data-bs-target="#bookings" type="button" role="tab">Мои бронирования</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="routes-tab" data-bs-toggle="tab" data-bs-target="#routes" type="button" role="tab">Маршруты</button>
            </li>
        </ul>
    </div>

    <!-- КОНТЕНТ -->
    <div class="acc-content">
        <div class="tab-content">
            <!-- Вкладка Бронирований -->
            <div class="tab-pane fade show active" id="bookings" role="tabpanel">
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                <?php Pjax::begin(); ?>
                <?= ListView::widget([
                    'dataProvider' => $dataProvider,
                    'pager' => ['class' => LinkPager::class],
                    'itemView' => 'item',
                    'itemOptions' => ['class' => 'item'],
                    'options' => ['class' => 'list-view'],
                    'summary' => false,
                ]); ?>
                <?php Pjax::end(); ?>
            </div>

            <!-- Вкладка Маршрутов -->
            <div class="tab-pane fade" id="routes" role="tabpanel">
                <h4>Забронированные маршруты</h4>
                <?php if (empty($bookedRoutesGrouped)): ?>
                    <p style="color:#999; font-size:14px;">Вы ещё не записаны на маршруты.</p>
                <?php else: ?>
                    <?php foreach ($bookedRoutesGrouped as $item): ?>
                        <?= $this->render('_route_item2', [
                            'route' => $item['route'],
                            'isBooked' => true,
                            'residents' => $item['residents']
                        ]) ?>
                    <?php endforeach; ?>
                <?php endif; ?>

                <hr>

                <h4>Доступные маршруты</h4>
                <?php if (empty($availableRoutes)): ?>
                    <p style="color:#999; font-size:14px;">Нет доступных маршрутов.</p>
                <?php else: ?>
                    <?php foreach ($availableRoutes as $route): ?>
                        <?= $this->render('_route_item', ['model' => $route, 'isBooked' => false]) ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<'JS'
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.acc-tab');
    const panes = {
        'bookings': document.getElementById('bookings'),
        'routes': document.getElementById('routes')
    };

    function activateTab(activeId) {
        // Убираем активный класс у всех кнопок
        tabs.forEach(tab => {
            tab.classList.remove('active');
        });
        // Скрываем все панели
        for (let id in panes) {
            if (panes[id]) {
                panes[id].classList.remove('show', 'active');
            }
        }
        // Активируем нужную кнопку и панель
        const activeTab = document.querySelector(`.acc-tab[data-bs-target="#${activeId}"]`);
        if (activeTab) activeTab.classList.add('active');
        const activePane = document.getElementById(activeId);
        if (activePane) activePane.classList.add('show', 'active');
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            const target = this.getAttribute('data-bs-target');
            if (target) {
                const targetId = target.substring(1); // убираем #
                activateTab(targetId);
            }
        });
    });

    // По умолчанию активируем вкладку "Мои бронирования"
    activateTab('bookings');
});
JS);
?>