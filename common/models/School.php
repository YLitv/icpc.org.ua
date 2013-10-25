<?php

namespace common\models;

/**
 * School (university, academy, institute)
 */
class School extends \common\ext\MongoDb\Document
{

    /**
     * Full university name in ukranian
     * @var string
     */
    public $fullNameUk;

    /**
     * Full university name in english
     * @var string
     */
    public $fullNameEn;

    /**
     * Short university name in ukrainian
     * @var string
     */
    public $shortNameUk;

    /**
     * Short university name in english
     * @var string
     */
    public $shortNameEn;

    /**
     * State
     * @var string
     */
    public $state;

    /**
     * Region
     * @var string
     */
    public $region;

    /**
     * Returns the attribute labels.
     *
     * Note, in order to inherit labels defined in the parent class, a child class needs to
     * merge the parent labels with child labels using functions like array_merge().
     *
     * @return array attribute labels (name => label)
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'fullNameUk'    => \yii::t('app', 'Full university name in ukrainian'),
            'fullNameEn'    => \yii::t('app', 'Full university name in english'),
            'shortNameUk'   => \yii::t('app', 'Short university name in ukrainian'),
            'shortNameEn'   => \yii::t('app', 'Short university name in english'),
            'state'         => \yii::t('app', 'State'),
            'region'        => \yii::t('app', 'Region'),

        ));
    }

    /**
     * Define attribute rules
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('fullNameUk, state, region', 'required'),
            array('fullNameUk, fullNameEn, shortNameUk, shortNameEn', 'unique'),
        ));
    }

    /**
     * This returns the name of the collection for this class
     *
     * @return string
     */
    public function getCollectionName()
    {
        return 'school';
    }

    /**
     * List of collection indexes
     *
     * @return array
     */
    public function indexes()
    {
        return array_merge(parent::indexes(), array(
            'fullNameUk' => array(
                'key' => array(
                    'fullNameUk' => \EMongoCriteria::SORT_ASC,
                ),
                'unique' => true,
            ),
            'fullNameEn' => array(
                'key' => array(
                    'fullNameEn' => \EMongoCriteria::SORT_ASC,
                ),
            ),
            'shortNameUk' => array(
                'key' => array(
                    'shortNameUk' => \EMongoCriteria::SORT_ASC,
                ),
            ),
            'shortNameEn' => array(
                'key' => array(
                    'shortNameEn' => \EMongoCriteria::SORT_ASC,
                ),
            ),
        ));
    }

    /**
     * Before validate action
     *
     * @return bool
     */
    protected function beforeValidate()
    {
        if (!parent::beforeValidate()) return false;

        // State
        if (!in_array($this->state, Geo\State::model()->getConstantList('NAME_'))) {
            $this->addError('state', \yii::t('app', 'Unknown state name.'));
        }

        // Region
        if (!in_array($this->region, Geo\Region::model()->getConstantList('NAME_'))) {
            $this->addError('region', \yii::t('app', 'Unknown region name.'));
        }

        return true;
    }

}