<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'Санаторий «Танхой»';
$this->registerCssFile('@web/css/main.css');
?>

<div class="site-index">

    <!-- ========== HERO ========== -->
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <span class="hero-label">Санаторий на берегу Байкала</span>
            <h1 class="hero-title">
                Здоровье<br>и <em>природа</em><br>Байкала
            </h1>
            <?= Html::a('Забронировать поездку', ['/catalog'], ['class' => 'btn btn-hero']) ?>
        </div>
    </section>

    <!-- ========== О НАС ========== -->
    <section class="about-section">
        <div>
            <h2 class="about-title">Санаторий<br>«<em>Танхой</em>»</h2>
            <p class="about-text">
                Расположен на берегу Байкала в посёлке Танхой.
                Уникальный микроклимат заповедника, лечебные программы
                и чистый воздух создают идеальные условия для восстановления здоровья.
            </p>
            <div class="about-stats">
                <div class="stat-circle">
                    <div class="stat-num">+5K</div>
                    <div class="stat-lbl">гостей<br>в год</div>
                </div>
                <div class="stat-circle">
                    <div class="stat-num">30+</div>
                    <div class="stat-lbl">лет<br>работы</div>
                </div>
                <div class="stat-circle">
                    <div class="stat-num">40+</div>
                    <div class="stat-lbl">видов<br>процедур</div>
                </div>
            </div>
        </div>

        <div class="about-img-wrap">
            <img src="/img/territory/2.jpg" alt="Санаторий Танхой">
        </div>
    </section>

    <!-- ========== ПОПУЛЯРНЫЕ НОМЕРА ========== -->
    <section class="rooms-section">
        <div class="section-title">Популярные <em>номера</em></div>

        <div class="rooms-layout">
            <!-- Большая карточка слева -->
            <?php if (!empty($rooms) && isset($rooms[0])): ?>
                <?php $room = $rooms[0]; ?>
                <?php
                $firstImage = $room->roomImages[0] ?? null;
                $imageUrl = $firstImage ? Url::to(['room/display-image', 'id' => $firstImage->id]) : '/web/img/no-image.jpg';
                ?>
                <div class="room-card-big">
                    <img src="<?= $imageUrl ?>" alt="<?= Html::encode($room->roomType->name) ?>">
                    <div class="room-big-overlay"></div>
                    <div class="room-big-body">
                        <span class="room-promo-badge">Специальное предложение</span>
                        <div class="room-big-name"><?= Html::encode($room->roomType->name) ?> <?= $room->number_guests ?>-местный</div>
                        <div class="room-big-price">
                            <?= number_format($room->price_per_day, 0, '', ' ') ?> ₽ <span>/ ночь</span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div>
                <!-- Малые карточки -->
                <div class="rooms-small-grid">
                    <?php for ($i = 1; $i <= 3 && $i < count($rooms); $i++): ?>
                        <?php $room = $rooms[$i]; ?>
                        <?php
                        $firstImage = $room->roomImages[0] ?? null;
                        $imageUrl = $firstImage ? Url::to(['room/display-image', 'id' => $firstImage->id]) : '/web/img/no-image.jpg';
                        ?>
                        <div class="room-card-sm">
                            <div class="room-sm-img">
                                <img src="<?= $imageUrl ?>" alt="<?= Html::encode($room->roomType->name) ?>">
                            </div>
                            <div class="room-sm-body">
                                <div class="room-sm-name"><?= Html::encode($room->roomType->name) ?> <?= $room->number_guests ?>-местный</div>
                                <div class="room-sm-price"><?= number_format($room->price_per_day, 0, '', ' ') ?> ₽ <span>/ ночь</span></div>
                            </div>
                        </div>
                    <?php endfor; ?>
                    <!-- Заглушки, если номеров меньше 4 -->
                    <?php for ($i = count($rooms); $i <= 3; $i++): ?>
                        <div class="room-card-sm" style="opacity:0.6;">
                            <div class="room-sm-img">
                                <img src="/web/img/no-image.jpg" alt="Нет фото">
                            </div>
                            <div class="room-sm-body">
                                <div class="room-sm-name">Скоро появится</div>
                                <div class="room-sm-price">— ₽</div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>

                <div class="rooms-footer">
                    <span class="rooms-count">Все номера санатория</span>
                    <?= Html::a('Все номера →', ['/catalog'], ['class' => 'btn-all-rooms']) ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== МАРШРУТЫ ========== -->
    <?php if (!empty($routes)): ?>
        <section class="routes-section">
            <div class="routes-grid">
                <div class="route-col-1">
                    <div>
                        <div class="section-title" style="margin-bottom:0">
                            Подбери <em>идеальный</em><br>маршрут под себя
                        </div>
                    </div>
                    <?php $route1 = $routes[0] ?? null; ?>
                    <?php if ($route1): ?>
                        <a href="<?= Url::to(['account/index', '#' => 'routes']) ?>" class="route-card" style="min-height:240px; display:block; text-decoration:none;">
                            <img src="<?= $route1->imageUrl ?: '/web/img/no-image.jpg' ?>" alt="<?= Html::encode($route1->name) ?>" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover;">
                            <div class="route-card-overlay">
                                <div class="route-card-level"><?= Html::encode($route1->level->title ?? '') ?></div>
                                <div class="route-card-name"><?= Html::encode($route1->name) ?></div>
                                <div class="route-card-desc"><?= Html::encode(mb_substr($route1->description, 0, 80)) ?>…</div>
                            </div>
                        </a>
                    <?php else: ?>
                        <div class="route-placeholder" style="min-height:240px;">Скоро появится</div>
                    <?php endif; ?>
                </div>

                <!-- Колонка 2: высокая карточка (второй маршрут) -->
                <div class="route-col-2">
                    <?php $route2 = $routes[1] ?? null; ?>
                    <?php if ($route2): ?>
                        <a href="<?= Url::to(['account/index', '#' => 'routes']) ?>" class="route-card route-card-tall" style="width:100%; display:block; text-decoration:none;">
                            <img src="<?= $route2->imageUrl ?: '/web/img/no-image.jpg' ?>" alt="<?= Html::encode($route2->name) ?>" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover;">
                            <div class="route-card-overlay">
                                <div class="route-card-level"><?= Html::encode($route2->level->title ?? '') ?></div>
                                <div class="route-card-name"><?= Html::encode($route2->name) ?></div>
                                <div class="route-card-desc"><?= Html::encode(mb_substr($route2->description, 0, 100)) ?>…</div>
                            </div>
                        </a>
                    <?php else: ?>
                        <div class="route-placeholder route-card-tall" style="width:100%;">Скоро появится</div>
                    <?php endif; ?>
                </div>

                <!-- Колонка 3: средняя карточка + кнопка (третий маршрут) -->
                <div class="route-col-3">
                    <?php $route3 = $routes[2] ?? null; ?>
                    <?php if ($route3): ?>
                        <a href="<?= Url::to(['account/index', '#' => 'routes']) ?>" class="route-card route-card-medium" style="display:block; text-decoration:none;">
                            <img src="<?= $route3->imageUrl ?: '/web/img/no-image.jpg' ?>" alt="<?= Html::encode($route3->name) ?>" style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover;">
                            <div class="route-card-overlay">
                                <div class="route-card-level"><?= Html::encode($route3->level->title ?? '') ?></div>
                                <div class="route-card-name"><?= Html::encode($route3->name) ?></div>
                                <div class="route-card-desc"><?= Html::encode(mb_substr($route3->description, 0, 70)) ?>…</div>
                            </div>
                        </a>
                    <?php else: ?>
                        <div class="route-placeholder route-card-medium">Скоро появится</div>
                    <?php endif; ?>
                    <a href="<?= Yii::$app->user->isGuest ? Url::to(['site/login']) : Url::to(['account/index', '#' => 'routes']) ?>" class="route-more-btn">
                        Узнать больше
                        <div class="route-more-arrow">→</div>
                    </a>
                </div>

            </div>
        </section>
    <?php endif; ?>

    <!-- ========== ОТЗЫВЫ ========== -->
    <!-- <section class="reviews-section">
        <div class="reviews-header">
            <div>
                <div class="section-title" style="margin-bottom:0">
                    Отзывы <em>гостей</em>
                </div>
            </div>
        </div>

        <div class="reviews-grid">
            <div class="review-card">
                <div class="review-quote">"</div>
                <div class="review-header">
                    <div class="review-avatar">АП</div>
                    <div>
                        <div class="review-name">Анна Петрова</div>
                        <div class="review-date">25.11.2023</div>
                    </div>
                </div>
                <div class="review-stars">★★★★★</div>
                <p class="review-text">
                    Прекрасное место! Воздух чистейший, персонал внимательный.
                    Байкал рядом — это само по себе уже счастье. Обязательно вернёмся.
                </p>
            </div>

            <div class="review-card">
                <div class="review-quote">"</div>
                <div class="review-header">
                    <div class="review-avatar">ИС</div>
                    <div>
                        <div class="review-name">Иван Смирнов</div>
                        <div class="review-date">14.09.2023</div>
                    </div>
                </div>
                <div class="review-stars">★★★★☆</div>
                <p class="review-text">
                    Отличные процедуры, очень помогло. Номера уютные, вид на лес.
                    Санаторий приятно удивил уровнем обслуживания.
                </p>
            </div>

            <div class="review-card">
                <div class="review-quote">"</div>
                <div class="review-header">
                    <div class="review-avatar">МК</div>
                    <div>
                        <div class="review-name">Мария Козлова</div>
                        <div class="review-date">03.07.2023</div>
                    </div>
                </div>
                <div class="review-stars">★★★★★</div>
                <p class="review-text">
                    Приехала на 14 дней с программой кардиореабилитации.
                    Результат превзошёл ожидания, врачи внимательные и профессиональные.
                </p>
            </div>
        </div>
    </section> -->
    <section class="reviews-section">
        <div class="reviews-header">
            <div>
                <div class="section-title" style="margin-bottom:0">
                    Отзывы <em>гостей</em>
                </div>
            </div>
        </div>

        <?php if (empty($reviews)): ?>
            <p style="color:#999; font-size:14px; text-align:center; padding:32px 0">
                Пока нет отзывов. Станьте первым гостем!
            </p>
        <?php else: ?>
            <div class="reviews-grid">
                <?php foreach ($reviews as $review): ?>
                    <?php
                    /** @var app\models\Review $review */
                    $user     = $review->user;
                    $initials = mb_strtoupper(
                        mb_substr($user->surname ?? '', 0, 1) . mb_substr($user->name ?? '', 0, 1)
                    );
                    ?>
                    <div class="review-card">
                        <div class="review-quote">"</div>
                        <div class="review-header">
                            <div class="review-avatar"><?= \yii\helpers\Html::encode($initials) ?></div>
                            <div>
                                <div class="review-name">
                                    <?= \yii\helpers\Html::encode(($user->surname ?? '') . ' ' . ($user->name ?? '')) ?>
                                </div>
                                <div class="review-date">
                                    <?= Yii::$app->formatter->asDate($review->created_at, 'php:d.m.Y') ?>
                                </div>
                            </div>
                        </div>
                        <div class="review-stars-wrap">
                            <?= \kartik\rating\StarRating::widget([
                                'bsVersion'     => '5.x',
                                'name'          => 'rs_' . $review->id,
                                'value'         => $review->stars,
                                'pluginOptions' => [
                                    'size'        => 'xs',
                                    'readonly'    => true,
                                    'showClear'   => false,
                                    'showCaption' => false,
                                    'displayOnly' => true,
                                ],
                            ]) ?>
                        </div>
                        <p class="review-text"><?= \yii\helpers\Html::encode($review->comment) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </section>

    <!-- ========== КАК ДОБРАТЬСЯ ========== -->
    <section class="map-section">
        <div>
            <div class="section-title" style="font-size:26px">
                Республика Бурятия,<br>п. <em>Танхой</em>
            </div>
            <p class="route-address">
                ул. Пионерская, 1А, Кабанский район
            </p>
            <div class="route-cards">
                <div class="transport-card">
                    <div class="route-card-title">Из Иркутска</div>
                    <div class="route-card-text">
                        220 км &middot; маршрутка №1059 &middot; ~3 ч<br>
                        238 км &middot; поезд &middot; ~4 ч 30 мин
                    </div>
                </div>
                <div class="transport-card">
                    <div class="route-card-title">От ж/д станции</div>
                    <div class="route-card-text">
                        12 минут пешком: прямо по Центральной,<br>
                        налево до Пионерской, ещё раз налево
                    </div>
                </div>
            </div>
        </div>

        <div class="map-wrap">
            <script type="text/javascript" charset="utf-8" async
                src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A239a693b0c183898581f0620fe61b32b0536c542282248242141eb39008e2368&width=100%&height=340&lang=ru_RU&scroll=true">
            </script>
        </div>
    </section>

</div>
<script>
    document.querySelectorAll('.room-card-big, .room-card-sm').forEach(card => {
        card.style.cursor = 'pointer';
        card.addEventListener('click', () => {
            window.location.href = '<?= Url::to(['/catalog']) ?>';
        });
    });
</script>