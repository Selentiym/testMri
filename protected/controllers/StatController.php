<?php

class StatController extends Controller {
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
		);
	}
	public function actionLoadStatistics(){
		$this -> renderPartial("//navBar");
		echo '<a href="'.Yii::app() -> baseUrl."/stat/full".'">К списку линий</a><br/>';
		if ($_GET["time"] > 24*3600*10) {
			GDCall::importFromGoogleDoc($_GET["time"]);
		} else {
			echo "Ошибка в формате времени.";
		}
	}
}