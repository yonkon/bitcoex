<?php

class OrderController extends Controller
{
    public function actionCreate()
    {
        if (Yii::app()->user->isGuest) {
            return false;
        }
        $order = new Order;
        $_REQUEST['user'] = Yii::app()->user->id;
        $_REQUEST['rest'] = $_REQUEST['summ'];
        $_REQUEST['date'] = time();
        $_REQUEST['status'] = Order::STATUS_NEW;
        $order->setAttributes($_REQUEST);
        if ($order->validate(null, false) && $order->save()) {

            if ($order->processCurrentBids()) {
                echo json_encode(array(
                    'status' => 'OK'
                ));
                die();
            }
        }

        echo json_encode(array(
            'status' => 'error',
            'errors' => array(
                'order' => $order->getErrors()
            )
        ));
        die();

        $this->renderText(json_encode(array(
            'errors' => array(
                'order' => $order->getErrors()
            )
        )));
    }

    public function actionList()
    {
        $this->render('list');
    }

    public function actionUpdate()
    {
        $this->render('update');
    }

    public function actionView()
    {
        $this->render('view');
    }

    /**
     * @param integer $id Order ID
     * @throws Exception Throws if order could not find
     */
    public function actionCancel($id)
    {
      /**
      * @var Order $order Found order
      * @var CWebApplication $app Yii instance
      */
      $app = Yii::app();
        if($app->user->isGuest) {
            $this->redirect($app->createUrl('user/login'));
        }
        $uid = Yii::app()->user->id;
        $status = 'OK';
        $errors = array();
        if (empty($id)) {
            $status = 'error';
            $errorMsg = Yii::t('error', 'Undefined order id');
            if(empty($_REQUEST['ajax'])) {
                throw new InvalidArgumentException($errorMsg);
            } else {
                $errors['order'][] = array('order', $errorMsg);
            }
        }
        $order = Order::model()->find("id = {$id} AND user = $uid");
        if (!$order || empty($order)) {
            $status = 'error';
            $errorMsg = Yii::t('error', 'Could not find user order');
            if(empty($_REQUEST['ajax'])) {
                throw new Exception($errorMsg);
            } else {
                $errors['order'][] = array('order', $errorMsg);
            }
        }
//        $order->status = Order::STATUS_CANCELED;
        if (!$order->cancel()) {
          $status = 'error';
          $errors['order'] = array_merge($errors['order'], $order->getErrors());
        }
        echo json_encode(array(
          'status' => $status,
          'errors' => $errors
        ));
        die();
    }

    public function actionGetChanges() {
      if (empty($_REQUEST['ajax'])) {
        $this->redirect(Yii::app()->createUrl('site/index'));
      }
      if (empty($_REQUEST['date'])) {
        $date = 0;
      } else {
        $date = $_REQUEST['date'];
      }
      $recent_orders = Order::getModifiedAfter($date);
      $lastModified = $recent_orders[0]->modified;
      $result = array('min' => array(
          'buy' => 0,
          'sell' => 0
        ),
        'max' => array(
          'buy' => 0,
          'sell' => 0
        )
      );
      foreach($recent_orders as $order) {
        /**
         * @var Order $order
         */
        $typeTranslateString = 'Buy';
        $typeIndex = 'buy';
        if ($order->isBTCBuy()) {
          $typeTranslateString = 'Buy';
          $typeIndex = 'buy';
        }
        if ($order->isBTCSell()) {
          $typeIndex = 'sell';
          $typeTranslateString = 'Sell';
        }
        $orderData = array(
          'id' => $order->id,
          'date' => $order->date,
          'modified' => $order->modified,
          'type' => Yii::t('order', $typeTranslateString),
          'price' => $order->price,
          'summ' => $order->summ,
          'rest' => $order->rest,
          'btc' => $order->restCryptoEquivalent(),
          'usd' => $order->restCurrencyEquivalent(),
          'status' => $order->status,
        );

        $result[$typeIndex][] = $orderData;
        if ($result['min'][$typeIndex] == 0 || $result['min'][$typeIndex] > $orderData['price']) {
          $result['min'][$typeIndex] = $orderData['price'];
        }
        if ($result['max'][$typeIndex] == 0 || $result['max'][$typeIndex] < $orderData['price']) {
          $result['max'][$typeIndex] = $orderData['price'];
        }
        if ($order->user == Yii::app()->user->id) {
          $result['my'][$typeIndex][] = $orderData;
        }
      }
      $recent_history = Transaction::model()->findAll("date >= {$date} ORDER BY date DESC");
      /**
       * @var Transaction $history
       */
      foreach($recent_history as $history){
        $historyData = array(
          'id' => $history->id,
          'date' => $history->date,
          '_type' => ($history->isBTCSell())? 'sell' : 'buy',
          'type' => ($history->isBTCSell())? Yii::t('order', 'Sell') : Yii::t('order', 'Buy'),
          'price' => $history->src_price,
          'btc' => $history->srcBTCEquivalent(),
          'usd' => $history->srcUSDEquivalent(),
        );
        $result['history'][] = $historyData;
      }
      $resultWallets = array();
      if (!Yii::app()->user->isGuest) {
        $uid = Yii::app()->user->id;
        $wallets = Wallet::model()->findAll("user_id = $uid");
        foreach($wallets as $wallet) {
          if ($wallet->type == Wallet::WALLET_TYPE_WMZ) {
            $resultWallets['usd']['money'] = $wallet->money;
            $resultWallets['usd']['available'] = $wallet->available;
          }
          if ($wallet->type == Wallet::WALLET_TYPE_BTC) {
            $resultWallets['btc']['money'] = $wallet->money;
            $resultWallets['btc']['available'] = $wallet->available;
          }
        }
      }

      echo json_encode(array(
        'status' => 'OK',
        'orders' => $result,
        'wallets' => $resultWallets,
        'last_modified' => $lastModified
      ));
      die();
    }

    // Uncomment the following methods and override them if needed
    /*
    public function filters()
    {
        // return the filter configuration for this controller, e.g.:
        return array(
            'inlineFilterName',
            array(
                'class'=>'path.to.FilterClass',
                'propertyName'=>'propertyValue',
            ),
        );
    }

    public function actions()
    {
        // return external action classes, e.g.:
        return array(
            'action1'=>'path.to.ActionClass',
            'action2'=>array(
                'class'=>'path.to.AnotherActionClass',
                'propertyName'=>'propertyValue',
            ),
        );
    }
    */
}
