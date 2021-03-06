<?php

use \common\models\Result;

class ResultCommand extends \console\ext\ConsoleCommand
{

    /**
     * Method which deletes results for specified year and geo info
     * 
     * @param int    $year
     * @param string $geo
     */
    public function actionDelete($year, $geo)
    {
        if (isset($year) && isset($geo)) {
            $criteria = new \EMongoCriteria();
            $criteria
                ->addCond('year', '==', (int)$year)
                ->addCond('geo', '==', $geo);
            Result::model()->deleteAll($criteria);
            echo "Results for year {$year} and geo = {$geo} were successfully deleted\n";
        } else {
            echo "Error! Specify year and geo of results to delete\n";
        }
    }
}