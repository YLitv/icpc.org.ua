<?php

use \common\models\News;

class NewsCommand extends \console\ext\ConsoleCommand
{

    /**
     * Method which deletes news with specified id
     * @param string $id
     */
    public function actionDelete($id = null)
    {
        if (!isset($id)) {
            echo "\nError! Specify id of news to delete\n";
            exit;
        }
        $newsToDelete = News::model()->findByPk(new \MongoId((string)$id));
        if (isset($newsToDelete)) {
            $newsToDelete->delete();
            echo "\nNews with id=\'$id\' was successfully deleted\n";
        } else {
            echo "Error! News with id=$id was not found\n";
        }
    }
}