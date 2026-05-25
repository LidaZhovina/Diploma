<?php

/** @var array $data */
?>

<?php
/** @var array $data */
?>
<!DOCTYPE html>
<html>

<head>
    <title>Чек об оплате</title>
    <meta charset="utf-8">
    <style>
        @font-face {
            font-family: Hero;
            src: url('/web//fonts/ofont.ru_Hero.ttf');
        }

        body {
            font-family: Hero;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #d8e9f7;
            color: #251862;
            padding: 15px;
            text-align: center;
        }

        .content {
            padding: 20px;
            background-color: #ffffff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src=/img/logo.jpg alt=logo class="logo"> Танхой
            <h2>Чек об оплате бронирования №<?= $data['bookingId'] ?></h2>
            <p><?= date("d.m.Y H:i") ?></p>
        </div>
        <div class="content">
            <p><strong>Даты проживания:</strong> <?= $data['arrival_date'] ?> – <?= $data['departure_date'] ?></p>
            <p><strong>Номер:</strong> <?= $data['room_type'] ?></p>

            <h3>Гости и оздоровительные программы</h3>
            <table>
                <thead>
                    <tr>
                        <th>ФИО</th>
                        <th>Дата рождения</th>
                        <th>Оздоровительная программа</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['guests'] as $guest): ?>
                        <tr>
                            <td><?= htmlspecialchars($guest['name']) ?></td>
                            <td><?= $guest['birth_date'] ?></td>
                            <td><?= htmlspecialchars($guest['wellness_program']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p><strong>Полная стоимость:</strong> <?= $data['total_price'] ?> руб.</p>
            <p><strong>Предоплата (30%):</strong> <?= $data['payment'] ?> руб.</p>
            <p><strong>Остаток к оплате при заезде:</strong> <?= $data['total_price'] - $data['payment'] ?> руб.</p>
            <p>Благодарим за выбор нашего санатория!</p>
        </div>
        <div class="footer">
            Это автоматическое сообщение, пожалуйста, не отвечайте на него.
        </div>
    </div>
</body>

</html>