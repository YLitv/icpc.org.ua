<script type="text/javascript">
    $(document).ready(function() {
        new appDocsItem();
    });
</script>

<div class="page-header">
    <h1><?=\yii::t('app', 'Guidance docs')?></h1>
</div>

<?php if (\yii::app()->user->checkAccess('documentCreate')): ?>
    <a href="<?=$this->createUrl('/staff/docs/create', array('type' => \common\models\Document::TYPE_GUIDANCE))?>" class="btn btn-success btn-lg">
        <?=\yii::t('app', 'Upload Doc')?>
    </a>
    <hr />
<?php endif; ?>

<?php foreach ($documentList as $document): ?>
    <?php \web\widgets\document\Row::create(array('document' => $document)); ?>
<?php endforeach; ?>