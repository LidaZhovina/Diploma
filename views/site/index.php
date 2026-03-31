<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->title = 'Сайт';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <div class="card text-dark" style="height: 500px; overflow: hidden;">
            <img src="/web/img/123456.jpg" class="card-img" alt="..." style="width: 100%; height: 100%; object-fit: cover;">
            <div class="card-img-overlay d-flex flex-column justify-content-end align-items-center text-center">
                <?= Html::a('Забронировать поездку', ['/catalog'], ['class' => 'btn register btn-lg']) ?>
            </div>
        </div>
        <!-- <h1 class="display-4">Добро пожаловать</h1> -->
    </div>

    <div class="body-content">

        <div class="row">

        </div>

    </div>
</div>