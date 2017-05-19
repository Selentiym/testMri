<?php

class StatController extends Controller {
	public $layout='//layouts/site.php';
	// Uncomment the following methods and override them if needed
	/*public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}*/

	public function actions() {
		// return external action classes, e.g.:
		return array(
            'full' => array(
                'class' => 'application.controllers.site.ModelViewAction',
                'modelClass' => 'User',
                'view' => '//stat/full'
            ),
            'showDiff' => array(
                'class' => 'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
                'view' => '//stat/linedDifference'
            ),
            'mangoCalls' => array(
                'class' => 'application.controllers.site.FileViewAction',
				'access' => function () {return Yii::app() -> user -> checkAccess('admin');},
                'view' => '//stat/mangoCalls'
            ),
			'factorStat' => array(
				'class' => 'application.controllers.site.ModelViewAction',
				'modelClass' => 'User',
				'view' => '//stat/factorStat'
			),
			'factorList' => array(
				'class' => 'application.controllers.site.ModelViewAction',
				'modelClass' => 'User',
				'view' => '//stat/factorList'
			),
		);
	}
	public function actionLoadStatistics(){
		$this -> layout = '//layouts/site.php';
		$this -> renderPartial("//navBar");
		echo '<a href="'.Yii::app() -> baseUrl."/stat/full".'">К списку линий</a><br/>';
		if ($_GET["time"] > 24*3600*10) {
			GDCall::importFromGoogleDoc($_GET["time"]);
		} else {
			echo "Ошибка в формате времени.";
		}
	}
	public function actionLoadMango(){
		if( $curl = curl_init() ) {
			$from = $_GET["from"];
			$to = $_GET["to"];
			$params = array_filter([
					'dateFrom' => $from,
					'dateTo' => $to,
					'key' => WebUtils::pss(),
					'city' => 1
			]);

			$url = 'http://new.web-utils.ru/api/calls?'.http_build_query($params);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			$out = curl_exec($curl);
			//echo $out;
			//echo $url;
			$mCalls = json_decode($out);
			if (!empty($mCalls)) {
				foreach ($mCalls as $mCall) {
					//Добавляем только если не найдено
					if (!(mCall::model()->findByPk($mCall->id))) {
						$b = new mCall();
						$b->id = $mCall->id;
						$b->line = $mCall->line;
						$b->fromPhone = $mCall->fromPhone;
						$b->toPhone = $mCall->toPhone;
						$b->direction = $mCall->direction;
						$b->status = $mCall->status;
						$b->duration = $mCall->duration;
						$b->type = $mCall->type;
						$b->date = $mCall->date;
						if ($ph = UserPhone::givePhoneByNumber($b->line)) {
							$b->i = $ph->i;
						}
						if (!$b -> save()) {
							$err = $b -> getErrors();
						}
					}
				}
			} else {
				echo $url;
				echo "No data!";
			}
			curl_close($curl);

			//Вместе с Манго загружаем информацию по формам.
			StatCall::loadFormDataFromOmri($from, $to);

			$this -> redirect(Yii::app() -> createUrl('stat/mangoCalls', ['from' => $from, 'to' => $to]));
		}
	}

	public function actionLoadMangoCallsByDay(){
		if (!($day=$_GET['day'])) {
			$to = time();
		} else {
			$to = strtotime($_GET['day'].' 12:00:00');
		}
		$from = $to - 10;
		self::log("Loading mango calls data from $from to $to which is ".date('c', $from).' and '.date('c', $to));
		mCall::loadDataByApi($from, $to);
	}

