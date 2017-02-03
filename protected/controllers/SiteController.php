<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/site.php';
	public $defaultAction = 'login';
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
			'reviewstat'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//review/review_stat'
			),
			'phonestat'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//phone_stat'
			),
			
			'allstat'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//adminStat'
			),
			'paystat'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//paystat'
			),
			'userlist'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//users/_info_all_users'
			),
			'activeuserlist'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//users/_userlistActive'
			),
			'addData'=>array(
					'class'=>'application.controllers.site.FileViewAction',
					'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
					'view' => '//addToDataBase'
			),
			'entries'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//entries'
			),
			'errors'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//errors'
			),
			'chess'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//chess'
			),
			'userSmsForm'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//users/sendSms'
			),
			'userCollection'=>array(
				'class'=>'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
				'view' => '//users/collection'
			),
			
			'cabinet' => array(
				'class' => 'application.controllers.site.ModelViewAction',
				'modelClass' => 'User',
				'view' => '//LK'
			),
			'patients' => array(
				'class' => 'application.controllers.site.ModelViewAction',
				'modelClass' => 'User',
				'view' => '//patients'
			),
			'printDirections' => array(
				'class' => 'application.controllers.site.ModelViewAction',
				'modelClass' => 'User',
				'view' => '//print/print',
				'partial' => true
			),
			'stat' => array(
				'class' => 'application.controllers.site.ModelViewAction',
				'modelClass' => 'User',
				'view' => '//stat'
			),
			'allCalls' => array(
				'class' => 'application.controllers.site.ModelViewAction',
				'modelClass' => 'User',
				'view' => '//allCalls'
			),
			'lineStatByFactors' => array(
				'class' => 'application.controllers.site.ModelViewAction',
				'modelClass' => 'User',
				'view' => '//stat/lineStatByFactors'
			),
			'showReviews' => array(
				'class' => 'application.controllers.site.ModelViewAction',
				'modelClass' => 'User',
				'view' => '//review/list'
			),
			
			'userSendSms' => array(
				'class' => 'application.controllers.site.ModelCollectionAction',
				'modelClass' => 'User',
				'args' => $_POST["userGroup"],
				'action' => 'sendSms',
				'addArgs' => $_POST["smsText"],
				'redirect' => Yii::app() -> baseUrl.'/activeuserlist'
			),
			
			'createUser' => array(
				'class' => 'application.controllers.site.ModelCreateAction',
				'modelClass' => 'User',
				'view' => '//createUser',
				'scenario' => 'create'
			),
			'UserAddressCreate' => array(
				'class' => 'application.controllers.site.ModelCreateAction',
				'modelClass' => 'UserAddress',
				'view' => '//UserAddress/create',
				'scenario' => 'create'
			),
			'TestAddressCreate' => array(
				'class' => 'application.controllers.site.ModelCreateAction',
				'modelClass' => 'TestAddress',
				'view' => '//TestAddress/create',
				'scenario' => 'create'
			),
			'PhoneCreate' => array(
				'class' => 'application.controllers.site.ModelCreateAction',
				'modelClass' => 'UserPhone',
				'view' => '//PhoneCreate',
				'scenario' => 'create'
			),
			'ReviewCreate' => array(
				'class' => 'application.controllers.site.ModelCreateAction',
				'modelClass' => 'Review',
				'view' => '//review/review_create',
				'scenario' => 'create'
			),
			'MentorCreate' => array(
				'class' => 'application.controllers.site.ModelCreateAction',
				'modelClass' => 'UserMentor',
				'view' => '//mentor/create_mentor'
			),
			'PatientCreate' => array(
				'class' => 'application.controllers.site.ModelCreateAction',
				'modelClass' => 'Patient',
				'view' => '//patient/create_patient'
			),
			
			
			
			'updateUser' => array(
				'class' => 'application.controllers.site.ModelUpdateAction',
				'modelClass' => 'User',
				'view' => '//UpdateUser',
				'scenario' => 'updateByAdmins'
			),
			'settings' => array(
				'class' => 'application.controllers.site.ModelUpdateAction',
				'modelClass' => 'Setting',
				//'redirect' => '/data',
				'view' => '//_form_settings'
			),
			'UserAddressUpdate' => array(
				'class' => 'application.controllers.site.ModelUpdateAction',
				'modelClass' => 'UserAddress',
				'view' => '//UserAddress/update',
				'scenario' => 'update'
			),
			'TestAddressUpdate' => array(
				'class' => 'application.controllers.site.ModelUpdateAction',
				'modelClass' => 'TestAddress',
				'view' => '//TestAddress/update',
				'scenario' => 'update'
			),
			'updateSelf' => array(
				'class' => 'application.controllers.site.ModelUpdateAction',
				'modelClass' => 'User',
				'view' => '//SelfUpdate',
				'scenario' => 'SelfUpdate',
				'redirectUrl' => Yii::app() -> baseUrl . '/cabinet'
			),
			'PhoneUpdate' => array(
				'class' => 'application.controllers.site.ModelUpdateAction',
				'modelClass' => 'UserPhone',
				'view' => '//PhoneUpdate',
				'scenario' => 'update'
			),
			'ReviewUpdate' => array(
				'class' => 'application.controllers.site.ModelUpdateAction',
				'modelClass' => 'Review',
				'view' => '//review/_form_review',
				'scenario' => 'update'
			),
			'MentorUpdate' => array(
				'class' => 'application.controllers.site.ModelUpdateAction',
				'modelClass' => 'UserMentor',
				'view' => '//mentor/update_mentor'
			),
			
			
			'PhoneDelete' => array(
				'class' => 'application.controllers.site.ModelDeleteAction',
				'modelClass' => 'UserPhone'
			),
			'UserDelete' => array(
				'class' => 'application.controllers.site.ModelDeleteAction',
				'modelClass' => 'User'
			),
			'UserAddressDelete' => array(
				'class' => 'application.controllers.site.ModelDeleteAction',
				'modelClass' => 'UserAddress'
			),
			'TestAddressDelete' => array(
				'class' => 'application.controllers.site.ModelDeleteAction',
				'modelClass' => 'TestAddress'
			),
			'ReviewDelete' => array(
				'class' => 'application.controllers.site.ModelDeleteAction',
				'modelClass' => 'Review'
			),
			'CallDelete' => array(
				'class' => 'application.controllers.site.ModelDeleteAction',
				'modelClass' => Setting::getCallClass(),
				'returnUrl' => Yii::app() -> baseUrl.'/errors'
			),
			'MentorDelete' => array(
				'class' => 'application.controllers.site.ModelDeleteAction',
				'modelClass' => 'UserMentor'
			),
			'info' => array(
				'class' => 'application.controllers.site.ModelViewAction',
				'modelClass' => 'User',
				'view' => '//infoPage'
			),
			
			
			'statUpload' => array(
				'class' => 'application.controllers.site.FileUploadAction',
				'returnUrl' => array('site/settings'),
				'report' => true,
				'serverName' => Yii::app() -> basePath . '/../images/stat.jpg',
				'formFileName' => 'statImageUpload',
				'validate' => function ($file) {
					//print_r($file);
					return true;
					//return $file
				},
				'checkAccess' => function () {
					return Yii::app() -> user -> checkAccess('admin');
				}
			),
			'clientphonesupload' => array(
				'class' => 'application.controllers.site.FileUploadAction',
				'returnUrl' => array('site/data'),
				'report' => function ($name) {
					return Data::model() -> ImportNumberFile($name);
				},
				'serverName' => Yii::app() -> basePath . '/../files/inputClient.csv',
				'formFileName' => 'ClientPhoneUpload',
				'checkAccess' => function () {
					return Yii::app() -> user -> checkAccess('admin');
				}
			),
			'doctorsImport' => array(
				'class' => 'application.controllers.site.FileUploadAction',
				'returnUrl' => array('site/cabinet'),
				'report' => function ($name) {
					return DataFromCsvFile::model() -> ImportDoctors($name);
				},
				'serverName' => Yii::app() -> basePath . '/../files/doctors.csv',
				'formFileName' => 'DoctorsFile',
				'checkAccess' => function () {
					return Yii::app() -> user -> checkAccess('admin');
				}
			),
			'deleteGroup'=>array(
				'class'=>'application.controllers.site.ModelGroupAction',
				'action' => function($id){
					$call = Setting::getCallModel() -> findByPk($id);
					$call -> delete();
				},
				'args' => $_GET["group"],
				'returnUrl' => Yii::app() -> baseUrl.'/errors'
			),
		);
	}
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;
		
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='loginForm')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		if (!Yii::app() -> user -> isGuest) {
			$this -> redirect('site/cabinet');
		}
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->baseUrl.'/cabinet');
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}
	//public function actionAssignCall($call_id,$user_id){
	public function actionAssignCall(){
		if (Yii::app() -> user -> checkAccess('admin')) {
			$call = Setting::getCallModel() -> findByPk($_POST['id_call']);
			//$call = Setting::getCallModel() -> findByPk($call_id);
			if (($call)&&(!$call -> id_user)) {
				if (!$_POST['id_user']) {
					$_POST['id_user'] = array();
				}
				//Если явно указан пользователь, которому присвоить запись, то делаем это
				if ((int)current($_POST['id_user'])) {
					$call -> id_user = (int)current($_POST['id_user']);
				} else {
					//Иначе считываем адрес и пытаемся найти владельца.
					if ($_POST['id_address']) {
						
						//print_r($_POST['id_address']);
						$id_a = current($_POST['id_address']);
						$call_object = new Call();
						//echo "adr_given<br/>";
						
						
						//echo "call_created<br/>";
						$call_object -> mangoTalker = $call -> mangoTalker;
						$call_object -> lookForIAttribute();
						$call_object -> j = $call -> j;
						$call_object -> id_address = $id_a;
						
						if ($owner = $call_object -> giveOwner()) {
							$call -> id_user = $owner -> id;
							//echo "owner_found";
							//var_dump($owner);
						} else {
							//echo "owner_not_found";
							new CustomFlash('error',Setting::getCallClass(),'UserNotFound','Присвоение пользователя не успешно',true);
						}
					}
				}
				if (($_POST['id_user'])||($_POST['id_address'])) {
					if ($call -> id_user) {
						if ($call -> save()) {
						//if (false) {
							new CustomFlash('success',Setting::getCallClass(),'UserAssignSucc','Пользователь успешно присвоен',true);
						} else {
							new CustomFlash('error',Setting::getCallClass(),'SaveError','Ошибка при сохранении результата',true);
						}
					}
				} else {
					new CustomFlash('error',Setting::getCallClass(),'EmptyInput','Задайте параметры',true);
				}
			} else {
				new CustomFlash('error',Setting::getCallClass(),'UserAssignErr','Неуспешное присвоение пользователя: не найдена запись или у нее уже выбран пользователь',true);
			}
			//echo "123";
			//CustomFlash::ShowFlashes();
			$this -> redirect(Yii::app() -> baseUrl.'/errors');
		} else {
			$this -> renderPartial('//accessDenied');
		}
	}
	public function actionUploadFile(){
		if (Yii::app() -> user -> checkAccess('admin')) {
			//print_r($_FILES);
			if ((isset($_FILES['inputFile']))&&(isset($_POST["year"]))){
				if ($_POST["year"] > 2013) {
					$set = Setting::model() -> find();
					$set -> year = $_POST["year"];
					@$set -> save();
					$name = Yii::app() -> basePath.'/../files/temp_file.csv';
					if (move_uploaded_file($_FILES['inputFile']['tmp_name'],$name)) {
						if (Data::model() -> ImportDataToDatabase($name)) {
							new CustomFlash('success','Data','FileOpened','Файл успешно загружен на сервер и обработан.',true);
						} else {
							new CustomFlash('error','Data','FileDidNotOpen','Ошибка при загрузке файла.',true);
						}
					} else {
						new CustomFlash('error','Data','FileDidNotOpen','Ошибка при загрузке файла.',true);
					}
				} else {
					new CustomFlash('error','Data','IncorrectYear','Год должен быть выше 2013',true);
				}
			} else {
				if ((isset($_POST))||(isset($_GET))) {
					new CustomFlash('error','Data','NotEnoughData','Заполнены не все поля',true);
				}
			}
			$this -> redirect(Yii::app() -> baseUrl.'/data');
			//$this -> controller -> redirect('//site/cabinet');
		} else {
			$this -> render('//accessDenied');
		}
	}
	public function actionSendSms(){
		require_once(Yii::getPathOfAlias('webroot.vendor').'\autoload.php');
		//require_once(Yii::getPathOfAlias('webroot.vendor.zelenin.smsru.Api').'.php');
		//$client = new \Zelenin\SmsRu\Api(new \Zelenin\SmsRu\Auth\ApiIdAuth('1fb53119-4130-5524-4950-c8c74a627908'));
		$client = Yii::app() -> sms -> giveClient();
		var_dump($client);
		//var_dump($client -> myBalance());
		//$sms = new \Zelenin\SmsRu\Entity\Sms('89516727222', 'Смс с сайта через класс Api. Дошла ли?');
		//$sms = new \Zelenin\SmsRu\Entity\Sms('89523660187', 'Смс с сайта через класс Api. Дошла ли?');
		//var_dump($sms);
		//$smsResp = $client->smsSend($sms);
		//$smsId = current($smsResp -> ids);
		//var_dump($client -> smsStatus('201552-100000436'));
		//echo $smsId;
		$bal = $client -> myBalance();
		echo $bal -> balance;
		if (isset($_POST["submitted"])) {
			
		}
		//$this -> renderPartial('//sms/form');
	}
	/*public function actiongiveImage($addr){
		
	}*/
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	public function actionTransportData(){
		$users = User::model() -> findAll();
		foreach ($users as $user) {
			$user -> inp_jMin = array($user -> jMin, $user -> jMin_add);
			$user -> inp_jMax = array($user -> jMax, $user -> jMax_add);
			$user -> save();
		}
		
		echo count($users);
	}
	public function actionCheckDebug(){
		echo "check sync2!";
	}
	public function actionLoadDataByTime(){
		$t = microtime(true);
		$factory  = Yii::app() -> getModule('googleDoc') -> getFactory();
		/**
		 * @type aGDCallFactory $factory
		 */
		if ($_GET["time"] < 10000) {
			$time = time();
		} else {
			$time = $_GET["time"];
		}
		echo "Time, for which the download took place $time <br/>";
		$entries = $factory -> scanGoogle([],$time);
		foreach ($entries as $e) {
			$call = $factory -> buildByEntry($e);
			if (!$call -> save()) {
				//$err = $call -> getErrors();
			}
		}
		echo "time:".(microtime(true) - $t)."<br/>";
	}
	public function actionCheck () {
		var_dump($_GET);
		//$was = GDCallFactorable::model() -> findByPk(10609);
		/*$mod = Yii::app() -> getModule('googleDoc');
		$check = new GDCallFactorable();
		$check -> id = 10609;
		$check -> research_type = 'check!';
		$check -> setIsNewRecord(false);
		$check -> save(['research_type']);*/


		//$this -> render('//stat/factorStat');



		/*$api = GoogleDocApiHelper::getLastInstance();

		$call = StatCall::model() -> findByPk(5222);
		echo $call -> external_id;
		$entry = $call -> findGDByLink();
		var_dump($entry);//*/

		//var_dump(\Google\Spreadsheet\ListEntry::getEntryByUrl('https://spreadsheets.google.com/feeds/list/1CN1K4fG2nsrUlj5GOEfs4ncPU5gUT0pXNjuryQDJNFk/od6/private/full/cokwr'));
		//https://spreadsheets.google.com/feeds/list/1CN1K4fG2nsrUlj5GOEfs4ncPU5gUT0pXNjuryQDJNFk/od6/private/full/cokwr
		/*$api -> setWorkArea('check', date("F o",time()));
		$data = $api->giveData();
		var_dump($data -> getEntries());//*/
		/*if( $curl = curl_init() ) {
			$params = [
					'dateFrom' => time() - 60*60*24*5,
					'dateTo' => time(),
					'key' => OmriPss::pss(),
					'city' => 1
			];
			$url = "http://o.mrimaster.ru/api/forms?".http_build_query($params);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$out = curl_exec($curl);
			$calls = json_decode($out);
			foreach ($calls as $call) {
				$stCall = new StatCall();
				$stCall -> type = 'form';
				$stCall -> date = new CDbExpression('FROM_UNIXTIME(\''.strtotime($call -> dateCreated).'\')');
				$stCall -> report = $call -> description;
				$stCall -> fio = $call -> name;
				$stCall -> number = $call -> phone;
				$stCall -> i = $call -> pid;
				$criteria = new CDbCriteria();
				$criteria -> compare('i', $stCall -> i);
				$criteria -> compare('number', $stCall -> number);
				$criteria -> compare('report', $stCall -> report);
				$criteria -> addCondition('date = FROM_UNIXTIME('.strtotime($call -> dateCreated).')');
				$rec = StatCall::model() -> find($criteria);
				if (!$rec) {
					if (!$stCall->save()) {
						var_dump($stCall->getErrors());
					}
				}
			}
			curl_close($curl);
		}*/
	}
	/*public function actionDownloadClinicsList(){
		$reader = new CsvReader(Yii::app() -> basePath.'/../files/tests_clinics.csv');
		echo Yii::app() -> basePath.'/files/tests_clinics.csv';
		if ($reader -> file) {
			$reader -> saveHeader();
			$reader -> separator = ';';
			$i = 0;
			while($line = $reader -> line()) {
				$i++;
				$name = $line[0];
				$addr = $line[1];
				if (!($record = TestAddress::model() -> findByAttributes(array('name' => $name)))) {
					$record = new TestAddress;
				}
				$record -> name = $name;
				$record -> address = $addr;
				$record -> save();
			}
			echo "{$i} lines was executed";
		} else {
			echo "File not opened.";
		}
	}*/
	/* Do not uncomment! The following functions are for construction only. They should have been used feom a console but it didn't work out for some reason. */
	/*public function actionPss(){
		echo CPasswordHelper::hashPassword('admin');
	}*/
	// admin <=> $2a$13$70aJ2YIqjkbCFF3l3bS3r.7OVYaF8t.lgsQExJi0k6FGageWMFxfi
	/*public function actionAddRules() {
		//print_r(Yii::app() -> getAuthManager());
		$auth=Yii::app()->authManager;
		$auth -> clearAll();
		
		$isParent = 'return Yii::app() -> user -> getId()==$params["user"] -> id_parent';
		$bizRule = 'return Yii::app()->user->getId()==$params["user"]->id;';
		
		$auth -> createOperation('viewUserCabinet', 'view some user\'s cabinet.');
		$auth -> createOperation('viewChildUserCabinet', 'view your child user\'s cabinet.',$isParent);
		$auth -> createOperation('createDoctor','create an ordinary doctor');
		$auth -> createOperation('updateDoctor','update an ordinary doctor');
		$auth -> createOperation('updateChildDoctor','update an ordinary doctor that is your child',$isParent);
		//Вторым параметром в checkAccess передаем массив array('user' => <UserWhoseCabinetIsBeingDisplayedObject>)
		
		$auth -> createOperation('viewOwnUserCabinet', 'view your own cabinet.', $bizRule);
		$auth -> createOperation('updateOwnUser', 'update user\'s own profile.', $bizRule);
		//MD - main Doctor
		$auth -> createOperation('viewMDCabinet', 'view some MD\'s cabinet.');
		$auth -> createOperation('createMainDoc', 'create a main doctor.');
		$auth -> createOperation('updateMainDoc', 'update a main doctor.');
		
		$admin = $auth -> createRole('admin');
		$doctor = $auth -> createRole('doctor');
		$MD = $auth -> createRole('mainDoc');
		
		$doctor -> addChild('viewOwnUserCabinet');
		$doctor -> addChild('updateOwnUser');
		
		$MD -> addChild('viewChildUserCabinet');
		$MD -> addChild('updateChildDoctor');
		$MD -> addChild('createDoctor');
		
		$admin -> addChild('viewMDCabinet');
		$admin -> addChild('viewUserCabinet');
		$admin -> addChild('updateDoctor');
		$admin -> addChild('createMainDoc');
		
		$MD -> addChild('doctor');
		
		$admin -> addChild('mainDoc');
		
		$this -> AddAdminUser();
		//$auth -> createOperation('viewOwnUserCabinet', 'view your own cabinet.', $bizRule);
	}
 
	public function AddAdminUser() {
		$auth=Yii::app()->authManager;
		$admin = User::model() -> findByAttributes(array('username' => 'admin'));
		$doctor = User::model() -> findByAttributes(array('username' => 'doctor'));
		$mainDoc = User::model() -> findByAttributes(array('username' => 'maindoc'));
		$auth->assign('admin',  $admin -> id);
		$auth->assign('mainDoc',  $mainDoc -> id);
		$auth->assign('doctor',  $doctor -> id);
	}//*/
	

}