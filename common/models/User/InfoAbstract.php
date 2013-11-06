<?php

namespace common\models\User;

abstract class InfoAbstract extends \common\ext\MongoDb\Document
{

    /**
     * ID of the related user
     * @var string
     */
    public $userId;

    /**
     * Lang of the information (e.g. "en", "uk")
     * @var string
     */
    public $lang;

    /**
     * Home phone number
     * @var string
     */
    public $phoneHome;

    /**
     * Mobile phone number
     * @var string
     */
    public $phoneMobile;

    /**
     * Skype
     * @var string
     */
    public $skype;

    /**
     * ACM Number
     * @var string
     */
    public $ACMNumber;

    /**
     * Institution name
     * @var string
     */
    public $instName;

    /**
     * Short form of the institution name
     * @var string
     */
    public $instNameShort;

    /**
     * Division
     * I-offers advanced degree in computer science
     * II-does not offer advanced degree in computer science
     * @var string
     */
    public $instDivision;

    /**
     * Official post and email addresses
     * @var string
     */
    public $instPostEmailAddresses;

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
            'userId'                       => \yii::t('app', 'Related user ID'),
            'lang'                         => \yii::t('app', 'Language of the information'),
            'phoneHome'                    => \yii::t('app', 'Home phone number'),
            'phoneMobile'                  => \yii::t('app', 'Mobile phone number'),
            'skype'                        => \yii::t('app', 'Skype'),
            'ACMNumber'                    => \yii::t('app', 'ACM number if you have'),
            'instName'                     => \yii::t('app', 'Institution name'),
            'instNameShort'                => \yii::t('app', 'Short name of the institution name'),
            'instDivision'                 => \yii::t('app', 'Division'),
            'instPostEmailAddresses'       => \yii::t('app', 'Official post and email addresses')
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
            array('lang, userId, instName, instNameShort, instPostEmailAddresses, phoneMobile, skype', 'required'),
        ));
    }

    /**
     * This returns the name of the collection for this class
     *
     * @return string
     */
    public function getCollectionName()
    {
        return 'user.info';
    }

    /**
     * List of collection indexes
     *
     * @return array
     */
    public function indexes()
    {
        return array_merge(parent::indexes(), array(
            'userId_lang' => array(
                'key' => array(
                    'userId'    => \EMongoCriteria::SORT_ASC,
                    'lang'      => \EMongoCriteria::SORT_ASC,
                ),
                'unique' => true,
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

        // Convert MongoId to string
        $this->userId = (string)$this->userId;

        return true;
    }

}