	/**
	 * Ежеденевная загрузка новых звонков. Смотрит два дня на всякий случай.
	 */
	public function actionLoadGoogleDocDaily() {
		ob_start();
		$fromTime = time() - 24*68*60;
		GDCall::importFromGoogleDoc($fromTime,true);
		GDCall::importFromGoogleDoc(time(),true);
		$out = ob_get_contents();
		ob_end_clean();

		self::log("LoadGoogleDocDaily".PHP_EOL.$out);
	}
	public function actionRefreshData($from = null,$to = null) {
		StatCall::refreshInPeriod($from, $to);
		$this -> redirect(Yii::app() ->createUrl('stat/full', ["from" => $_GET["from"], "to" => $_GET["to"]]));
	}
	public static function log($str) {
		$handler = fopen('stat.log','a+');
		if ($handler) {
			$str = "log on " . date("j.m.y H:i") . PHP_EOL . $str;
			fwrite($handler, $str);
		}
		fclose($handler);
	}
	public function actionCheck(){
//		$u = new WebUtils('http://web-utils.ru/api/calls');
//		$u -> setParams(['city' => 1]);
//		$u -> setPortionObtainCallback(function($response){
//			return json_decode($response);
//		});
//		$rez = $u -> getData(time() - 86400*2,time());
//		var_dump($rez);

//		$api -> ;
		$mod = Yii::app() -> getModule('landingData');
		$googleMod = Yii::app() -> getModule('googleDoc');
		/**
		 * @type landingDataModule $mod
		 * @type GDCallFactorable $gd
		 */
		$gd = GDCallFactorable::model() -> findByPk(144);
		$t1 = $gd -> fio;
		$t2 = $gd -> mangoTalker;
		$gd -> lookForIAttribute();
		$tCall = $gd -> getTCall('max');
		//$attrs = $tCall -> attributes;
		if (($tCall)&&($gd -> i == $tCall -> getLandingId())&&($gd -> i)) {
			$gd -> id_enter = $tCall -> id_enter;
			$test = $gd -> id_enter;
		}
		//GDCall::importFromGoogleDoc(time() - 86400*40, false);
	}
	public function actionFormAssign(){
		//
		$s = new FormSubmit();
		$s -> fio = $_GET["name"];
		$s -> number = $_GET["phone"];
		$s -> lineNumber = $_GET["pid"];
		$s -> save();
		$s -> refresh();
		$num = preg_replace('/[^\d]/ui','',$s -> number);
		$len = mb_strlen($num,"utf-8");
		if ($len == 7) {

		} elseif ($len == 11) {
			$num = preg_replace('/^8/ui','7', $num);
		}
		$s -> numberFormatted = $num;
		$phone = UserPhone::model() -> findByAttributes(['number' => $s -> lineNumber.'.online']);
		if ($phone instanceof UserPhone) {
			$s->i = $phone->i;
			$client = ClientPhone::model() -> findByAttributes(['mangoTalker' => $s -> numberFormatted]);
			if (!($client instanceof ClientPhone)) {
				$client = new ClientPhone();
				$client -> id_phone = $phone -> id;
				$client -> mangoTalker = $s -> numberFormatted;
				$client -> save();
			}
			//$client = new ClientPhone();
		}
		$s -> i = $phone -> i;
		if ($s -> save()) {
			echo $s->id;
		} else {
			echo "none";
		}
	}

	public function actionLoadCallsFromWebutilsTable(){
		$criteria = new CDbCriteria();
		$criteria -> addCondition('LENGTH(line) > 1');
		$criteria -> addCondition('LENGTH(from_phone) > 1');
//		$criteria -> limit = 100;
		$mCalls = WUPhoneCall::model() -> findAll($criteria);
		$count = 0;
		$noLine = 0;
		if (!empty($mCalls)) {
			foreach ($mCalls as $mCall) {
				$count ++;
				//Добавляем только если не найдено
				if (!(mCall::model()->findByPk($mCall->id))) {
					if (!$mCall -> line) {
						$noLine ++;
						echo "no line".$noLine."<br/>";

					}
					$b = new mCall();
					$b->id = $mCall->id;
					$b->line = $mCall->line;
					$b->fromPhone = $mCall->from_phone;
					$b->toPhone = $mCall->to_phone;
					$b->direction = $mCall->direction;
					$b->status = $mCall->status;
					$b->duration = $mCall->duration;
					$b->type = $mCall->type;
					$b->date = $mCall->date;
					if ($ph = UserPhone::givePhoneByNumber($b->line)) {
						$b->i = $ph->i;
					}
					if (!$b -> save()) {
						$err = $b -> getErrors();
						var_dump($err);
						$errNum ++;
						echo $errNum;
					} else {
						$saved ++;
					}
				} else {
					$inDb++;
					echo "Found in DB, id ".$mCall -> id.', num '.$inDb.'<br/>';
				}
			}
			echo "<br/>Saved $saved <br/>Errors: $errNum <br/> In database: $inDb <br/> ran into $count <br/> no line: $noLine";

		}
	}
}