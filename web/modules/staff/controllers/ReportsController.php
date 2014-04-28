<?php

namespace web\modules\staff\controllers;

use \common\models\Team;
use \common\models\Result;

class ReportsController extends \web\modules\staff\ext\Controller
{

    /**
     * Main page
     */
    public function actionIndex()
    {
        // Render view
        $this->render('index', array('year' => $this->year));
    }

    /**
     * Report of participants
     */
    public function actionParticipants()
    {
        // Get list of teams
        $criteria = new \EMongoCriteria();
        $criteria
            ->addCond('phase', '==', Result::PHASE_3)
            ->addCond('year', '==', $this->year)
            ->sort('schoolNameUk', \EMongoCriteria::SORT_ASC);
        $teams = Team::model()->findAll($criteria);

        // Create list of participants
        $participants = array();
        foreach ($teams as $team) {
            $participant = array(
                'schoolNameUk' => $team->schoolNameUk,
                'schoolNameEn' => $team->schoolNameEn,
                'teamName'     => $team->name,
                'coach'        => $team->coach,
            );
            foreach ($team->members as $member) {
                $participant['members'][] = $member;
            }
            $participants[] = $participant;
        }

        // Render view
        $this->layout = false;
        $this->render('participants', array(
            'participants' => $participants,
        ));
    }

    /**
     * Report of winners
     */
    public function actionWinners()
    {
        // Get list of results
        $criteria = new \EMongoCriteria();
        $criteria
            ->addCond('phase', '==', Result::PHASE_3)
            ->addCond('year', '==', $this->year)
            ->sort('place', \EMongoCriteria::SORT_ASC);
        $results = Result::model()->findAll($criteria);

        // Create list of winners
        $winners = array();
        foreach ($results as $result) {
            $winner = array(
                'place'      => $result->place,
                'teamName'   => $result->teamName,
                'schoolName' => $result->schoolNameUk,
                'tasks'      => $result->total,
                'totalTime'  => $result->penalty,
                'members'    => array(),
                'coach'      => null,
            );
            if ($result->team !== null) {
                foreach ($result->team->members as $member) {
                    $winner['members'][] = $member;
                }
                $winner['coach'] = $result->team->coach;
            }
            $winners[] = $winner;
        }

        // Render view
        $this->layout = false;
        $this->render('winners', array(
            'winners' => $winners
        ));
    }

}
