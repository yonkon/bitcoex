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
        $order->status = Order::STATUS_CANCELED;
        if (!$order->cancel()) {
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
