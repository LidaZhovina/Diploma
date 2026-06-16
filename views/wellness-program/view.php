<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\WellnessProgram $model */

$this->title = $model->title;
$this->registerCssFile('@web/css/catalog.css');
$this->registerCssFile('@web/css/wellness-view.css');
\yii\web\YiiAsset::register($this);
?>

<div class="wv-page container my-2">

    <?= Html::a(
        '<i class="ti ti-arrow-left"></i> К списку программ',
        ['index'],
        ['class' => 'wv-back', 'encode' => false]
    ) ?>

    <div class="wv-card">

        <!-- ── ШАПКА ── -->
        <div class="wv-header">
            <div class="wv-header-top">
                <div class="wv-header-icon">
                    <i class="ti ti-heart-handshake"></i>
                </div>
                <div class="wv-header-text">
                    <h1 class="wv-title"><?= Html::encode($model->title) ?></h1>
                    <p class="wv-subtitle">Оздоровительная программа санатория «Танхой»</p>
                </div>
            </div>
            <div class="wv-dur-pill">
                <i class="ti ti-clock"></i> <?= Html::encode($model->duration) ?>
            </div>
        </div>

        <!-- ── ТЕЛО ── -->
        <div class="wv-body">

            <!-- Левая колонка: описание + что входит -->
            <div class="wv-main">

                <div class="wv-section">
                    <div class="wv-section-title">
                        <i class="ti ti-align-left"></i> Описание
                    </div>
                    <p class="wv-desc"><?= nl2br(Html::encode($model->description)) ?></p>
                </div>

                <div class="wv-section">
                    <div class="wv-section-title">
                        <i class="ti ti-list-check"></i> Что входит в программу
                    </div>
                    <div class="wv-checks">
                        <div class="wv-check">
                            <i class="ti ti-check"></i>
                            Первичный приём у лечащего врача
                        </div>
                        <div class="wv-check">
                            <i class="ti ti-check"></i>
                            Индивидуальный план процедур
                        </div>
                        <div class="wv-check">
                            <i class="ti ti-check"></i>
                            Проживание в номере по выбору
                        </div>
                        <div class="wv-check">
                            <i class="ti ti-check"></i>
                            Питание в период программы
                        </div>
                        <div class="wv-check">
                            <i class="ti ti-check"></i>
                            Сопровождение врача на весь курс
                        </div>
                    </div>
                </div>

            </div>

            <!-- Правая панель: фото + инфокарточки + кнопка -->
            <div class="wv-side">

                <!-- Фото программы -->
                <div class="wv-photo">
                    <?php if ($model->imageUrl): ?>
                        <img src="<?= Html::encode($model->imageUrl) ?>"
                             alt="<?= Html::encode($model->title) ?>">
                    <?php else: ?>
                        <div class="wv-photo-placeholder">
                            <i class="ti ti-spa"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Инфокарточки в обёртке для мобильной сетки -->
                <div class="wv-info-card-wrap">
                    <div class="wv-info-card">
                        <i class="ti ti-clock"></i>
                        <div>
                            <div class="wv-info-label">Длительность</div>
                            <div class="wv-info-value"><?= Html::encode($model->duration) ?></div>
                        </div>
                    </div>
                    <div class="wv-info-card">
                        <i class="ti ti-user-check"></i>
                        <div>
                            <div class="wv-info-label">Подходит</div>
                            <div class="wv-info-value">Всем возрастам</div>
                        </div>
                    </div>
                    <div class="wv-info-card">
                        <i class="ti ti-stethoscope"></i>
                        <div>
                            <div class="wv-info-label">Врачебный контроль</div>
                            <div class="wv-info-value">Ежедневно</div>
                        </div>
                    </div>
                    <div class="wv-info-card">
                        <i class="ti ti-shield-check"></i>
                        <div>
                            <div class="wv-info-label">Формат</div>
                            <div class="wv-info-value">Индивидуально</div>
                        </div>
                    </div>
                </div>

                <!-- Кнопка назад (десктоп) -->
                <div class="wv-cta">
                    <?= Html::a(
                        '<i class="ti ti-arrow-left"></i> Назад к списку',
                        ['index'],
                        ['class' => 'wv-btn-outline', 'encode' => false]
                    ) ?>
                </div>

            </div>
        </div>

        <!-- Кнопка назад (только мобилка, внизу карточки) -->
        <div class="wv-cta">
            <?= Html::a(
                '<i class="ti ti-arrow-left"></i> Назад к списку',
                ['index'],
                ['class' => 'wv-btn-outline', 'encode' => false]
            ) ?>
        </div>

    </div>
</div>