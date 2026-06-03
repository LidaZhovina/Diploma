<?php

use yii\bootstrap5\Html;
use yii\helpers\Url;

// Нормализуем переменные: поддерживаем оба способа передачи
if (!isset($route) && isset($model)) {
    $route = $model;
}
$isBooked  = $isBooked ?? false;
$residents = $residents ?? [];

$freeSlots = $route->number_participant - $route->getRouteResidents()->count();
$isFull    = $freeSlots <= 0;
?>

<div class="rc-card <?= $isBooked ? 'rc-card--booked' : '' ?>">

    <!-- Изображение -->
    <div class="rc-img">
        <?php if ($route->imageUrl): ?>
            <img src="<?= Html::encode($route->imageUrl) ?>"
                 alt="<?= Html::encode($route->name) ?>">
        <?php else: ?>
            <div class="rc-img-placeholder"><i class="ti ti-mountain" style="font-size:36px;"></i></div>
        <?php endif; ?>

        <!-- Бейдж уровня сложности -->
        <div class="rc-level-badge">
            <?= Html::encode($route->level->title ?? '') ?>
        </div>

        <!-- Бейдж "мест нет" -->
        <?php if ($isFull && !$isBooked): ?>
            <div class="rc-full-badge">Мест нет</div>
        <?php endif; ?>

        <!-- Бейдж "Записан" -->
        <?php if ($isBooked): ?>
            <div class="rc-booked-badge">✓ Записан</div>
        <?php endif; ?>
    </div>

    <!-- Тело карточки -->
    <div class="rc-body">
        <div class="rc-body-top">
            <div class="rc-name"><?= Html::encode($route->name) ?></div>

            <!-- Мета-информация -->
            <div class="rc-meta-row">
                <div class="rc-meta">
                    <span class="rc-meta-icon"><i class="ti ti-calendar-event"></i></span>
                    <div>
                        <div class="rc-meta-label">Дата</div>
                        <div class="rc-meta-val">
                            <?= Yii::$app->formatter->asDate($route->date_start, 'php:d.m.Y') ?>
                        </div>
                    </div>
                </div>
                <div class="rc-meta">
                    <span class="rc-meta-icon"><i class="ti ti-clock"></i></span>
                    <div>
                        <div class="rc-meta-label">Начало</div>
                        <div class="rc-meta-val">
                            <?= Yii::$app->formatter->asTime($route->time_start, 'php:H:i') ?>
                        </div>
                    </div>
                </div>
                <div class="rc-meta">
                    <span class="rc-meta-icon"><i class="ti ti-hourglass"></i></span>
                    <div>
                        <div class="rc-meta-label">Длительность</div>
                        <div class="rc-meta-val"><?= Html::encode($route->duration) ?></div>
                    </div>
                </div>
                <?php if (!$isBooked): ?>
                    <div class="rc-meta">
                        <span class="rc-meta-icon"><i class="ti ti-users"></i></span>
                        <div>
                            <div class="rc-meta-label">Свободно мест</div>
                            <div class="rc-meta-val <?= $isFull ? 'rc-meta-val--red' : '' ?>">
                                <?= $isFull ? 'Мест нет' : $freeSlots . ' из ' . $route->number_participant ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Список участников (для забронированных) -->
            <?php if ($isBooked && !empty($residents)): ?>
                <div class="rc-residents">
                    <div class="rc-residents-label">Участники</div>
                    <?php foreach ($residents as $resident): ?>
                        <div class="rc-resident-row">
                            <div class="rc-resident-av">
                                <?php
                                $parts = explode(' ', $resident['name']);
                                $initials = '';
                                foreach (array_slice($parts, 0, 2) as $p) {
                                    $initials .= mb_strtoupper(mb_substr($p, 0, 1));
                                }
                                echo Html::encode($initials);
                                ?>
                            </div>
                            <span class="rc-resident-name"><?= Html::encode($resident['name']) ?></span>
                            <?= Html::a(
                                '<i class="ti ti-x"></i>',
                                ['account/cancel-route',
                                    'route_id'    => $route->id,
                                    'resident_id' => $resident['id']],
                                [
                                    'class'        => 'rc-cancel-btn',
                                    'encode'       => false,
                                    'data-method'  => 'post',
                                    'data-confirm' => 'Отменить запись для этого гостя?',
                                    'title'        => 'Отменить запись',
                                ]
                            ) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Кнопки -->
        <div class="rc-actions">
            <?php if ($isBooked): ?>
                <?= Html::a('Подробнее', ['route/view', 'id' => $route->id],
                    ['class' => 'btn-rc-detail']) ?>
            <?php else: ?>
                <?php if (!$isFull): ?>
                    <?= Html::a('Записаться', ['account/book-route', 'id' => $route->id],
                        ['class' => 'btn-rc-book']) ?>
                <?php endif; ?>
                <?= Html::a('Подробнее', ['route/view', 'id' => $route->id],
                    ['class' => 'btn-rc-detail']) ?>
            <?php endif; ?>
        </div>
    </div>

</div>