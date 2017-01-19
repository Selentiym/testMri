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
					'key' => OmriPss::pss(),
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

	/**
	 * Ежеденевная загрузка новых звонков. Смотрит два дня на всякий случай.
	 */
	public function actionLoadStatisticsDaily() {
		ob_start();
		GDCall::importFromGoogleDoc(time() - 24*68*60,true);
		GDCall::importFromGoogleDoc(time(),true);
		$out = ob_get_contents();
		ob_end_clean();

		self::log("LoadStatisticsDaily".PHP_EOL.$out);
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
		$int = DateInterval::createFromDateString("- 1 month");
		$time = new DateTime();
		$time -> add($int);
		$timeEnd = $time -> getTimestamp();
		$timeStart = $time -> add($int) -> getTimestamp();
		//echo $time;
		mCall::loadDataByApi($timeStart, $timeEnd);
	}
}