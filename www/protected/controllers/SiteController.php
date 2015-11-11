<?php

class SiteController extends Controller
{
//	public $layout='column1';
    public $layout = 'standart';

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
        $min_buy_order = Order::model()->find(
            array(
                'condition' => 'dst_wallet_type IN ('. join(', ', Wallet::$WALLET_CURRENCY_BTC) .') AND status=0 ',
                "order" => 'price asc',
                'limit' => 1
            )
        );
        $max_buy_order = Order::model()->find(
            array(
                'condition' => 'dst_wallet_type IN ('. join(', ', Wallet::$WALLET_CURRENCY_BTC) .') AND status=0 ',
                "order" => 'price desc',
                'limit' => 1
            )
        );
        $min_sell_order = Order::model()->find(
            array(
                'condition' => 'dst_wallet_type IN ('. join(', ', Wallet::$WALLET_CURRENCY_USD) .') AND status=0 ',
                "order" => 'price asc',
                'limit' => 1
            )
        );
        $max_sell_order = Order::model()->find(
            array(
                'condition' => 'dst_wallet_type IN ('. join(', ', Wallet::$WALLET_CURRENCY_USD) .') AND status=0',
                "order" => 'price desc',
                'limit' => 1
            )
        );
        /**
         * @var Order[] $active_orders
         */
        $active_orders = Order::model()->findAll(array(
            'condition' => 'status=0',
//            "order" => 'summ asc',
//            'limit' => 1
        ));
        $orders = array(
            'buy'   =>array(),
            'sell'  =>array(),
            'total' => array(
                'sell' => 0,
                'buy' => 0,
            )
        );
        foreach ($active_orders as $order) {
            if ($order->isBTCSell()) {
                $orders['sell'][] = $order;
                $orders['total']['sell'] += $order->rest;
            } else {
                $orders['buy'][] = $order;
                $orders['total']['buy'] += $order->rest;
            }
//            $tIndex = date('m/d/Y H:i', $order->date);
//            $transactionGroups[$tIndex][]=$order;
        }

        $transactions = Transaction::model()->findAll(array(
//            'condition' => 'status=0',
            "order" => 'date desc',
            'limit' => 500
        ));

        $transactionGroups = Transaction::prepareForPlot(array_reverse($transactions));

        $userOrders = array();
        if (Yii::app()->user->isGuest) {
            $user = null;
        } else {
            $uid = Yii::app()->user->id;
            $user = User::model();
            $user = $user->findByPk($uid);
            $userOrders = Order::model()->findAll("user={$uid} AND status = " . Order::STATUS_NEW);
            $wallets = Wallet::model()->findAllByAttributes(array('user_id'=>$user->id));
            foreach ($wallets as $wallet) {
                if ( in_array($wallet->type , Wallet::$WALLET_CURRENCY_USD)) {
                    $user_wallets['USD'] = $wallet;
                }
                if ( in_array($wallet->type , Wallet::$WALLET_CURRENCY_BTC)) {
                    $user_wallets['BTC'] = $wallet;
                }
            }
        }

        $this->render('index', array(
            'user'                  => $user,
            'wallets'               => $user_wallets,
            'min_buy_order'         => $min_buy_order,
            'max_buy_order'         => $max_buy_order,
            'min_sell_order'        => $min_sell_order,
            'max_sell_order'        => $max_sell_order,
            'last_order'            => $last_order,
            'orders'                => $orders,
            'transactions'          => $transactions,
            'transactionGroups'     => $transactionGroups,
            'userOrders'            => $userOrders
            )
        );

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
            Yii::app()->controller->end();
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
