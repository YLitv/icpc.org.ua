<div class="row">
    <div class="col-lg-6 col-lg-offset-3">
        <h1>
            <?=\CHtml::encode($team->name)?>
            <?php if ($team->coachId === \yii::app()->user->id): ?>
                <a href="<?=$this->createUrl('staff/team/manage', array('id' => $team->_id))?>" class="btn btn-primary"><?=\yii::t('app', 'Manage')?></a>
            <?php endif; ?>
        </h1>
        <h3><?=$team->year?></h3>
        <strong><?=\yii::t('app', 'Coach')?></strong>:
        <?php \web\widgets\user\Name::create(array('user' => $coach)); ?>
        <br />
        <strong><?=\yii::t('app', 'School')?></strong>:
        <?=\CHtml::encode($team->school->fullNameUk)?>
        <br />
        <strong><?=\yii::t('app', 'Participants')?></strong>:
        <ul>
            <?php foreach ($members as $member): ?>
            <li><?php \web\widgets\user\Name::create(array('user' => $member)); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php if (count($results)): ?>
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            <h2><?=\yii::t('app', 'Results')?></h2>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><?=\yii::t('app', 'Place')?></th>
                        <th><?=\yii::t('app', 'Phase')?></th>
                        <th><?=\yii::t('app', 'Total')?></th>
                        <th><?=\yii::t('app', 'Penalty')?></th>
                        <th><?=\yii::t('app', 'Details')?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($results as $result): ?>
                        <tr>
                            <td><?=$result->place?></td>
                            <td><?=$result->phase?></td>
                            <td><?=$result->total?></td>
                            <td><?=$result->penalty?></td>
                            <td>
                                <a href="<?=$this->createUrl('/results/view', array(
                                    'year'              => $result->year,
                                    'phase'             => $result->phase,
                                    $result->geoType    => $result->geo,
                                ))?>"><?=\yii::t('app', 'View')?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>