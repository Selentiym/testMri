<?php

class DataController extends Controller {
    //public $defaultAction="addData";
    /**
     * @var GoogleDocModule
     */
    public $GD;

    public function actions(){
        return [
            'googlePrice' => array(
                'class' => 'application.controllers.site.FileUploadAction',
                'returnUrl' => array('site/data'),
                'report' => function ($name) {
                    return GDCallFactorable::loadGooglePrices($name);
                },
                'serverName' => Yii::app() -> basePath . '/../files/inputGooglePrice.csv',
                'formFileName' => 'ClientPhoneUpload',
                'checkAccess' => function () {
                    return Yii::app() -> user -> checkAccess('admin');
                }
            ),
        ];
    }

    public function beforeAction(){
        $this -> GD = Yii::app() -> getModule('googleDoc');
        return true;
    }



    public function actionUploadGDCalls($timestamp) {
        $f = $this -> GD -> getFactory();
        $dateTime=new DateTime(date('Y-m-1 00:00:00',$timestamp));
        $dateTimeEnd=new DateTime(date('Y-m-31 23:59:59',$timestamp));
        //$dateTimeEnd = clone $dateTime;
        mCall::loadDataByApi($dateTime -> getTimestamp(), $dateTimeEnd -> getTimestamp());
        ob_start();
        foreach ($f -> ScanGoogle([], $timestamp) as $entry){
            $gd = $f -> buildByEntry($entry);
            if (!$gd -> save()) {
                if (!empty($arr = $gd -> getErrors())) {
                    var_dump($arr);
                }
            }
        }
        $out = ob_get_contents();
        ob_end_clean();
        Yii::app() -> user -> setFlash('savingErrors',$out);
    }
    public function actionChart($from, $to){
        $graph = current($_POST["graphs"]);
        $factors = FactorForm::createGraphFactorsFromConfig($graph);
        //
        $mod = Yii::app() -> getModule('landingData');
        Yii::app() -> getModule('googleDoc');
        /**
         * @type landingDataModule $mod
         */
        $calls = $mod -> getEnterData($mod -> getDefaultLanding() -> textId, landingDataModule::giveCriteriaForTimePeriod($from, $to));
        foreach ($calls as $c) {
            //echo $c -> created.', '.$c -> called.', '.$c -> id.'<br/>\r\n';
        }
        //$rez = GraphicsByFactors::GoogleDocGraphData($factors['filter'],$calls, $factors['view']);
        $rez = GraphicsByFactors::GoogleDocGraphDataVector($factors['filter'],$calls, $factors['view']);
        echo json_encode(array_values([$rez['header']] + $rez['data']));
        //
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
    public function actionGiveUtmData() {
        $mod = Yii::app() -> getModule('landingData');
        /**
         * @type landingDataModule $mod
         */
        $c = BaseCall::giveCriteriaForTimePeriod($_GET['from'],$_GET['to'],'created');
        $c -> addCondition('utm_term IS NOT NULL');
        $utms = [];
        foreach($mod -> getClassData('Enter','spbTomograf',$c) as $e){
            $t = $e -> utm_term;
            if (!$utms[$t]) {
                $utms[$t] = ['clicked' => 0, 'called' => 0, 'formed' => 0];
            }
            $utms[$t]['clicked'] ++;
            $utms[$t]['called'] += $e -> called;
            $utms[$t]['formed'] += $e -> formed;
            $utms[$t]['assigned'] += $e -> getAssigned();
        }
        $del = ';';
        $end = PHP_EOL;
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=utm_terms.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        $fromE = 'UTF-8';
        $toE = 'windows-1251';
        echo mb_convert_encoding("Фраза{$del}Кликов{$del}Звонков{$del}Форм{$del}Записей".$end,$toE,$fromE);
        foreach($utms as $term => $data){
            echo mb_convert_encoding(implode($del,[$term, $data['clicked'], $data['called'], $data['formed'], $data['assigned']]).$end,$toE,$fromE);
        }
    }
}