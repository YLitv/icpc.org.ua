<?php

namespace web\controllers;

use \common\models\User;

class AuthController extends \web\ext\Controller
{

    /**
     * Check recaptcha code
     *
     * @return bool|string
     */
	protected function _checkRecaptcha()
	{
        $challengeField = $this->request->getParam('recaptcha_challenge_field');
        $responseField  = $this->request->getParam('recaptcha_response_field');
        $errorMessage   = \yii::t('app', 'The recaptcha code is incorrect.');

        if (($responseField === null) || ($challengeField === null)) {
            return $errorMessage;
        }

        \yii::import('common.lib.recaptcha.reCAPTCHA.recaptchalib', true);
	    $response = recaptcha_check_answer(
            \yii::app()->params['recaptcha']['privateKey'],
            \yii::app()->request->userHostAddress,
            $challengeField,
            $responseField
        );
		if ($response->is_valid) {
            return true;
        } else {
			return $errorMessage;
		}
	}

    /**
     * Init
     */
    public function init()
    {
        parent::init();

        // Set default action
        $this->defaultAction = 'login';

        // Set active main menu item
        $this->setNavActiveItem('main', '');
    }

    /**
     * Before action
     *
     * @param \CAction $action
     * @return bool
     */
    protected function beforeAction($action)
    {
        if (!parent::beforeAction($action)) return false;

        // Take away loggedin users
        if ((!\yii::app()->user->isGuest) && ($this->action->id !== 'logout')) {
            return $this->redirect('/');
        }

        return true;
    }

    /**
     * Login page
     */
    public function actionLogin()
    {
        // Get params
        $email      = $this->request->getPost('email');
        $password   = $this->request->getPost('password');

        // Login
        $error = '';
        if ($this->request->isPostRequest) {
            $identity = new \web\ext\UserIdentity($email, $password);
            if ($identity->authenticate()) {
                \yii::app()->user->allowAutoLogin = true;
                \yii::app()->user->login($identity);
                return $this->redirect('/');
            } else {
                $error = $identity->errorMessage;
            }
        }

        // Render view
        $this->render('login', array(
            'email' => $email,
            'error' => $error,
        ));
    }

    /**
     * Logout
     */
    public function actionLogout()
    {
        \yii::app()->user->logout();
        return $this->redirect('/');
    }

    /**
     * Signup page
     */
    public function actionSignup()
    {
        // Get params
        $firstName      = $this->request->getPost('firstName');
        $lastName       = $this->request->getPost('lastName');
        $email          = $this->request->getPost('email');
        $password       = $this->request->getPost('password');
        $passwordRepeat = $this->request->getPost('passwordRepeat');
        $role           = $this->request->getPost('role');
        $rulesAgree     = (bool)$this->request->getPost('rulesAgree');

        // Register a new user
        if ($this->request->isPostRequest) {

            // Validate user date
            $user = new User();
            $user->setAttributes(array(
                'firstName'  => $firstName,
                'lastName'   => $lastName,
                'email'      => $email,
                'role'       => $role,
            ), false);
            $user->validate();
            $user->setPassword($password, $passwordRepeat);

            // Check rules to be accepted
            if (!$rulesAgree) {
                $user->addError('rulesAgree', \yii::t('app', 'Please, read and accept service rules'));
            }

            // Check recaptcha in the very end
            if (!$user->hasErrors()) {
                $recaptchaStatus = $this->_checkRecaptcha();
                if ($recaptchaStatus !== true) {
                    $user->addError('recaptcha', $recaptchaStatus);
                }
            }

            // If no errors, than create and auth user
            if (!$user->hasErrors()) {
                $user->save();
                $identity = new \web\ext\UserIdentity($email, $password);
                $identity->authenticate();
                \yii::app()->user->allowAutoLogin = true;
                \yii::app()->user->login($identity);
            }

            // Render json
            $this->renderJson(array(
                'errors' => $user->hasErrors() ? $user->getErrors() : false,
            ));
        }

        // Render view
        else {
            $this->render('signup', array(
                'firstName'         => $firstName,
                'lastName'          => $lastName,
                'email'             => $email,
                'password'          => $password,
                'passwordRepeat'    => $passwordRepeat,
                'rulesAgree'        => $rulesAgree,
            ));
        }
    }

}