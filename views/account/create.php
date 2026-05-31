<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Booking $model */

$this->title = 'Бронирование';

?>
<div class="booking-create">    
    <?= $this->render('step1', [
        'model' => $model,
        'room' => $room,
    ]) ?>

</div>