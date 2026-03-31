<?php

use yii\helpers\Html;
?>
<div class="card w-100">
    <div class="card-header">
        <div id="carouselExampleIndicators" class="carousel slide">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/web/img/room-3.2.webp" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="/web/img/room3.4.webp" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="/web/img/room-3.1.webp" class="d-block w-100" alt="...">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <!-- <img src="/web/img/room-3.2.webp" class="card-img-top" alt="..."> -->
    <div class="card-body">
        <h5 class="card-title"><?= $model->roomType->name . " " . $model->number_guests . " " . "местный" ?></h5>
        <div>
            <span class="fw-bold text-secondary">Цена за ночь: </span> <?= $model->price_per_day . "₽" ?>
        </div>
    </div>
    <div class="card-footer">
        <!-- <?= Html::a('Забронировать', ['index', 'id' => $model->id], ['class' => 'btn register']) ?> -->
        <?= Html::a('Подробнее', ['view', 'id' => $model->id], ['class' => 'btn register']) ?>
    </div>
</div>