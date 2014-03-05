<?php
    use \common\components\Rbac;
    use \common\models\User;
?>

<script type="text/javascript">
    $(document).ready(function() {
        new appStaffCoordinatorsIndex();
    });
</script>

<h3><?=\yii::t('app', 'List of Coordinators')?></h3>

<table class="table table-row-middle">
    <thead>
        <tr>
            <th><?=\yii::t('app', 'Name')?></th>
            <th><?=\yii::t('app', 'Email')?></th>
            <th><?=\yii::t('app', 'Registration date')?></th>
            <th><?=\yii::t('app', 'Coordinates')?></th>
            <th><?=\yii::t('app', 'Action')?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($userList as $user): ?>
            <tr data-id="<?=$user->_id?>">
                <td>
                    <a href="<?=$this->createUrl('/user/view', array('id' => (string)$user->_id))?>">
                        <?php \web\widgets\user\Name::create(array('user' => $user)); ?>
                    </a>
                </td>
                <td><?=$user->email?></td>
                <td><?=date('Y-m-d H:i:s', $user->dateCreated)?></td>
                <td><strong>
                    <?php if ($user->school->isNewRecord): ?>
                        <?=$user->getAttributeLabel($user->coordinator, 'coord')?>
                    <?php else: ?>
                        <?php switch ($user->coordinator) {
                            case User::ROLE_COORDINATOR_UKRAINE:
                                echo $user->school->countryLabel;
                                break;
                            case User::ROLE_COORDINATOR_REGION:
                                echo $user->school->regionLabel;
                                break;
                            case User::ROLE_COORDINATOR_STATE:
                                echo $user->school->stateLabel;
                                break;
                        } ?>
                    <?php endif; ?>
                </strong></td>
                <td>
                    <button type="button" class="btn btn-success coordinator-state <?=$user->isApprovedCoordinator ? 'hide' : ''?>"
                            <?=(\yii::app()->user->checkAccess(Rbac::OP_COORDINATOR_SET_STATUS, array('user' => $user))) ? '' : 'disabled'?>
                            data-state="1">
                        <?=\yii::t('app', 'Activate')?>
                    </button>
                    <button type="button" class="btn btn-danger coordinator-state <?=$user->isApprovedCoordinator ? '' : 'hide'?>"
                            <?=(\yii::app()->user->checkAccess(Rbac::OP_COORDINATOR_SET_STATUS, array('user' => $user))) ? '' : 'disabled'?>
                            data-state="0">
                        <?=\yii::t('app', 'Suspend')?>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>