<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Bitcoex',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.vendor.ETwigViewRenderer',
	),
	'modules' => array (
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'root'
        )
    ),

	'defaultController'=>'post',

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'db'=>array(
			'connectionString' => 'sqlite:protected/data/blog.db',
			'tablePrefix' => 'tbl_',
		),
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=blog',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'tablePrefix' => 'tbl_',
		),
		*/
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName' => false,
			'rules'=>array(
				'post/<id:\d+>/<title:.*?>'=>'post/view',
				'posts/<tag:.*?>'=>'post/index',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
        /*'viewRenderer'=>array(
            'class'=>'application.vendor.Smarty.ESmartyViewRenderer',
            'fileExtension' => '.tpl',
            //'pluginsDir' => 'application.smartyPlugins',
            //'configDir' => 'application.smartyConfig',
            //'prefilters' => array(array('MyClass','filterMethod')),
            //'postfilters' => array(),
            //'config'=>array(
            //    'force_compile' => YII_DEBUG,
            //   ... any Smarty object parameter
            //)
        ),*/
		'viewRenderer' => array(
			'class' => 'application.vendor.ETwigViewRenderer',

			// All parameters below are optional, change them to your needs
			'fileExtension' => '.twig',
			'options' => array(
				'autoescape' => true,
			),
			/*'extensions' => array(
				'My_Twig_Extension',
			),*/
			'globals' => array(
				'html' => 'CHtml'
			),
			'functions' => array(
				'rot13' => 'str_rot13',
			),
			'filters' => array(
				'jencode' => 'CJSON::encode',
			),
//            'enabled' => true,
			// Change template syntax to Smarty-like (not recommended)
			/*'lexerOptions' => array(
				'tag_comment'  => array('{*', '*}'),
				'tag_block'    => array('{', '}'),
				'tag_variable' => array('{$', '}')
			),*/
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);
