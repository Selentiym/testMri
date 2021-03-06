<?php

class DataController extends Controller {
    //public $defaultAction="addData";
    /**
     * @var GoogleDocModule
     */
    public $GD;

    public function actions(){
        return [
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
        //mCall::loadDataByApi($dateTime -> getTimestamp(), $dateTimeEnd -> getTimestamp());
        ob_start();
        foreach ($f -> ScanGoogle([], $timestamp) as $entry){
            $gd = $f -> buildByEntry($entry);
            if (!$gd -> save()) {
                var_dump($gd -> getErrors());
            }
        }
        $out = ob_get_contents();
        ob_end_clean();
        Yii::app() -> user -> setFlash('savingErrors',$out);
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