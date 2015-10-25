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
        if ($order->validate() && $order->save()) {

            $order->processCurrentBids(); //TODO make money available
        }

		$this->render('create', array(
			'errors' => array(
                'order' => $order->getErrors()
            )
		));
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
