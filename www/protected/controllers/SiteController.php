<?php

class SiteController extends Controller
{
//	public $layout='column1';
    public $layout = false;

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function actionIndex()
    {
        $user = null;
        $user_wallets = array();
        $last_order = Order::model()->find(
            array(
                'limit'=>1,
                'order' => 'id desc'
            )
        );
        $min_order = Order::model()->find(
            array(
                'condition' => 'status=0',
                "order" => 'summ asc',
                'limit' => 1
            )
        );
        $max_order = Order::model()->find(
            array(
                'condition' => 'status=0',
                "order" => 'summ desc',
                'limit' => 1
            )
        );

        if (Yii::app()->user->isGuest) {
            $user = null;
        } else {
            $user = User::model();
            $user->findByPk(Yii::app()->user->id);
            $wallets = Wallet::model()->findAllByAttributes(array('user'=>$user->getId()));
            foreach ($wallets as $wallet) {
                $user_wallets[$wallet->type] = $wallet;
            }
        }

        $this->render('index', array(
            'user'=>$user,
            'wallets' => $user_wallets,
            'min_order' => $min_order,
            'max_order' => $max_order,
            'last_order' => $last_order));

    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $headers = "From: {$model->email}\r\nReply-To: {$model->email}";
                mail(Yii::app()->params['adminEmail'], $model->subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        if (!defined('CRYPT_BLOWFISH') || !CRYPT_BLOWFISH)
            throw new CHttpException(500, "This application requires that PHP was compiled with Blowfish support for crypt().");

        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
}
