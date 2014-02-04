<?php

namespace web\controllers;

use \common\models\Team;

class TeamController extends \web\ext\Controller
{

    /**
     * Init
     */
    public function init()
    {
        parent::init();

        // Set default action
        $this->defaultAction = 'list';

        // Set active main menu item
        $this->setNavActiveItem('main', 'team');
    }

    /**
     * List all the teams
     */
    public function actionList()
    {
        // Get params
        $year = $this->getYear();

        // Get list of teams
        $teamsCount = Team::model()->countByAttributes(array(
            'year' => $year,
        ));

        // Render view
        $this->render('list', array(
            'user'          => \yii::app()->user->getInstance(),
            'year'          => $year,
            'teamsCount'    => $teamsCount,
        ));
    }

    /**
     * Method which shows the information about team
     */
    public function actionView()
    {
        // Get params
        $teamId = $this->request->getParam('id');

        // Get team
        $team = Team::model()->findByPk(new \MongoId($teamId));
        if ($team === null) {
            $this->httpException(404);
        }

        // Render view
        $this->render('view', array(
            'team'    => $team,
            'coach'   => $team->coach,
            'members' => $team->members
        ));
    }

    /**
     * Method for jqGrid which returns all the items to be shown
     */
    public function actionGetTeamListJson()
    {
        $lang = \yii::app()->language;

        // Get jqGrid params
        $criteria = new \EMongoCriteria();
        $criteria->addCond('year', '==', $this->getYear());
        $jqgrid = $this->_getJqgridParams(Team::model(), $criteria);

        // Fill rows
        $rows = array();
        foreach ($jqgrid['itemList'] as $team) {
            $members = $team->members;
            $members_arr = array();
            foreach ($members as $member) {
                $members_arr[] = \web\widgets\user\Name::create(array('user' => $member), true);
            }
            $members_str = implode(', ', $members_arr);
            $rows[] = array(
                'id'                            => (string)$team->_id,
                'name'                          => $team->name,
                'schoolName' . ucfirst($lang)   => $team->schoolName,
                'coachName' . ucfirst($lang)    => $team->coachName,
                'members'                       => $members_str,
                'year'                          => $team->year,
                'state'                         => $team->getStateLabel(),
                'region'                        => $team->getRegionLabel(),
                'phase'                         => $team->phase,
            );
        }

        // Render json
        $this->renderJson(array(
            'page'      => $jqgrid['page'],
            'total'     => ceil($jqgrid['totalCount'] / $jqgrid['perPage']),
            'records'   => count($jqgrid['itemList']),
            'rows'      => $rows,
        ));
    }

    /**
     * Action to export teams list in CSV
     */
    public function actionCsv()
    {
        // Get params
        $phase = \yii::app()->request->getParam('phase');

        // Set headers
        header("Content-Disposition: attachment; filename=\"icpc_teams_{$this->getYear()}_{$phase}.csv\"");
        header('Content-Type: text/csv; charset=UTF-16LE');

        // Get list of teams
        $criteria = new \EMongoCriteria();
        $criteria->addCond('year', '==', (int)$this->getYear());
        $criteria->addCond('phase', '>=', (int)$phase);
        $teams = Team::model()->findAll($criteria);

        // Send content
        $fileHandler = fopen('php://output', 'w');
        fwrite($fileHandler, "sep=,\n");
        foreach ($teams as $team) {
            fputcsv($fileHandler, array(implode(',', array(
                $team->name, $team->school->fullNameUk, $team->school->shortNameUk, $team->coachNameUk
            ))));
        }
        fclose($fileHandler);
    }

}