<script type="text/javascript">
    $(document).ready(function() {
        $('.news-status-switcher').staffNewsStatusSwitcher();
    });
</script>

<div class="news-status-switcher" data-news-id="<?=$this->news->commonId?>">
    <button type="button" class="btn btn-lg btn-success <?=$this->news->isPublished ? 'hide' : ''?>"
            <?=$this->news->isNewRecord ? 'disabled' : ''?>
            data-status="1">
        <?=\yii::t('app', 'Publish')?>
    </button>
    <button type="button" class="btn btn-lg btn-danger <?=$this->news->isPublished ? '' : 'hide'?>"
            <?=$this->news->isNewRecord ? 'disabled' : ''?>
            data-status="0">
        <?=\yii::t('app', 'Hide')?>
    </button>
</div>