<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->title = 'Танхой';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <div class="card text-dark" style="height: 500px; overflow: hidden;">
            <img src="/web/img/123456.jpg" class="card-img" alt="..." style="width: 100%; height: 100%; object-fit: cover;">
            <div class="card-img-overlay d-flex flex-column justify-content-end align-items-center text-center">
                <?= Html::a('Забронировать поездку', ['/catalog'], ['class' => 'btn register btn-lg']) ?>
            </div>
        </div>
        <h1 class="display-4">Санаторий "Танхой"</h1>
    </div>

    <div class="body-content">

        <div>
            <h2 class="text-center">ОТЗЫВЫ</h2>
            <div class="row">
                <div class="col-md-4 mb-4 d-flex">
                    <div class="card" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">Card subtitle</h6>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion mb-3" id="accordionExample">
            <h2 class="text-center">ЧАСТО ЗАДАВАЕМЫЕ ВОПРОСЫ</h2>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseTwo">
                        Что входит в цены, указанные на сайте?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <strong>В цены входит:</strong>
                        <ul>
                            <li>Проживание</li>
                            <li>Питание</li>
                            <li>Комфортные номера c Wi-Fi и спутниковым телевидением</li>
                            <li>Фитотерапия</li>
                            <li>Процедуры</li>
                            <li>Маршруты</li>
                            <!-- <li>И пятый</li>
                            <li>И пятый</li> -->
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Есть ли скидки на приобретение путевок для пенсионеров, детей и других категорий граждан?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <p>Скидка 10% на оздоровительные программы пенсионерам в санатории "Танхой". При заселении необходимо предоставить пенсионное удостоверение.</p>
                        <p>*Скидка не распространяется на отдельные процедуры.</p>
                        **Скидка не суммируется с другими акционными предложениями.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Есть ли на территории санатория парковка для автомобилей?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        На территории есть закрытая парковка во дворе санатория под шлагбаумом. Парковка оплачивается на месте на стойке размещения, парковочное место заранее не бронируется. </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseThree">
                        Проводят ли процедуры в выходные дни?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        Процедуры в санатории проводят ежедневно с 8:00 до 20:00
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseThree">
                        Где и как оформить сан-кур карту и что туда входит?
                    </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        Санаторно-курортную карту (форму 072/у) можно оформить в государственной поликлинике или
                        частном медицинском центре. Также карту могут выдать у частного врача (терапевта или семейного врача)
                        с соответствующей лицензией. </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row justify-content-start">
                <div class="col-12 col-md-6">
                    <h2 class="mb-3">СХЕМА ПРОЕЗДА</h2>
                    <h3 class="mt-3">Адрес:</h3>
                    <t class="text-secondary">Россия, Республика Бурятия, Кабанский район, посёлок Танхой, Пионерская улица, 1А</t>
                    <hr>
                    <h3>Как добраться:</h3>
                    <t class="text-secondary">
                        <span class="fw-bold">Из Иркутска:</span>
                        <p>220 км, 3 ч. 4 мин на маршрутке №1059<br>
                            238 км, 4 ч. 28 мин на поезде</p>
                    </t>
                    <t class="text-secondary">
                        <span class="fw-bold">От ЖД станции:</span>
                        <p>12 мин пешком, от вокзала прямо, на Центральную улицу, налево до перекрестка с Пионерской улицей, на нем налево и на следующем перекрёстке ещё раз поверните налево</p>
                    </t>
                </div>
                <div class="col-12 col-md-6 yandex-map">
                    <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A239a693b0c183898581f0620fe61b32b0536c542282248242141eb39008e2368&amp;width=100%&amp;height=400&amp;lang=ru_RU&amp;scroll=true"></script>
                </div>
            </div>
        </div>

    </div>
</div>