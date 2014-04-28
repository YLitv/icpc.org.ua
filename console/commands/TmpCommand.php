<?php

class TmpCommand extends \console\ext\ConsoleCommand
{

    /**
     * Set isApproved* properties for all users
     *
     * @version 3.0
     */
    public function actionSetIsApprovedProperties()
    {
        $users = \common\models\User::model()->findAll();

        foreach ($users as $user) {
            echo '.';
            $user->isApprovedStudent = \yii::app()->authManager->checkAccess(\common\models\User::ROLE_STUDENT, $user->_id);
            $user->isApprovedCoach = \yii::app()->authManager->checkAccess(\common\models\User::ROLE_COACH, $user->_id);
            $user->isApprovedCoordinator = \yii::app()->authManager->checkAccess($user->coordinator, $user->_id);
            $user->save(false);
        }
        echo "\nDone";
    }

    /**
     * Convert dates of birth to timestamps for all users
     *
     * @version 3.0
     */
    public function actionConvertBirthDates()
    {
        $users = \common\models\User::model()->findAll();

        foreach ($users as $user) {
            echo '.';
            $user->info->scenario = \common\models\User\InfoAbstract::SC_CONSOLE_DATE_OF_BIRTH_CONVERT;
            if (is_string($user->info->dateOfBirth)) {
                $user->info->dateOfBirth = strtotime($user->info->dateOfBirth);
            }
            if ($user->info->dateOfBirth === false) {
                $user->info->dateOfBirth = null;
            }
            $user->info->save(false);
        }

        echo "\nDone";
    }

}