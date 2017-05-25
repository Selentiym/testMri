
<?php
/**
 * @type Controller $this
 */
$this -> renderPartial('//navBar',array('user' => User::model() -> findByPk(Yii::app() -> user -> getId())));

	$this -> renderPartial('//_datepicker',['get' => $_GET,"url" => Yii::app() -> baseUrl . '/data/%startTime%/%endTime%']);
	$this -> renderPartial('//_form_file_upload');
	$this -> renderPartial('//_form_file_upload_client');
	//$this -> renderPartial('//users/_doctor_import');
	$this -> renderPartial('//_telephone_numbers_list');
	$this -> renderPartial('//_object_list',array(
		'htmlOptions' => array('id' => 'addressList'),
		'label' => 'Список адресов/клиник, где работают партнеры',
		'criteria' => new CDbCriteria,
		'display' => function($object){ return $object -> address; },
		'modelName' => 'UserAddress'
	));
	$this -> renderPartial('//_object_list',array(
		'htmlOptions' => array('id' => 'TestAddressList'),
		'label' => 'Список клиник, где делают анализы',
		'criteria' => new CDbCriteria,
		'display' => function($object){ return $object -> name; },
		'modelName' => 'TestAddress'
	));
	$this -> renderPartial('//_mentor_list');
	$this -> renderPartial('//data/_googleDocOptions');
	$this -> renderPartial('//data/_uploadPrices');
	$this -> renderPartial('//data/_termsCsv');
//	$f = new CustomFlash();
//	$f -> showFlashes();
?>