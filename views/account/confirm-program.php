<?php
use yii\bootstrap5\Html;
use yii\helpers\Url;
?>
<div class="modal show d-block" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.5);">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Выбор программы</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="window.location.href='<?= Url::to(['account/guests-data']) ?>'"></button>
            </div>
            <div class="modal-body">
                <p>Хотите выбрать оздоровительную программу?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="window.location.href='<?= Url::to(['account/select-program']) ?>'">Да</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='<?= Url::to(['account/guests-data']) ?>'">Нет</button>
            </div>
        </div>
    </div>
</div>