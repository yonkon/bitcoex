<?php

class LoginController extends Controller
{
	public $defaultAction = 'login';

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if (Yii::app()->user->isGuest) {
			$model=new UserLogin;
			// collect user input data
			if(isset($_POST['UserLogin']))
			{
				$model->attributes=$_POST['UserLogin'];
				// validate user input and redirect to previous page if valid
				if($model->validate()) {
					$this->lastViset();
					if (Yii::app()->user->returnUrl=='/index.php')
						$this->redirect(Yii::app()->controller->module->returnUrl);
					else
						$this->redirect(Yii::app()->user->returnUrl);
				}
			}
			// display the login form
			$form = (new CForm(array(
				'elements'=>array(
					'username'=>array(
						'type'=>'text',
						'maxlength'=>32,
					),
					'password'=>array(
						'type'=>'password',
						'maxlength'=>32,
					),
					'rememberMe'=>array(
						'type'=>'checkbox',
					)
				),

				'buttons'=>array(
					'login'=>array(
						'type'=>'submit',
						'label'=>'Login',
					),
				),
			), $model));
			$this->render('/user/login',array('model'=>$model, 'form' => $form));
		} else
			$this->redirect(Yii::app()->controller->module->returnUrl);
	}
	
	private function lastViset() {
		$lastVisit = User::model()->notsafe()->findByPk(Yii::app()->user->id);
		$lastVisit->lastvisit = time();
		$lastVisit->save();
	}

}
