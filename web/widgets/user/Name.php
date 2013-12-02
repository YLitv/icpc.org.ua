<?php

namespace web\widgets\user;

use \common\models\User;

/**
 * Renders user's full name
 */
class Name extends \web\ext\Widget
{

    /**
     * Availabel views
     */
    const VIEW_FIRST                = 'first';
    const VIEW_FIRST_MIDDLE_LAST    = 'firstMiddleLast';
    const VIEW_LAST_FIRST_MIDDLE    = 'lastFirstMiddle';

    /**
     * User
     * @var User
     */
    public $user;

    /**
     * View type
     * @var string
     */
    public $view;

    /**
     * Language in which name is needed
     * @var
     */
    public $lang;

    /**
     * Run widget
     */
    public function run()
    {
        // Prepare name parts
        $first  = \CHtml::encode($this->user->getFirstName($this->lang));
        $middle = \CHtml::encode($this->user->getMiddleName($this->lang));
        $last   = \CHtml::encode($this->user->getLastName($this->lang));

        // Render full name
        switch ($this->view) {
            case static::VIEW_FIRST:
                echo "{$first}";
                break;
            default:
            case static::VIEW_FIRST_MIDDLE_LAST:
                echo "{$first} {$middle} {$last}";
                break;
            case static::VIEW_LAST_FIRST_MIDDLE:
                echo "{$last} {$first} {$middle}";
                break;
        }
    }

}