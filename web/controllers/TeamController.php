<?php

namespace web\controllers;

use \common\models\Result;
use \common\models\Team;

class TeamController extends \web\ext\Controller
{
    /**
     * Returns the access rules for this controller
     * @return array
     */
    public function accessRules()
    {
        // Return rules
        return array(
            array(
                'allow',
                'actions'   => array('exportCheckingSystem', 'exportRegistration'),
                'roles'     => array(\common\components\Rbac::OP_TEAM_EXPORT),
            ),
            array(
                'deny',
                'actions'   => array('exportCheckingSystem', 'exportRegistration'),
            ),
            array(
                'allow',
            ),
        );
    }

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
            'lang'          => \yii::app()->languageCore,
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
        if (($team === null) || $team->isDeleted) {
            $this->httpException(404);
        }

        // Get results
        $criteria = new \EMongoCriteria();
        $criteria
            ->addCond('teamId', '==', $teamId)
            ->addCond('year', '==', $team->year)
            ->sort('phase', \EMongoCriteria::SORT_ASC);
        $results = Result::model()->findAll($criteria);

        // Render view
        $this->render('view', array(
            'team'       => $team,
            'coach'      => $team->coach,
            'members'    => $team->members,
            'results'    => $results,
        ));
    }

    /**
     * Method for jqGrid which returns all the items to be shown
     */
    public function actionGetTeamListJson()
    {
        $lang = \yii::app()->languageCore;

        // Get jqGrid params
        $criteria = new \EMongoCriteria();
        $criteria->addCond('year', '==', $this->getYear());
        $jqgrid = $this->_getJqgridParams(Team::model()->scopeByActive(), $criteria);

        // Fill rows
        $rows = array();
        $idsToRemember = array();
        foreach ($jqgrid['itemList'] as $team) {
            $members = $team->members;
            $members_arr = array();
            foreach ($members as $member) {
                $members_arr[] =
                    '<a href="' . $this->createUrl('/user/view', array('id' => (string)$member->_id)) . '">' .
                    \web\widgets\user\Name::create(array('user' => $member), true) .
                    '</a>';
            }
            $members_str = implode(', ', $members_arr);

            $idsToRemember[] = (string)$team->_id;
            $rows[] = array(
                'id'                            => (string)$team->_id,
                'name'                          => $team->name,
                'schoolName' . ucfirst($lang)   => $team->schoolName,
                'coachName' . ucfirst($lang)    => $team->coachName,
                'coachId'                       => $team->coachId,
                'members'                       => $members_str,
                'year'                          => $team->year,
                'state'                         => $team->getStateLabel(),
                'region'                        => $team->getRegionLabel(),
                'phase'                         => $team->phase,
            );
        }

        // Save filtered IDs for export
        \yii::app()->user->setState('teamIdsForExport', $idsToRemember);

        // Render json
        $this->renderJson(array(
            'page'      => $jqgrid['page'],
            'total'     => ceil($jqgrid['totalCount'] / $jqgrid['perPage']),
            'records'   => count($jqgrid['itemList']),
            'rows'      => $rows,
        ));
    }

    /**
     * Action to export teams list in CSV for checking system
     */
    public function actionExportCheckingSystem()
    {
        // Get params
        $teamIds = \yii::app()->user->getState('teamIdsForExport');

        $teamIds = array_map(function($id) {
            return new \MongoId($id);
        }, $teamIds);

        // Get list of teams
        $criteria = new \EMongoCriteria();
        $criteria->addCond('_id', 'in', $teamIds);
        $teams = Team::model()->findAll($criteria);

        // Render CSV
        $this->renderCsv($teams, "icpc_teams_cs_{$this->getYear()}.csv", function($team) {
            return array(
                $team->name, $team->school->fullNameUk, $team->school->shortNameUk, $team->coachNameUk
            );
        });
    }

    /**
     * Action to export teams list in CSV for registration
     */
    public function actionExportRegistration()
    {
        // Get params
        $teamIds = \yii::app()->user->getState('teamIdsForExport');

        $teamIds = array_map(function($id) {
            return new \MongoId($id);
        }, $teamIds);

        // Get list of teams
        $criteria = new \EMongoCriteria();
        $criteria->addCond('_id', 'in', $teamIds);
        $teams = Team::model()->findAll($criteria);

        // Render CSV
        $this->renderCsv($teams, "icpc_teams_r_{$this->getYear()}.csv", function($team) {
            $arrayToPut = array(
                $team->name, $team->school->fullNameUk, $team->school->shortNameUk, $team->coachNameUk
            );
            foreach ($team->members as $member) {
                $arrayToPut[] = \web\widgets\user\Name::create(array('user' => $member, 'lang' => 'uk'), true);
            }
            return $arrayToPut;
        });
    }

}