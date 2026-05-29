<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\rating\StarRating;

/** @var yii\web\View $this */
/** @var app\models\Room $model */

$this->title = $model->roomType->name . ' ' . $model->number_guests . '-местный';
$this->registerCssFile('@web/css/room-view.css');
\yii\web\YiiAsset::register($this);

$images = $model->roomImages;
$firstImage = $images[0] ?? null;
?>

<div class="room-view container">

    <div class="room-layout">

        <!-- ═══ ГАЛЕРЕЯ ═══ -->
        <div class="room-gallery">

            <?php if (!empty($images)): ?>
                <div id="roomCarousel" class="carousel slide room-main-img" data-bs-ride="false">
                    <div class="carousel-inner h-100">
                        <?php foreach ($images as $i => $image): ?>
                            <div class="carousel-item h-100 <?= $i === 0 ? 'active' : '' ?>">
                                <img src="<?= Url::to(['room/display-image', 'id' => $image->id]) ?>"
                                    class="d-block w-100 h-100"
                                    style="object-fit:cover"
                                    alt="Фото номера">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($images) > 1): ?>
                        <div class="gallery-nav">
                            <button class="gnav-btn"
                                data-bs-target="#roomCarousel"
                                data-bs-slide="prev">←</button>
                            <button class="gnav-btn"
                                data-bs-target="#roomCarousel"
                                data-bs-slide="next">→</button>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Миниатюры -->
                <?php if (count($images) > 1): ?>
                    <div class="room-thumbs">
                        <?php foreach (array_slice($images, 0, 3) as $i => $image): ?>
                            <div class="room-thumb <?= $i === 0 ? 'active' : '' ?>"
                                data-bs-target="#roomCarousel"
                                data-bs-slide-to="<?= $i ?>">
                                <img src="<?= Url::to(['room/display-image', 'id' => $image->id]) ?>"
                                    alt="Фото <?= $i + 1 ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="room-main-img room-no-photo">
                    <span>Фото отсутствует</span>
                </div>
            <?php endif; ?>

        </div>

        <!-- ═══ ИНФОРМАЦИЯ ═══ -->
        <div class="room-info">

            <h1 class="room-title"><?= Html::encode($this->title) ?></h1>

            <div class="room-meta-row">
                <div class="room-meta-pill">
                    <span>🏠</span> <?= Html::encode($model->roomType->name) ?>
                </div>
                <div class="room-meta-pill">
                    <span>👥</span> <?= $model->number_guests ?> места
                </div>
            </div>

            <!-- Описание -->
            <?php if ($model->description): ?>
                <p class="room-desc"><?= Html::encode($model->description) ?></p>
            <?php endif; ?>

            <div class="room-amenities-title">Удобства</div>
            <div class="room-amenities">
                <div class="amenity" data-tip="Wi-Fi">📶</div>
                <div class="amenity" data-tip="Телевизор">📺</div>
                <div class="amenity" data-tip="Кондиционер">❄️</div>
                <div class="amenity" data-tip="Душ/ванна">🚿</div>
                <div class="amenity" data-tip="Кровать">🛏️</div>
                <div class="amenity" data-tip="Питание включено">🍽️</div>
            </div>

            <!-- Цена -->
            <div class="room-price-block">
                <div class="room-price-label">Стоимость за ночь</div>
                <div class="room-price">
                    <?= number_format($model->price_per_day, 0, '.', ' ') ?> ₽
                    <span>/ ночь</span>
                </div>
                <div class="room-price-note">Предоплата 30% при бронировании</div>
            </div>

            <!-- Кнопка бронирования — только для клиентов -->
            <?php if (Yii::$app->user->identity?->isClient): ?>
                <?= Html::a(
                    'Забронировать <span class="arrow">→</span>',
                    ['account/create', 'room_id' => $model->id],
                    ['class' => 'btn-book-room', 'encode' => false]
                ) ?>
            <?php elseif (Yii::$app->user->isGuest): ?>
                <div class="room-login-note">
                    <?= Html::a('Войдите', ['site/login']) ?>
                    или
                    <?= Html::a('зарегистрируйтесь', ['site/register']) ?>
                    чтобы забронировать номер
                </div>
            <?php endif; ?>

            <!-- Кнопка назад -->
            <?= Html::a('← Вернуться к списку номеров', ['/catalog/index'], ['class' => 'btn-back']) ?>

        </div>
    </div>
</div>

<?php
$this->registerJs("
    document.querySelectorAll('.room-thumb').forEach(function(thumb, i) {
        thumb.addEventListener('click', function() {
            document.querySelectorAll('.room-thumb').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
    document.getElementById('roomCarousel')?.addEventListener('slide.bs.carousel', function(e) {
        document.querySelectorAll('.room-thumb').forEach((t, i) => {
            t.classList.toggle('active', i === e.to);
        });
    });
");
?>