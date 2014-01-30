<?php \yii::app()->getClientScript()->registerCoreScript('jquery.jqgrid'); ?>

<div class="pull-right">
    <?php \web\widgets\filter\Year::create(array('checked' => $year)); ?>
</div>

<?php if (\yii::app()->user->checkAccess(\common\components\Rbac::OP_TEAM_CREATE)): ?>
    <a class="btn btn-success btn-lg" href="<?=$this->createUrl('/staff/team/manage')?>"><?=\yii::t('app', 'Create a new team')?></a>
    <hr>
<?php endif; ?>


<script type="text/javascript">
    $(document).ready(function(){

        var $table = $('#team-list');
        $table.jqGrid({
            url: '<?=$this->createUrl('/team/GetTeamListJson')?>',
            datatype: 'json',
            colNames: [
                '<?=\yii::t('app', 'Team name')?>',
                '<?=\yii::t('app', 'School name')?>',
                '<?=\yii::t('app', 'Coach name')?>',
                '<?=\yii::t('app', 'Members')?>',
                '<?=\yii::t('app', 'State')?>',
                '<?=\yii::t('app', 'Region')?>',
                '<?=\yii::t('app', 'Phase')?>'
            ],
            colModel: [
                {name: 'name', index: 'name', width: 20, formatter: 'showlink', formatoptions:{baseLinkUrl:'/team/view'}},
                {name: 'schoolName<?=ucfirst(\yii::app()->language)?>', index: 'schoolName<?=ucfirst(\yii::app()->language)?>', width: 20},
                {name: 'coachName<?=ucfirst(\yii::app()->language)?>', index: 'coachName<?=ucfirst(\yii::app()->language)?>', width: 15},
                {name: 'members', index: 'members', width: 40, search: false},
                {name: 'state', index: 'state', width: 15, search: false},
                {name: 'region', index: 'region', width: 10, search: false},
                {name: 'phase', index: 'phase', width: 5, searchoptions: {sopt: ['ge']}}
            ],
            sortname: 'teamname',
            sortorder: 'asc',
            autowidth: true,
            beforeSelectRow: function() {
                return false;
            }
        });
        $table.jqGrid('filterToolbar', {
            stringResult: true,
            searchOnEnter: false
        });

    });
</script>



<?php if ($teamsCount > 0): ?>

    <table id="team-list" style="width: 100%;"></table>

<?php else: ?>

    <div class="alert alert-info">
        <?=\yii::t('app', 'There are no teams.')?>
    </div>

<?php endif; ?>