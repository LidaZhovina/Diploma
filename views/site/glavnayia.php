<?php

/** @var yii\web\View $this */
use yii\bootstrap5\Html;

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
            <div class="room-card-big">
                <img src="/img/room-big.jpg" alt="Люкс">
                <div class="room-big-overlay"></div>
                <div class="room-big-body">
                    <span class="room-promo-badge">Специальное предложение</span>
                    <div class="room-big-name">Люкс 2-местный</div>
                    <div class="room-big-price">
                        от 6 500 ₽ <span>/ ночь</span>
                    </div>
                </div>
            </div>

            <div>
                <!-- Малые карточки -->
                <div class="rooms-small-grid">
                    <div class="room-card-sm">
                        <div class="room-sm-img">
                            <img src="/img/room1.jpg" alt="Стандарт">
                        </div>
                        <div class="room-sm-body">
                            <div class="room-sm-name">Стандарт 1-местный</div>
                            <div class="room-sm-price">2 800 ₽ <span>/ ночь</span></div>
                        </div>
                    </div>
                    <div class="room-card-sm">
                        <div class="room-sm-img">
                            <img src="/img/room2.jpg" alt="Стандарт 2">
                        </div>
                        <div class="room-sm-body">
                            <div class="room-sm-name">Стандарт 2-местный</div>
                            <div class="room-sm-price">3 200 ₽ <span>/ ночь</span></div>
                        </div>
                    </div>
                    <div class="room-card-sm">
                        <div class="room-sm-img">
                            <img src="/img/room3.jpg" alt="Семейный">
                        </div>
                        <div class="room-sm-body">
                            <div class="room-sm-name">Семейный 4-местный</div>
                            <div class="room-sm-price">5 400 ₽ <span>/ ночь</span></div>
                        </div>
                    </div>
                </div>

                <div class="rooms-footer">
                    <span class="rooms-count">Все номера санатория</span>
                    <?= Html::a('Все номера →', ['/catalog'], ['class' => 'btn-all-rooms']) ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== ПРЕИМУЩЕСТВА ========== -->
    <section class="benefits-section">
        <div>
            <p class="benefits-lead">
                Инвестируйте в своё здоровье вместе с санаторием в
                <strong>заповедном</strong> байкальском регионе, чтобы обрести
                <strong>природное восстановление</strong>
                и <strong>уникальный климат</strong>
            </p>

            <div class="benefits-grid">
                <div class="benefit-card">
                    <div class="benefit-icon">🌿</div>
                    <div>
                        <div class="benefit-title">Природа Байкала</div>
                        <div class="benefit-desc">
                            Уникальный микроклимат и чистейший воздух заповедника
                        </div>
                    </div>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon">💆</div>
                    <div>
                        <div class="benefit-title">Релаксация</div>
                        <div class="benefit-desc">
                            Близость к природе снижает стресс и улучшает самочувствие
                        </div>
                    </div>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon">🏥</div>
                    <div>
                        <div class="benefit-title">Лечение</div>
                        <div class="benefit-desc">
                            40+ лечебных процедур ежедневно с 8:00 до 20:00
                        </div>
                    </div>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon">🚶</div>
                    <div>
                        <div class="benefit-title">Маршруты</div>
                        <div class="benefit-desc">
                            Пешие и велосипедные прогулки по заповеднику
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="benefits-img-wrap">
            <img src="/img/territory/4.jpg" alt="Санаторий">
        </div>
    </section>

    <!-- ========== ОТЗЫВЫ ========== -->
    <section class="reviews-section">
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
                <div class="route-card">
                    <div class="route-card-title">Из Иркутска</div>
                    <div class="route-card-text">
                        220 км &middot; маршрутка №1059 &middot; ~3 ч<br>
                        238 км &middot; поезд &middot; ~4 ч 30 мин
                    </div>
                </div>
                <div class="route-card">
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